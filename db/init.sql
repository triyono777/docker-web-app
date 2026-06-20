CREATE TABLE IF NOT EXISTS notes (
  id SERIAL PRIMARY KEY,
  title VARCHAR(120) NOT NULL,
  detail TEXT NOT NULL DEFAULT '',
  completed BOOLEAN NOT NULL DEFAULT FALSE,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

INSERT INTO notes (title, detail, completed)
VALUES
  ('Buat Dockerfile', 'Definisikan base image, dependency, source code, port, dan command start.', TRUE),
  ('Jalankan Compose', 'Start service web dan db dengan docker compose up --build.', FALSE),
  ('Cek volume database', 'Restart container lalu pastikan catatan tetap tersimpan.', FALSE);

