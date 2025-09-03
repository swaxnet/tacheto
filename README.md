# Tacheto Results Portal

Responsive results portal with admin for managing schools and PDF results.

## Getting Started (Local)

1. Create `.env`:

```
PORT=3000
SESSION_SECRET=wekenenosiri_lenye_utu
UPLOAD_DIR=uploads

# MySQL
MYSQL_HOST=127.0.0.1
MYSQL_PORT=3306
MYSQL_USER=root
MYSQL_PASSWORD=yourpassword
MYSQL_DATABASE=tacheto

# Seed admin
ADMIN_DEFAULT_EMAIL=admin@tacheto.local
ADMIN_DEFAULT_PASSWORD=admin123
```

2. Install dependencies

```
npm install
```

3. Initialize DB schema and seed admin

```
npm run init:db
```

4. Start server

```
npm run dev
```

## Docker Compose (local)

`docker-compose.yml` can be adapted to include a MySQL service. Example:

```yaml
services:
  db:
    image: mysql:8
    environment:
      - MYSQL_ROOT_PASSWORD=yourpassword
      - MYSQL_DATABASE=tacheto
    ports:
      - "3306:3306"
    volumes:
      - dbdata:/var/lib/mysql
  app:
    build: .
    environment:
      - PORT=3000
      - SESSION_SECRET=change_this_secret
      - UPLOAD_DIR=uploads
      - MYSQL_HOST=db
      - MYSQL_PORT=3306
      - MYSQL_USER=root
      - MYSQL_PASSWORD=yourpassword
      - MYSQL_DATABASE=tacheto
    ports:
      - "3000:3000"
    depends_on:
      - db
volumes:
  dbdata:
```

## Deploy (Render)

- Set environment variables for MySQL (use Render MySQL or external provider)
- Ensure disks for `/app/uploads` are attached for PDFs persistence
- Trigger `npm run init:db` on first deploy if DB is empty