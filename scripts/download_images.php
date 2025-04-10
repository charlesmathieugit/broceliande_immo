<?php

// Liste des images à télécharger avec leurs URLs
$images = [
    'maison1.jpg' => 'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg',
    'maison2.jpg' => 'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg',
    'appartement1.jpg' => 'https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg',
    'terrain1.jpg' => 'https://images.pexels.com/photos/280221/pexels-photo-280221.jpeg',
    't2-1.jpg' => 'https://images.pexels.com/photos/1571468/pexels-photo-1571468.jpeg',
    'maison-location1.jpg' => 'https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg',
    'studio1.jpg' => 'https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg',
    'no-image.jpg' => 'https://images.pexels.com/photos/1546168/pexels-photo-1546168.jpeg'
];

// Créer les dossiers s'ils n'existent pas
$dirs = [
    __DIR__ . '/../public/uploads/annonces',
    __DIR__ . '/../public/assets/images'
];

foreach ($dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Télécharger les images
foreach ($images as $filename => $url) {
    $destination = $filename === 'no-image.jpg' 
        ? __DIR__ . '/../public/assets/images/' . $filename
        : __DIR__ . '/../public/uploads/annonces/' . $filename;
    
    if (!file_exists($destination)) {
        echo "Téléchargement de $filename...\n";
        $image = file_get_contents($url);
        if ($image !== false) {
            file_put_contents($destination, $image);
            echo "✓ $filename téléchargé avec succès\n";
        } else {
            echo "✗ Erreur lors du téléchargement de $filename\n";
        }
    } else {
        echo "→ $filename existe déjà\n";
    }
}

echo "\nTerminé !\n";
