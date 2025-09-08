import { Router } from 'express';
import { prisma } from '../db.js';
const router = Router();
router.get('/me', async (_req, res) => {
  const u = await prisma.user.findFirst();
  res.json(u || {});
});
router.get('/me/listings', async (_req, res) => {
  const u = await prisma.user.findFirst();
  if (!u) return res.json([]);
  const rows = await prisma.listing.findMany({ where: { sellerId: u.id }, orderBy: { createdAt: 'desc' } });
  res.json(rows);
});
export default router;
