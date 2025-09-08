import { Router } from 'express';
import { prisma } from '../db.js';
const router = Router();
router.get('/:slug', async (req, res) => {
  const slug = String(req.params.slug);
  const p = await prisma.policy.findUnique({ where: { slug } });
  if (!p) return res.status(404).json({ error: 'not found' });
  res.json({ slug, title: p.title, content: p.contentMd });
});
export default router;
