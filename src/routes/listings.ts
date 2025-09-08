import { Router } from 'express';
import { prisma } from '../db.js';
const router = Router();
function mapCategory(cat?: string) {
  const s = (cat||'').toLowerCase();
  const map:any = { 'شقة':'apartment','شقق':'apartment','apartment':'apartment','فيلا':'villa','فلل':'villa','villa':'villa',
    'office':'office','مكتب':'office','مكاتب':'office','استراحة':'resthouse','استراحات':'resthouse','resthouse':'resthouse' };
  return map[s] || cat;
}
function mapStatus(st?: string) {
  const s = (st||'').toLowerCase();
  if (s in { 'rent':1, 'للإيجار':1 }) return 'rent';
  if (s in { 'sell':1, 'للبيع':1 }) return 'sell';
  return st;
}
router.get('/', async (req, res) => {
  const q = req.query as any;
  const where: any = {};
  if (q.q) where.OR = [{ title: { contains: q.q as string } }, { address: { contains: q.q as string } }];
  if (q.city) where.city = q.city;
  if (q.category) where.category = mapCategory(q.category);
  if (q.status) where.status = mapStatus(q.status);
  if (q.bedrooms) where.bedrooms = { gte: Number(q.bedrooms) };
  if (q.bathrooms) where.bathrooms = { gte: Number(q.bathrooms) };
  if (q.minPrice || q.maxPrice) where.price = {
    gte: q.minPrice ? Number(q.minPrice) : undefined,
    lte: q.maxPrice ? Number(q.maxPrice) : undefined,
  };
  const orderBy = q.sort === 'price_asc' ? { price: 'asc' } :
                  q.sort === 'price_desc' ? { price: 'desc' } :
                  { createdAt: 'desc' };
  const items = await prisma.listing.findMany({ where, orderBy, include: { seller: { select: { id: true, name: true, avatarUrl: true } } } });
  res.json(items.map(i => ({ ...i, seller: { id: i.seller.id, name: i.seller.name, avatarUrl: i.seller.avatarUrl, verified: false } })));
});
export default router;
