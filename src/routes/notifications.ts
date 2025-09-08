import { Router } from 'express';
import { prisma } from '../db.js';
const router = Router();
router.get('/', async (req, res) => {
  const starred = String(req.query.starred || '') === 'true';
  const where = starred ? { starred: true } : {};
  const rows = await prisma.notification.findMany({ where, orderBy: { createdAt: 'desc' } });
  res.json(rows);
});
router.post('/:id/star', async (req, res) => {
  const id = String(req.params.id);
  const n = await prisma.notification.findUnique({ where: { id } });
  if (!n) {
    const created = await prisma.notification.create({ data: { id, title: 'إشعار', starred: true } });
    return res.json({ ok: true, starred: created.starred });
  }
  const updated = await prisma.notification.update({ where: { id }, data: { starred: !n.starred } });
  res.json({ ok: true, starred: updated.starred });
});
router.post('/read-all', async (_req, res) => {
  await prisma.notification.updateMany({ data: { readAt: new Date() } });
  res.json({ ok: true });
});
export default router;
