Real Estate Backend (MySQL + Prisma + Express) — Built 2025-09-08T21:33:54

1) phpMyAdmin:
   - Create DB & user; import sql/init_mysql.sql
   - (Optional) import sql/seed_mysql.sql for demo data

2) Server app (Node.js App on cPanel):
   - Upload project to e.g., ~/apps/backend
   - cp .env.example .env, set:
       DATABASE_URL=mysql://USER:PASSWORD@HOST:3306/DBNAME
       PORT=3000
       CORS_ORIGIN=*
   - npm ci && npx prisma generate && npm run build
   - Start app (startup file: dist/server.js)

3) Endpoints preserved:
   - GET /listings (filters: q, city, category, status, minPrice, maxPrice, bedrooms, bathrooms, sort)
   - GET /sellers/:id
   - GET /sellers/:id/listings
   - GET/PATCH /account/profile, POST /account/avatar
   - GET/PATCH /account/channels, GET/PATCH /account/links
   - GET/PATCH /app/settings
   - GET /support/settings, PATCH /support/settings
   - GET /policies/:slug
   - GET /notifications, POST /notifications/:id/star, POST /notifications/read-all
   - POST /favorites/:listingId, DELETE /favorites/:listingId, GET /favorites
   - GET /orders, GET /orders/:id, POST /orders
   - POST /requests
