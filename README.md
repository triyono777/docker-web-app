# Docker Notes Web App

Contoh aplikasi web sederhana memakai Express, PostgreSQL, dan Docker Compose.

## Jalankan

```bash
docker compose up --build
```

Buka:

```text
http://localhost:3000
```

## Hentikan

```bash
docker compose down
```

Hapus data volume database:

```bash
docker compose down -v
```

