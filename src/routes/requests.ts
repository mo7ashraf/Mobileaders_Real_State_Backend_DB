import { Router } from 'express';
import { prisma } from '../db.js';
const router = Router();
router.post('/', async (req, res) => {
  const u = await prisma.user.findFirst();
  if (!u) return res.status(400).json({ error: 'no user' });
  const b = req.body || {};
  const row = await prisma.propertyRequest.create({ data: { userId: u.id, type: b.type || 'شقة', city: b.city || 'الرياض', budgetMin: Number(b.budgetMin||0), budgetMax: Number(b.budgetMax||0), bedrooms: Number(b.bedrooms||0), bathrooms: Number(b.bathrooms||0), notes: b.notes } });
  res.status(201).json(row);
});
export default router;
