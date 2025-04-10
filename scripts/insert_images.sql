-- Suppression des anciennes données
DELETE FROM `images`;

-- Insertion des nouvelles images
INSERT INTO `images` (`annonce_id`, `file_path`, `is_active`, `created_at`) VALUES
(1, 'maison-jardin-1.jpg', 1, NOW()),
(1, 'maison-jardin-2.jpg', 1, NOW()),
(1, 'maison-jardin-3.jpg', 1, NOW()),
(2, 'appartement-t3-1.jpg', 1, NOW()),
(2, 'appartement-t3-2.jpg', 1, NOW()),
(3, 'terrain-1.jpg', 1, NOW()),
(4, 'local-commercial-1.jpg', 1, NOW()),
(5, 'maison-location-1.jpg', 1, NOW()),
(6, 'studio-1.jpg', 1, NOW());
