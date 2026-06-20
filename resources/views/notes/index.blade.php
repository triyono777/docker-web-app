<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Laravel Notes') }}</title>
  <style>
    :root {
      --ink: #171717;
      --paper: #f6f1e7;
      --line: #202020;
      --red: #c43d2d;
      --blue: #2563eb;
      --green: #2f8f46;
      --orange: #d85f17;
      --muted: #686158;
      --shadow: 7px 7px 0 #171717;
      --radius: 8px;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      color: var(--ink);
      background:
        linear-gradient(90deg, rgba(23, 23, 23, .05) 1px, transparent 1px) 0 0 / 28px 28px,
        linear-gradient(rgba(23, 23, 23, .05) 1px, transparent 1px) 0 0 / 28px 28px,
        radial-gradient(circle at 16% 12%, rgba(196, 61, 45, .14), transparent 28%),
        radial-gradient(circle at 88% 16%, rgba(37, 99, 235, .16), transparent 26%),
        var(--paper);
      font-family: "Avenir Next Condensed", "Gill Sans", "Trebuchet MS", sans-serif;
    }

    button,
    input,
    textarea {
      font: inherit;
    }

    .shell {
      width: min(1180px, calc(100% - 32px));
      margin: 0 auto;
      padding: 38px 0;
    }

    .hero,
    .composer,
    .board,
    .service {
      background: rgba(255, 250, 240, .96);
      border: 3px solid var(--line);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }

    .hero {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(260px, 360px);
      gap: 24px;
      align-items: stretch;
      margin-bottom: 24px;
      padding: clamp(24px, 4vw, 44px);
    }

    .eyebrow {
      margin: 0 0 10px;
      color: var(--muted);
      font-size: 14px;
      font-weight: 900;
      letter-spacing: 0;
      text-transform: uppercase;
    }

    h1,
    h2,
    h3 {
      margin: 0;
      font-family: "Rockwell", "American Typewriter", Georgia, serif;
      letter-spacing: 0;
    }

    h1 {
      max-width: 720px;
      font-size: clamp(42px, 7vw, 82px);
      line-height: .92;
    }

    h2 {
      font-size: clamp(28px, 4vw, 44px);
      line-height: 1;
    }

    h3 {
      font-size: 24px;
      line-height: 1.1;
      overflow-wrap: anywhere;
    }

    .lede {
      max-width: 620px;
      margin: 18px 0 0;
      color: #2d2924;
      font-size: clamp(18px, 2.2vw, 24px);
      line-height: 1.35;
    }

    .service {
      display: grid;
      align-content: center;
      gap: 12px;
      padding: 24px;
      box-shadow: 5px 5px 0 rgba(23, 23, 23, .18);
    }

    .service-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      border: 2px solid var(--line);
      border-radius: var(--radius);
      padding: 10px 12px;
      background: #fff;
      font-weight: 900;
    }

    .pill {
      border: 2px solid var(--line);
      border-radius: 999px;
      padding: 5px 10px;
      background: #dff3e4;
      color: #1f6f36;
      white-space: nowrap;
    }

    .workspace {
      display: grid;
      grid-template-columns: minmax(280px, 380px) minmax(0, 1fr);
      gap: 24px;
      align-items: start;
    }

    .composer,
    .board {
      padding: 24px;
    }

    .composer {
      position: sticky;
      top: 24px;
    }

    label {
      display: block;
      margin: 16px 0 8px;
      font-weight: 900;
    }

    label:first-child {
      margin-top: 0;
    }

    input,
    textarea {
      width: 100%;
      border: 2px solid var(--line);
      border-radius: var(--radius);
      background: #fff;
      color: var(--ink);
      padding: 12px 14px;
      outline: none;
    }

    textarea {
      min-height: 130px;
      resize: vertical;
    }

    input:focus,
    textarea:focus {
      border-color: var(--blue);
      box-shadow: 0 0 0 4px rgba(37, 99, 235, .16);
    }

    button,
    .link-button {
      border: 2px solid var(--line);
      border-radius: var(--radius);
      background: var(--ink);
      color: #fff;
      padding: 12px 16px;
      cursor: pointer;
      font-weight: 900;
      text-decoration: none;
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }

    button:hover,
    .link-button:hover {
      background: var(--orange);
      transform: translate(-2px, -2px);
      box-shadow: 4px 4px 0 var(--line);
    }

    .composer button {
      width: 100%;
      margin-top: 18px;
    }

    .notice,
    .error-box {
      margin: 0 0 18px;
      border: 2px solid var(--line);
      border-radius: var(--radius);
      padding: 12px 14px;
      background: #dff3e4;
      font-weight: 900;
    }

    .error-box {
      background: #ffe0dc;
      color: var(--red);
    }

    .board-head {
      display: flex;
      justify-content: space-between;
      gap: 16px;
      align-items: center;
      margin-bottom: 20px;
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 10px;
      margin: 0 0 22px;
    }

    .stats span {
      background: #fff;
      border: 2px solid var(--line);
      border-radius: var(--radius);
      padding: 12px;
      color: var(--muted);
      font-weight: 900;
    }

    .stats strong {
      display: block;
      color: var(--ink);
      font-size: 28px;
      line-height: 1;
    }

    .notes {
      display: grid;
      gap: 12px;
    }

    .note {
      display: grid;
      grid-template-columns: minmax(0, 1fr) auto;
      gap: 14px;
      align-items: start;
      background: #fff;
      border: 2px solid var(--line);
      border-radius: var(--radius);
      padding: 16px;
    }

    .note.done h3 {
      color: var(--muted);
      text-decoration: line-through;
    }

    .note p {
      margin: 8px 0 10px;
      color: #38342e;
      line-height: 1.42;
      overflow-wrap: anywhere;
    }

    .note small {
      color: var(--muted);
      font-weight: 900;
    }

    .note-actions {
      display: grid;
      gap: 8px;
      min-width: 140px;
    }

    .secondary {
      background: #fff;
      color: var(--ink);
    }

    .danger {
      background: #fff;
      color: var(--red);
    }

    .danger:hover {
      background: var(--red);
      color: #fff;
    }

    .empty {
      border: 2px dashed var(--line);
      border-radius: var(--radius);
      padding: 28px;
      color: var(--muted);
      background: rgba(255, 255, 255, .65);
      font-weight: 900;
      text-align: center;
    }

    @media (max-width: 840px) {
      .shell {
        width: min(100% - 20px, 680px);
        padding: 20px 0;
      }

      .hero,
      .workspace,
      .note {
        grid-template-columns: 1fr;
      }

      .composer {
        position: static;
      }

      .board-head {
        align-items: stretch;
        flex-direction: column;
      }

      .stats {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <main class="shell">
    <section class="hero">
      <div>
        <p class="eyebrow">Laravel + MySQL + phpMyAdmin</p>
        <h1>Catatan Belajar Docker</h1>
        <p class="lede">Aplikasi Laravel berjalan di container PHP, data tersimpan di MySQL, dan database bisa dicek lewat phpMyAdmin.</p>
      </div>

      <aside class="service" aria-label="Service Docker">
        <div class="service-row"><span>Laravel</span><span class="pill">:8000</span></div>
        <div class="service-row"><span>MySQL</span><span class="pill">internal</span></div>
        <div class="service-row"><span>phpMyAdmin</span><span class="pill">:8080</span></div>
      </aside>
    </section>

    <section class="workspace">
      <form class="composer" method="POST" action="{{ route('notes.store') }}">
        @csrf

        @if ($errors->any())
          <div class="error-box">
            {{ $errors->first() }}
          </div>
        @endif

        <label for="title">Judul catatan</label>
        <input id="title" name="title" maxlength="120" value="{{ old('title') }}" placeholder="Contoh: migrasi Laravel" required>

        <label for="detail">Detail</label>
        <textarea id="detail" name="detail" maxlength="500" placeholder="Tulis langkah singkat praktikum">{{ old('detail') }}</textarea>

        <button type="submit">Tambah catatan</button>
      </form>

      <div class="board">
        @if (session('status'))
          <p class="notice">{{ session('status') }}</p>
        @endif

        <div class="board-head">
          <div>
            <p class="eyebrow">Tabel MySQL: notes</p>
            <h2>Daftar catatan</h2>
          </div>
          <a class="link-button" href="http://localhost:8080" target="_blank" rel="noreferrer">Buka phpMyAdmin</a>
        </div>

        <div class="stats" aria-label="Ringkasan catatan">
          <span><strong>{{ $totalNotes }}</strong> total</span>
          <span><strong>{{ $completedNotes }}</strong> selesai</span>
          <span><strong>{{ $totalNotes - $completedNotes }}</strong> aktif</span>
        </div>

        <div class="notes">
          @forelse ($notes as $note)
            <article class="note {{ $note->completed ? 'done' : '' }}">
              <div>
                <h3>{{ $note->title }}</h3>
                <p>{{ $note->detail ?: 'Tidak ada detail.' }}</p>
                <small>Dibuat {{ $note->created_at->translatedFormat('d M Y H:i') }}</small>
              </div>

              <div class="note-actions">
                <form method="POST" action="{{ route('notes.update', $note) }}">
                  @csrf
                  @method('PATCH')
                  <input type="hidden" name="completed" value="{{ $note->completed ? 0 : 1 }}">
                  <button class="secondary" type="submit">{{ $note->completed ? 'Buka lagi' : 'Selesai' }}</button>
                </form>

                <form method="POST" action="{{ route('notes.destroy', $note) }}">
                  @csrf
                  @method('DELETE')
                  <button class="danger" type="submit">Hapus</button>
                </form>
              </div>
            </article>
          @empty
            <div class="empty">Belum ada catatan. Tambahkan catatan pertama.</div>
          @endforelse
        </div>
      </div>
    </section>
  </main>
</body>
</html>

