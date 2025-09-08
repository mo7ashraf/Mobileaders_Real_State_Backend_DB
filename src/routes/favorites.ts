import { Router } from 'express';
import { prisma } from '../db.js';
const router = Router();
router.post('/:listingId', async (req, res) => {
  const listingId = String(req.params.listingId);
  const u = await prisma.user.findFirst();
  if (!u) return res.status(400).json({ error: 'no user' });
  await prisma.favorite.upsert({ where: { userId_listingId: { userId: u.id, listingId } }, update: {}, create: { userId: u.id, listingId } });
  res.json({ ok: true });
});
router.delete('/:listingId', async (req, res) => {
  const listingId = String(req.params.listingId);
  const u = await prisma.user.findFirst();
  if (!u) return res.status(400).json({ error: 'no user' });
  await prisma.favorite.delete({ where: { userId_listingId: { userId: u.id, listingId } } }).catch(() => null);
  res.json({ ok: true });
});
router.get('/', async (_req, res) => {
  const u = await prisma.user.findFirst();
  if (!u) return res.json([]);
  const rows = await prisma.favorite.findMany({ where: { userId: u.id }, include: { listing: true } });
  res.json(rows.map(r => r.listing));
});
export default router;
