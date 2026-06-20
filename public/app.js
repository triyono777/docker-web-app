const form = document.querySelector("#noteForm");
const titleInput = document.querySelector("#title");
const detailInput = document.querySelector("#detail");
const formMessage = document.querySelector("#formMessage");
const notesEl = document.querySelector("#notes");
const refreshButton = document.querySelector("#refreshButton");
const template = document.querySelector("#noteTemplate");
const statusLight = document.querySelector("#statusLight");
const statusText = document.querySelector("#statusText");
const totalCount = document.querySelector("#totalCount");
const doneCount = document.querySelector("#doneCount");
const openCount = document.querySelector("#openCount");

async function requestJson(url, options = {}) {
  const response = await fetch(url, {
    headers: { "Content-Type": "application/json" },
    ...options,
  });

  if (!response.ok) {
    const payload = await response.json().catch(() => ({}));
    throw new Error(payload.message || "Request gagal.");
  }

  if (response.status === 204) return null;
  return response.json();
}

function formatDate(value) {
  return new Intl.DateTimeFormat("id-ID", {
    dateStyle: "medium",
    timeStyle: "short",
  }).format(new Date(value));
}

function setStatus(state, text) {
  statusLight.className = `status-light ${state}`;
  statusText.textContent = text;
}

function renderNotes(notes) {
  notesEl.innerHTML = "";
  totalCount.textContent = notes.length;
  doneCount.textContent = notes.filter((note) => note.completed).length;
  openCount.textContent = notes.filter((note) => !note.completed).length;

  if (!notes.length) {
    const empty = document.createElement("div");
    empty.className = "empty";
    empty.textContent = "Belum ada catatan. Tambahkan catatan pertama.";
    notesEl.append(empty);
    return;
  }

  notes.forEach((note) => {
    const node = template.content.firstElementChild.cloneNode(true);
    const checkbox = node.querySelector("input");
    const title = node.querySelector(".note-title");
    const detail = node.querySelector(".note-detail");
    const date = node.querySelector(".note-date");
    const deleteButton = node.querySelector(".delete-button");

    node.classList.toggle("done", note.completed);
    checkbox.checked = note.completed;
    title.textContent = note.title;
    detail.textContent = note.detail || "Tidak ada detail.";
    date.textContent = `Dibuat ${formatDate(note.created_at)}`;

    checkbox.addEventListener("change", async () => {
      await requestJson(`/api/notes/${note.id}`, {
        method: "PATCH",
        body: JSON.stringify({ completed: checkbox.checked }),
      });
      await loadNotes();
    });

    deleteButton.addEventListener("click", async () => {
      await requestJson(`/api/notes/${note.id}`, { method: "DELETE" });
      await loadNotes();
    });

    notesEl.append(node);
  });
}

async function checkHealth() {
  try {
    await requestJson("/api/health");
    setStatus("ok", "Database terhubung");
  } catch (error) {
    setStatus("fail", "Koneksi gagal");
  }
}

async function loadNotes() {
  const notes = await requestJson("/api/notes");
  renderNotes(notes);
}

form.addEventListener("submit", async (event) => {
  event.preventDefault();
  formMessage.textContent = "Menyimpan...";

  try {
    await requestJson("/api/notes", {
      method: "POST",
      body: JSON.stringify({
        title: titleInput.value,
        detail: detailInput.value,
      }),
    });

    form.reset();
    formMessage.textContent = "Catatan tersimpan ke PostgreSQL.";
    await loadNotes();
  } catch (error) {
    formMessage.textContent = error.message;
  }
});

refreshButton.addEventListener("click", loadNotes);

checkHealth();
loadNotes().catch((error) => {
  notesEl.innerHTML = `<div class="empty">${error.message}</div>`;
});

