# Tacheto Results Portal

Responsive results portal with admin to manage schools and upload PDF results.

## Features
- Admin login (email/password)
- Manage schools
- Create result batches (Kidato + mwaka + kichwa)
- Upload overall summary PDF per batch
- Upload per-school PDFs per batch
- Public listing with search and pagination
- Mobile-friendly UI

## Tech Stack
- Node.js (Express 4), EJS, CSS
- SQLite (data/tacheto.db), Sessions via connect-sqlite3
- Multer for PDF uploads

## Quick start (local)
```bash
npm install
npm run init:db
npm run dev
# Open http://localhost:3000
```
Admin login:
- Email: admin@tacheto.local
- Password: admin123

## Environment
Copy `.env.example` to `.env` and adjust:
```
PORT=3000
SESSION_SECRET=change_this_secret
UPLOAD_DIR=uploads
DB_FILE=data/tacheto.db
ADMIN_DEFAULT_EMAIL=admin@tacheto.local
ADMIN_DEFAULT_PASSWORD=admin123
```

## Docker
Build and run with Docker:
```bash
docker build -t swaxnet/tacheto:latest .
docker run -d --name tacheto -p 3000:3000 \
 -v $(pwd)/data:/app/data \
 -v $(pwd)/uploads:/app/uploads \
 -e SESSION_SECRET=change_this \
 -e ADMIN_DEFAULT_EMAIL=admin@tacheto.local \
 -e ADMIN_DEFAULT_PASSWORD=admin123 \
 swaxnet/tacheto:latest
```
Or with docker compose:
```bash
docker compose up -d --build
```

## GitHub Actions (GHCR)
Pushing to `main` builds and publishes a Docker image to GHCR:
- Image: `ghcr.io/<owner>/<repo>:latest`

## Deploy to Render
- One-click (setup envs and disks after create):
  - Go to Render → New → Blueprint → connect this repo (uses `render.yaml`)
- Or create a Web Service from this repo using the `Dockerfile` and add two disks:
  - Disk 1: name `tacheto-data`, mount `/app/data`, size ≥ 1GB
  - Disk 2: name `tacheto-uploads`, mount `/app/uploads`, size ≥ 1GB
- Environment variables:
  - `PORT=3000`
  - `SESSION_SECRET=your_secret`
  - `UPLOAD_DIR=uploads`
  - `DB_FILE=data/tacheto.db`
  - `ADMIN_DEFAULT_EMAIL=admin@tacheto.local`
  - `ADMIN_DEFAULT_PASSWORD=admin123`

## Persistence
- Database file: `data/tacheto.db`
- Uploaded PDFs: `uploads/`

## License
MIT