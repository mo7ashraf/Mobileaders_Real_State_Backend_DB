INSERT INTO User (id, phone, name, avatarUrl, accRole)
VALUES ('u1','966500000000+','البندري عبد الرحمن','https://i.pravatar.cc/150?img=23','وسيط عقاري')
ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO SellerProfile (id, userId, verified, clients, rating, badges, joinedHijri, joinedText, regionText)
VALUES ('sp1','u1',1,15,4.2,'["موثق","وكيل خدمات"]','1429','أكتوبر 2021','الرياض • الشمال • الملقا • النرجس')
ON DUPLICATE KEY UPDATE verified=VALUES(verified);

INSERT INTO Listing (id,sellerId,title,address,city,price,bedrooms,bathrooms,areaSqm,status,category,imageUrl,tags) VALUES
 ('l1','u1','شقة حديثة','الرياض، الملقا','الرياض',500000,3,2,150,'rent','apartment','https://images.unsplash.com/photo-1600585154526-990dced4db0d?q=80&w=1200&auto=format&fit=crop','["غير مفروش"]'),
 ('l2','u1','فيلا فاخرة','الرياض، النرجس','الرياض',2200000,5,4,380,'sell','villa','https://images.unsplash.com/photo-1570129477492-45c003edd2be?q=80&w=1200&auto=format&fit=crop','["مدفوع","مطلوب"]')
ON DUPLICATE KEY UPDATE title=VALUES(title);

INSERT INTO AppSettings (id, language, theme, notifications, privacy)
VALUES (1,'ar','system','{"all":true,"messages":true,"orders":true,"ads":true}','{"analytics":true,"personalAds":false}')
ON DUPLICATE KEY UPDATE language=VALUES(language);

INSERT INTO SupportSettings (id, whatsapp, email)
VALUES (1,'966500000000','support@example.com')
ON DUPLICATE KEY UPDATE whatsapp=VALUES(whatsapp);
