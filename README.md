# Real Estate Backend — FULL PROJECT (Express + Prisma + Postgres)

Built: 2025-09-08T20:48:37

## Quick start
```bash
cp .env.example .env
docker-compose up -d db
npm i
npx prisma generate
npm run prisma:dev
npm run seed         # optional
npm run dev          # http://localhost:3000
```

## Endpoint parity (matches old mock)
- GET /listings?q=&city=&category=&status=&minPrice=&maxPrice=&bedrooms=&bathrooms=&sort
- GET /sellers/:id
- GET /sellers/:id/listings
- GET /account/profile
- PATCH /account/profile
- POST /account/avatar
- GET /account/channels
- PATCH /account/channels
- GET /account/links
- PATCH /account/links
- GET /app/settings
- PATCH /app/settings
- GET /policies/:slug  (returns {slug,title,content} with Markdown)
- GET /support/settings
- PATCH /support/settings
- GET /notifications
- POST /notifications/:id/star
- POST /notifications/read-all
- POST /favorites/:listingId
- DELETE /favorites/:listingId
- GET /favorites
- GET /orders
- GET /orders/:id
- POST /orders
- POST /requests
- (compat) GET /users/me
- (compat) GET /users/me/listings

## Import your old mock JSON (optional)
Place your old files under `assets/mock/` in this project:
- sellers.json, listings.json, users.json
Then:
```bash
npm run import:mock
```

## Deploy
- Docker compose on server: `docker-compose up -d --build`
- Reverse proxy with Nginx to port 3000, add HTTPS (Let's Encrypt).
