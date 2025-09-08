import { Router } from 'express';
import { prisma } from '../db.js';
const account = Router(); const app = Router();
account.get('/profile', async (_req, res) => {
  const u = await prisma.user.findFirst();
  res.json({ name: u?.name || '', bio: u?.bio || '', orgName: u?.orgName || '', role: u?.accRole || 'وسيط عقاري', avatarUrl: u?.avatarUrl || '' });
});
account.patch('/profile', async (req, res) => {
  const u0 = await prisma.user.findFirst();
  const id = u0?.id || (await prisma.user.create({ data: { phone: '966500000000+', name: 'مستخدم' } })).id;
  const { name, bio, orgName, role, avatarUrl } = req.body || {};
  const u = await prisma.user.update({ where: { id }, data: { name, bio, orgName, accRole: role, avatarUrl } });
  res.json({ ok: true, profile: u });
});
account.post('/avatar', async (req, res) => {
  const { avatarUrl } = req.body || {};
  const u0 = await prisma.user.findFirst();
  if (!u0) return res.json({ ok: true, avatarUrl: avatarUrl || '' });
  await prisma.user.update({ where: { id: u0.id }, data: { avatarUrl } });
  res.json({ ok: true, avatarUrl: avatarUrl || '' });
});
account.get('/channels', async (_req, res) => {
  const u = await prisma.user.findFirst();
  res.json(u?.channels || { chatInApp: true, whatsapp: false, call: false });
});
account.patch('/channels', async (req, res) => {
  const u0 = await prisma.user.findFirst();
  const id = u0?.id || (await prisma.user.create({ data: { phone: '966500000000+', name: 'مستخدم' } })).id;
  const channels = req.body || {};
  await prisma.user.update({ where: { id }, data: { channels } });
  res.json({ ok: true, channels });
});
account.get('/links', async (_req, res) => {
  const u = await prisma.user.findFirst();
  res.json(u?.socialLinks || { twitter: '', snapchat: '', tiktok: '', facebook: '', website: '' });
});
account.patch('/links', async (req, res) => {
  const u0 = await prisma.user.findFirst();
  const id = u0?.id || (await prisma.user.create({ data: { phone: '966500000000+', name: 'مستخدم' } })).id;
  const socialLinks = req.body || {};
  await prisma.user.update({ where: { id }, data: { socialLinks } });
  res.json({ ok: true, links: socialLinks });
});
app.get('/settings', async (_req, res) => {
  const s = await prisma.appSettings.findUnique({ where: { id: 1 } });
  res.json(s || { language: 'ar', theme: 'system', notifications: { all: true, messages: true, orders: true, ads: true }, privacy: { analytics: true, personalAds: false } });
});
app.patch('/settings', async (req, res) => {
  const cur = await prisma.appSettings.upsert({ where: { id: 1 }, update: {}, create: { id: 1 } });
  const next = { ...cur, ...(req.body || {}) };
  await prisma.appSettings.update({ where: { id: 1 }, data: { language: next.language, theme: next.theme, notifications: next.notifications as any, privacy: next.privacy as any } });
  res.json({ ok: true });
});
export default { account, app };
