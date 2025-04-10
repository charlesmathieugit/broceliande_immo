-- Insérer un agent immobilier
INSERT INTO users (firstname, lastname, email, password, role, phone, is_active)
VALUES ('Sophie', 'Martin', 'sophie.martin@broceliande-immo.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'agent', '0299123456', 1)
ON DUPLICATE KEY UPDATE id=id;

-- Insérer des annonces de vente
INSERT INTO annonces (user_id, title, category, price, pieces, surface, description, address, postal_code, city, type_bien, dpe_rating, ges_rating, features) VALUES
((SELECT id FROM users WHERE email = 'sophie.martin@broceliande-immo.fr'), 
'Magnifique maison avec jardin', 'vente', 320000.00, 6, 150.00, 
'Superbe maison familiale avec grand jardin arboré. Cuisine équipée, salon lumineux, 4 chambres, 2 salles de bain. Garage double.',
'12 rue des Chênes', '35380', 'Paimpont', 'maison', 'B', 'A',
'["Jardin", "Garage", "Cuisine équipée", "Double vitrage"]'),

((SELECT id FROM users WHERE email = 'sophie.martin@broceliande-immo.fr'),
'Appartement T3 centre-ville', 'vente', 180000.00, 3, 65.00,
'Bel appartement rénové en centre-ville. Séjour avec balcon, cuisine américaine, 2 chambres. Cave et parking.',
'5 place du Marché', '35160', 'Montfort-sur-Meu', 'appartement', 'C', 'B',
'["Balcon", "Parking", "Cave", "Ascenseur"]'),

((SELECT id FROM users WHERE email = 'sophie.martin@broceliande-immo.fr'),
'Terrain constructible 800m²', 'vente', 85000.00, 0, 800.00,
'Beau terrain plat et viabilisé. Proche commodités. Zone pavillonnaire calme.',
'Lotissement des Bruyères', '35750', 'Iffendic', 'terrain', NULL, NULL,
'["Viabilisé", "Plat", "Vue dégagée"]');

-- Insérer des annonces de location
INSERT INTO annonces (user_id, title, category, price, charges, pieces, surface, description, address, postal_code, city, type_bien, dpe_rating, ges_rating, features) VALUES
((SELECT id FROM users WHERE email = 'sophie.martin@broceliande-immo.fr'),
'T2 meublé centre-ville', 'location', 550.00, 50.00, 2, 45.00,
'Charmant T2 meublé en centre-ville. Cuisine équipée, chambre avec placard, salle d''eau rénovée.',
'8 rue de la Mairie', '35380', 'Plélan-le-Grand', 'appartement', 'D', 'C',
'["Meublé", "Cuisine équipée", "Interphone", "Local à vélos"]'),

((SELECT id FROM users WHERE email = 'sophie.martin@broceliande-immo.fr'),
'Maison T4 avec jardin', 'location', 850.00, 30.00, 4, 95.00,
'Belle maison familiale avec jardin clos. Cuisine aménagée, salon-séjour, 3 chambres, garage.',
'15 rue des Fontaines', '35750', 'Iffendic', 'maison', 'C', 'B',
'["Jardin", "Garage", "Cuisine aménagée", "Buanderie"]'),

((SELECT id FROM users WHERE email = 'sophie.martin@broceliande-immo.fr'),
'Studio étudiant', 'location', 380.00, 40.00, 1, 25.00,
'Studio rénové idéal étudiant. Kitchenette équipée, salle d''eau moderne, placards.',
'3 rue du Collège', '35160', 'Montfort-sur-Meu', 'appartement', 'E', 'D',
'["Meublé", "Kitchenette", "Internet inclus"]');

-- Insérer des images pour les annonces
INSERT INTO images (annonce_id, file_path, is_primary) VALUES
(1, '/uploads/annonces/maison1.jpg', 1),
(1, '/uploads/annonces/maison2.jpg', 0),
(2, '/uploads/annonces/appartement1.jpg', 1),
(3, '/uploads/annonces/terrain1.jpg', 1),
(4, '/uploads/annonces/t2-1.jpg', 1),
(5, '/uploads/annonces/maison-location1.jpg', 1),
(6, '/uploads/annonces/studio1.jpg', 1);
