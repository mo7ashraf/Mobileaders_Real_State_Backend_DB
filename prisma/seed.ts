import { PrismaClient } from '@prisma/client';
const prisma = new PrismaClient();
async function main() {
  const u = await prisma.user.upsert({
    where: { phone: '966500000000+' },
    update: {},
    create: {
      phone: '966500000000+',
      name: 'البندري عبد الرحمن',
      avatarUrl: 'https://i.pravatar.cc/150?img=23',
      accRole: 'وسيط عقاري',
      sellerProfile: {
        create: {
          verified: true, clients: 15, rating: 4.2,
          badges: ['موثق','وكيل خدمات'], joinedHijri: '1429',
          joinedText: 'أكتوبر 2021', regionText: 'الرياض • الشمال • الملقا • النرجس',
        },
      },
    },
  });
  await prisma.listing.createMany({
    data: [
      { sellerId: u.id, title: 'شقة حديثة', address: 'الرياض، الملقا', city: 'الرياض',
        price: 500000, bedrooms: 3, bathrooms: 2, areaSqm: 150, status: 'rent', category: 'apartment',
        imageUrl: 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?q=80&w=1200&auto=format&fit=crop', tags: ['غير مفروش'] },
      { sellerId: u.id, title: 'فيلا فاخرة', address: 'الرياض، النرجس', city: 'الرياض',
        price: 2200000, bedrooms: 5, bathrooms: 4, areaSqm: 380, status: 'sell', category: 'villa',
        imageUrl: 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?q=80&w=1200&auto=format&fit=crop', tags: ['مدفوع','مطلوب'] }
    ],
  });
  await prisma.policy.upsert({ where: { slug: 'privacy' }, update: {}, create: { slug: 'privacy', title: 'سياسة الخصوصية', contentMd: '# سياسة الخصوصية' }});
  await prisma.policy.upsert({ where: { slug: 'payment' }, update: {}, create: { slug: 'payment', title: 'سياسة الدفع', contentMd: '# سياسة الدفع' }});
  await prisma.policy.upsert({ where: { slug: 'terms' }, update: {}, create: { slug: 'terms', title: 'شروط الخدمة', contentMd: '# شروط الخدمة' }});
  await prisma.policy.upsert({ where: { slug: 'ip' }, update: {}, create: { slug: 'ip', title: 'سياسة حقوق الملكية الفكرية', contentMd: '# سياسة الملكية الفكرية' }});
  await prisma.policy.upsert({ where: { slug: 'listing-rules' }, update: {}, create: { slug: 'listing-rules', title: 'ضوابط الإعلانات العقارية', contentMd: '# الضوابط' }});
  await prisma.appSettings.upsert({ where: { id: 1 }, update: {}, create: { id: 1,
    language:'ar', theme:'system',
    notifications: { all:true, messages:true, orders:true, ads:true },
    privacy: { analytics:true, personalAds:false } }});
  await prisma.supportSettings.upsert({ where: { id: 1 }, update: {}, create: { id: 1, whatsapp:'966500000000', email:'support@example.com' }});
}
main().finally(()=>prisma.$disconnect());
