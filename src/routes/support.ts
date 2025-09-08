import { Router } from 'express';
import { prisma } from '../db.js';
const router = Router();
router.get('/settings', async (_req, res) => {
  const s = await prisma.supportSettings.findUnique({ where: { id: 1 } });
  res.json(s || { whatsapp: '966500000000', email: 'support@example.com' });
});
router.patch('/settings', async (req, res) => {
  await prisma.supportSettings.upsert({ where: { id: 1 }, update: req.body || {}, create: { id: 1, ...(req.body || {}) } });
  res.json({ ok: true });
});
export default router;
