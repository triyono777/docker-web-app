const express = require("express");
const path = require("path");
const { Pool } = require("pg");

const app = express();
const port = Number(process.env.PORT || 3000);
const databaseUrl = process.env.DATABASE_URL || "postgres://docker_user:docker_pass@localhost:5432/docker_notes";

const pool = new Pool({
  connectionString: databaseUrl,
});

app.use(express.json());
app.use(express.static(path.join(__dirname, "..", "public")));

async function waitForDatabase(retries = 20) {
  for (let attempt = 1; attempt <= retries; attempt += 1) {
    try {
      await pool.query("SELECT 1");
      return;
    } catch (error) {
      if (attempt === retries) throw error;
      await new Promise((resolve) => setTimeout(resolve, 1000));
    }
  }
}

async function ensureSchema() {
  await pool.query(`
    CREATE TABLE IF NOT EXISTS notes (
      id SERIAL PRIMARY KEY,
      title VARCHAR(120) NOT NULL,
      detail TEXT NOT NULL DEFAULT '',
      completed BOOLEAN NOT NULL DEFAULT FALSE,
      created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
    )
  `);
}

function cleanText(value, maxLength) {
  return String(value || "").trim().slice(0, maxLength);
}

app.get("/api/health", async (_request, response) => {
  await pool.query("SELECT 1");
  response.json({ status: "ok" });
});

app.get("/api/notes", async (_request, response, next) => {
  try {
    const result = await pool.query(`
      SELECT id, title, detail, completed, created_at
      FROM notes
      ORDER BY created_at DESC, id DESC
    `);
    response.json(result.rows);
  } catch (error) {
    next(error);
  }
});

app.post("/api/notes", async (request, response, next) => {
  try {
    const title = cleanText(request.body.title, 120);
    const detail = cleanText(request.body.detail, 400);

    if (!title) {
      response.status(400).json({ message: "Judul wajib diisi." });
      return;
    }

    const result = await pool.query(
      `
        INSERT INTO notes (title, detail)
        VALUES ($1, $2)
        RETURNING id, title, detail, completed, created_at
      `,
      [title, detail]
    );

    response.status(201).json(result.rows[0]);
  } catch (error) {
    next(error);
  }
});

app.patch("/api/notes/:id", async (request, response, next) => {
  try {
    const id = Number(request.params.id);
    const completed = Boolean(request.body.completed);

    if (!Number.isInteger(id)) {
      response.status(400).json({ message: "ID tidak valid." });
      return;
    }

    const result = await pool.query(
      `
        UPDATE notes
        SET completed = $1
        WHERE id = $2
        RETURNING id, title, detail, completed, created_at
      `,
      [completed, id]
    );

    if (!result.rowCount) {
      response.status(404).json({ message: "Catatan tidak ditemukan." });
      return;
    }

    response.json(result.rows[0]);
  } catch (error) {
    next(error);
  }
});

app.delete("/api/notes/:id", async (request, response, next) => {
  try {
    const id = Number(request.params.id);

    if (!Number.isInteger(id)) {
      response.status(400).json({ message: "ID tidak valid." });
      return;
    }

    const result = await pool.query("DELETE FROM notes WHERE id = $1", [id]);

    if (!result.rowCount) {
      response.status(404).json({ message: "Catatan tidak ditemukan." });
      return;
    }

    response.status(204).end();
  } catch (error) {
    next(error);
  }
});

app.use((error, _request, response, _next) => {
  console.error(error);
  response.status(500).json({ message: "Server error." });
});

async function start() {
  await waitForDatabase();
  await ensureSchema();
  app.listen(port, () => {
    console.log(`Docker Notes app running on http://localhost:${port}`);
  });
}

start().catch((error) => {
  console.error("Failed to start app", error);
  process.exit(1);
});

