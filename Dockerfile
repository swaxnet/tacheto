# syntax=docker/dockerfile:1

FROM node:18-alpine AS base
WORKDIR /app
ENV NODE_ENV=production

# Install dependencies only (leverage cache)
FROM base AS deps
COPY package.json package-lock.json* ./
RUN npm ci --only=production

# Build runtime image
FROM base AS runtime
# Create non-root user
RUN addgroup -S app && adduser -S app -G app

# App directories for persistent data
VOLUME ["/app/data", "/app/uploads"]

# Copy deps
COPY --from=deps /app/node_modules ./node_modules

# Copy application
COPY . .

# Ensure required folders exist at runtime
RUN mkdir -p /app/data /app/uploads /app/public /app/public/css \
  && chown -R app:app /app

USER app
EXPOSE 3000

# Environment defaults (can be overridden)
ENV PORT=3000 \
  SESSION_SECRET=change_this_secret \
  UPLOAD_DIR=uploads \
  DB_FILE=data/tacheto.db \
  ADMIN_DEFAULT_EMAIL=admin@tacheto.local \
  ADMIN_DEFAULT_PASSWORD=admin123

# Initialize DB if not present on container start, then run server
CMD ["sh", "-c", "[ -f \"$DB_FILE\" ] || node scripts/init-db.js; node server.js"] 