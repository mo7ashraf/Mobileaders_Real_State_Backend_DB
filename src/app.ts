import express from 'express';
import cors from 'cors';
import helmet from 'helmet';
import compression from 'compression';
import dotenv from 'dotenv';
dotenv.config();

import listings from './routes/listings.js';
import sellers from './routes/sellers.js';
import settings from './routes/settings.js';
import policies from './routes/policies.js';
import support from './routes/support.js';
import notifications from './routes/notifications.js';
import favorites from './routes/favorites.js';
import orders from './routes/orders.js';
import clients from './routes/clients.js';
import requests from './routes/requests.js';

const app = express();
app.use(cors({ origin: process.env.CORS_ORIGIN || '*' }));
app.use(helmet());
app.use(compression());
app.use(express.json({ limit: '2mb' }));

app.get('/', (_req, res) => res.json({ ok: true }));

app.use('/listings', listings);
app.use('/sellers', sellers);
app.use('/account', settings.account);
app.use('/app', settings.app);
app.use('/policies', policies);
app.use('/support', support);
app.use('/notifications', notifications);
app.use('/favorites', favorites);
app.use('/orders', orders);
app.use('/clients', clients);
app.use('/requests', requests);

export default app;
