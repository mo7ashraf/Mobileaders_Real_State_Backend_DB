import { Router } from 'express';
import { prisma } from '../db.js';
const router = Router();
router.get('/:id', async (req, res) => {
  const id = String(req.params.id);
  const user = await prisma.user.findUnique({ where: { id }, include: { sellerProfile: true, listings: true } });
  if (!user) return res.json({ id, name: 'مكتب عقاري', phone: '966500000000+', title: 'وسيط عقاري', verified: false,
    adsCount: 0, clients: 0, rating: 0, joinedHijri: '1429', joinedText: 'أكتوبر 2021', regionText: 'الرياض • الشمال • الملقا • النرجس', badges: [] });
  const sp = user.sellerProfile;
  res.json({ id: user.id, name: user.name, avatarUrl: user.avatarUrl || '', phone: user.phone, title: user.accRole || 'وسيط عقاري',
    verified: sp?.verified ?? false, adsCount: user.listings.length, clients: sp?.clients ?? 0, rating: sp?.rating ?? 0,
    joinedHijri: sp?.joinedHijri || '1429', joinedText: sp?.joinedText || 'أكتوبر 2021', regionText: sp?.regionText || 'الرياض • الشمال • الملقا • النرجس', badges: sp?.badges || [] });
});
router.get('/:id/listings', async (req, res) => {
  const id = String(req.params.id);
  const rows = await prisma.listing.findMany({ where: { sellerId: id }, orderBy: { createdAt: 'desc' } });
  const FALLBACKS = [
    'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1200&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1570129477492-45c003edd2be?q=80&w=1200&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1600585154526-990dced4db0d?q=80&w=1200&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1572120360610-d971b9d7767c?q=80&w=1200&auto=format&fit=crop',
  ];
  res.json(rows.map((l, idx) => ({ ...l, imageUrl: l.imageUrl || FALLBACKS[idx % FALLBACKS.length], status: l.status || (l.price > 700000 ? 'sell' : 'rent'), category: l.category || 'apartment', tags: l.tags || [] })));
});
export default router;
