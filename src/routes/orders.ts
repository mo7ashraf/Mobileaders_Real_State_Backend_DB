import { Router } from 'express';
import { prisma } from '../db.js';
const router = Router();
router.get('/', async (_req, res) => { const rows = await prisma.order.findMany({ orderBy: { createdAt: 'desc' } }); res.json(rows); });
router.get('/:id', async (req, res) => {
  const row = await prisma.order.findUnique({ where: { id: String(req.params.id) } });
  if (!row) return res.status(404).json({ error: 'not found' });
  res.json(row);
});
router.post('/', async (req, res) => {
  const u = await prisma.user.findFirst();
  if (!u) return res.status(400).json({ error: 'no user' });
  const row = await prisma.order.create({ data: { userId: u.id, status: 'open', notes: req.body?.notes } });
  res.status(201).json(row);
});
export default router;
