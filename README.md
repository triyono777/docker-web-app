# Laravel Notes Docker App

Contoh aplikasi web sederhana memakai Laravel, MySQL, phpMyAdmin, dan Docker Compose.

## Jalankan

```bash
docker compose up --build
```

URL:

- Laravel app: http://localhost:8000
- phpMyAdmin: http://localhost:8080

Login phpMyAdmin:

- Server: `mysql`
- Username: `laravel_user`
- Password: `laravel_pass`
- Database: `laravel_notes`

## Hentikan

```bash
docker compose down
```

Hapus container sekaligus volume database:

```bash
docker compose down -v
```

## Service

- `app` - Laravel di PHP 8.4, port `8000`
- `mysql` - MySQL 8.4, volume `mysql_data`
- `phpmyadmin` - phpMyAdmin, port `8080`

