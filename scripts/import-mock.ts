import { PrismaClient } from '@prisma/client';
import fs from 'fs';
import path from 'path';
const prisma = new PrismaClient();
const ROOT = process.cwd();
const M = (...p: string[]) => path.join(ROOT, 'assets', 'mock', ...p);
async function readJson(file: string) { try { return JSON.parse(fs.readFileSync(M(file), 'utf-8')); } catch { return null; } }
async function main() {
  const sellers = await readJson('sellers.json') || [];
  const listings = await readJson('listings.json') || [];
  const users = await readJson('users.json') || [];
  for (const s of sellers) {
    const phone = (s.phone || '966500000000+').toString();
    const u = await prisma.user.upsert({ where: { phone }, update: { name: s.name || 'مستخدم', avatarUrl: s.avatarUrl || s.avatar, accRole: s.title || 'وسيط عقاري' }, create: { phone, name: s.name || 'مستخدم', avatarUrl: s.avatarUrl || s.avatar, accRole: s.title || 'وسيط عقاري' } });
    await prisma.sellerProfile.upsert({ where: { userId: u.id }, update: { verified: !!s.verified, clients: Number(s.clients||0), rating: Number(s.rating||0), badges: Array.isArray(s.badges)? s.badges:[], joinedHijri: s.joinedHijri, joinedText: s.joinedText, regionText: s.regionText }, create: { userId: u.id, verified: !!s.verified, clients: Number(s.clients||0), rating: Number(s.rating||0), badges: Array.isArray(s.badges)? s.badges:[], joinedHijri: s.joinedHijri, joinedText: s.joinedText, regionText: s.regionText } });
  }
  for (const l of listings) {
    let sellerId = String(l.sellerId || '');
    if (!sellerId && users[0]) sellerId = String(users[0].id || users[0]._id);
    if (!sellerId && sellers[0]) sellerId = String(sellers[0].id || sellers[0]._id);
    if (!sellerId) continue;
    await prisma.listing.create({ data: { sellerId, title: l.title || 'إعلان', address: l.address || '', city: l.city || 'الرياض', price: Number(l.price||0), bedrooms: Number(l.bedrooms||0), bathrooms: Number(l.bathrooms||0), areaSqm: Number(l.areaSqm||0), status: (l.status || (l.price>700000?'sell':'rent')).toString(), category: (l.category || 'apartment').toString(), imageUrl: l.imageUrl || (Array.isArray(l.images)&&l.images[0]) || (Array.isArray(l.gallery)&&l.gallery[0]) || null, tags: Array.isArray(l.tags)? l.tags: [] } });
  }
  console.log('Import finished.');
}
main().finally(()=> prisma.$disconnect());
