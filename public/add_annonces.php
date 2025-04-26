<?php
// Script pour ajouter des annonces fictives dans la base de données
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Annonce.php';
require_once __DIR__ . '/../core/Database.php';

use Models\Annonce;
use Core\Database;

// Configuration
$addVentes = true;      // Mettre à false pour ne pas ajouter d'annonces de vente
$addLocations = true;   // Mettre à false pour ne pas ajouter d'annonces de location
$nbAnnoncesParType = 5; // Nombre d'annonces par type (vente/location)

$annonceModel = new Annonce(Database::getInstance());

// Utilisateur (administrateur par défaut)
$userId = 1;

// Villes de Bretagne
$villes = [
    ['Rennes', '35000'],
    ['Brest', '29200'],
    ['Saint-Malo', '35400'],
    ['Vannes', '56000'],
    ['Quimper', '29000'],
    ['Lorient', '56100'],
    ['Saint-Brieuc', '22000'],
    ['Concarneau', '29900'],
    ['Dinard', '35800'],
    ['Dinan', '22100']
];

// Types de biens
$typesBiens = ['Appartement', 'Maison', 'Terrain', 'Commerce', 'Bureau'];

// Caractéristiques possibles
$caracteristiques = [
    'Balcon', 'Terrasse', 'Jardin', 'Piscine', 'Parking', 'Garage', 
    'Cave', 'Ascenseur', 'Vue mer', 'Vue dégagée', 'Calme', 'Proche commerces', 
    'Proche écoles', 'Proche transports'
];

// Classes énergétiques
$classesEnergetiques = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

// Fonction pour générer un tableau de caractéristiques aléatoires
function getRandomFeatures($caracteristiques, $min = 2, $max = 5) {
    $nbFeatures = rand($min, $max);
    shuffle($caracteristiques);
    return array_slice($caracteristiques, 0, $nbFeatures);
}

// Fonction pour générer une description en fonction du type de bien
function getDescription($typeBien, $ville, $pieces, $surface) {
    $intro = ["Magnifique", "Superbe", "Charmant", "Beau", "Splendide", "Agréable"];
    $desc = $intro[array_rand($intro)] . " " . strtolower($typeBien);
    
    if ($typeBien === 'Appartement') {
        $desc .= " de $pieces pièces d'une surface de $surface m², situé dans un quartier prisé de $ville. 
        Cet appartement lumineux bénéficie d'une belle exposition et d'une vue dégagée. 
        Il se compose d'une entrée, d'un séjour spacieux, d'une cuisine équipée";
        if ($pieces > 1) {
            $desc .= ", de " . ($pieces - 1) . " chambre" . ($pieces > 2 ? "s" : "");
        }
        $desc .= " et d'une salle de bain moderne. Idéal pour " . ($pieces > 2 ? "une famille" : "un couple ou un investisseur") . ".";
    } 
    elseif ($typeBien === 'Maison') {
        $desc .= " de $pieces pièces d'une surface habitable de $surface m², sur un terrain arboré. 
        Cette maison familiale offre un cadre de vie agréable à $ville, proche de toutes commodités. 
        Elle se compose d'un grand séjour lumineux, d'une cuisine aménagée et équipée, 
        de " . ($pieces - 2) . " chambres" . ($pieces > 4 ? " dont une suite parentale" : "") . ", 
        et " . ($pieces > 4 ? "de 2 salles de bain" : "d'une salle de bain") . ". 
        Le jardin et la terrasse vous permettront de profiter pleinement des beaux jours.";
    }
    elseif ($typeBien === 'Terrain') {
        $desc .= " constructible d'une surface de $surface m² situé à $ville. 
        Ce terrain bénéficie d'une exposition idéale et d'un environnement calme. 
        Viabilisé et libre de constructeur, il n'attend que votre projet immobilier. 
        Proche des commodités et des axes routiers principaux.";
    }
    elseif ($typeBien === 'Commerce') {
        $desc .= " d'une surface de $surface m² idéalement situé en plein cœur de $ville. 
        Cet espace commercial bénéficie d'une excellente visibilité et d'un fort passage piéton. 
        La boutique dispose d'une belle vitrine, d'un espace de vente spacieux et lumineux, 
        ainsi que d'une réserve. Tous commerces autorisés.";
    }
    else { // Bureau
        $desc .= " professionnel d'une surface de $surface m² dans un immeuble moderne à $ville. 
        Ces bureaux se composent de $pieces espaces distincts, d'un accueil, 
        de sanitaires, et d'un espace kitchenette. Idéal pour une entreprise recherchant 
        un environnement de travail de qualité dans un secteur dynamique.";
    }
    
    return $desc;
}

// Compteurs pour les annonces créées
$countVentes = 0;
$countLocations = 0;
$errorCount = 0;

// Générer des annonces de vente
if ($addVentes) {
    for ($i = 0; $i < $nbAnnoncesParType; $i++) {
        // Données aléatoires
        $villeIndex = array_rand($villes);
        $typeBien = $typesBiens[array_rand($typesBiens)];
        $pieces = ($typeBien === 'Terrain') ? 0 : rand(1, 6);
        $surface = ($typeBien === 'Terrain') ? rand(300, 2000) : rand(30, 200);
        $prix = ($typeBien === 'Terrain') ? $surface * rand(80, 300) : $surface * rand(2000, 5000);
        $features = getRandomFeatures($caracteristiques);
        
        $data = [
            'user_id' => $userId,
            'title' => "$typeBien " . ($pieces > 0 ? "$pieces pièces " : "") . "$surface m² - " . $villes[$villeIndex][0],
            'category' => 'vente',
            'price' => $prix,
            'pieces' => $pieces,
            'surface' => $surface,
            'description' => getDescription($typeBien, $villes[$villeIndex][0], $pieces, $surface),
            'address' => rand(1, 100) . " rue " . ["du Général Leclerc", "de Paris", "Saint-Michel", "de Fougères", "de Redon", "de la Libération", "Jean Jaurès"][array_rand(["du Général Leclerc", "de Paris", "Saint-Michel", "de Fougères", "de Redon", "de la Libération", "Jean Jaurès"])],
            'postal_code' => $villes[$villeIndex][1],
            'city' => $villes[$villeIndex][0],
            'type_bien' => $typeBien,
            'dpe_rating' => $classesEnergetiques[array_rand($classesEnergetiques)],
            'ges_rating' => $classesEnergetiques[array_rand($classesEnergetiques)],
            'features' => $features,
            'image_path' => null // Sera associé manuellement via simple_img.php
        ];
        
        try {
            if ($annonceModel->create($data)) {
                $countVentes++;
            }
        } catch (Exception $e) {
            echo "Erreur lors de la création d'une annonce de vente: " . $e->getMessage() . "<br>";
            $errorCount++;
        }
    }
}

// Générer des annonces de location
if ($addLocations) {
    for ($i = 0; $i < $nbAnnoncesParType; $i++) {
        // Données aléatoires
        $villeIndex = array_rand($villes);
        $typeBien = ($typesBiens[array_rand($typesBiens)] === 'Terrain') ? 'Appartement' : $typesBiens[array_rand($typesBiens)]; // Pas de terrain en location
        $pieces = rand(1, 5);
        $surface = rand(20, 150);
        $loyer = $surface * rand(8, 15);
        $charges = round($surface * 1.5);
        $features = getRandomFeatures($caracteristiques);
        
        $data = [
            'user_id' => $userId,
            'title' => "$typeBien " . ($pieces > 0 ? "$pieces pièces " : "") . "$surface m² - " . $villes[$villeIndex][0],
            'category' => 'location',
            'price' => $loyer,
            'charges' => $charges,
            'pieces' => $pieces,
            'surface' => $surface,
            'description' => getDescription($typeBien, $villes[$villeIndex][0], $pieces, $surface),
            'address' => rand(1, 100) . " rue " . ["du Général Leclerc", "de Paris", "Saint-Michel", "de Fougères", "de Redon", "de la Libération", "Jean Jaurès"][array_rand(["du Général Leclerc", "de Paris", "Saint-Michel", "de Fougères", "de Redon", "de la Libération", "Jean Jaurès"])],
            'postal_code' => $villes[$villeIndex][1],
            'city' => $villes[$villeIndex][0],
            'type_bien' => $typeBien,
            'dpe_rating' => $classesEnergetiques[array_rand($classesEnergetiques)],
            'ges_rating' => $classesEnergetiques[array_rand($classesEnergetiques)],
            'features' => $features,
            'image_path' => null // Sera associé manuellement via simple_img.php
        ];
        
        try {
            if ($annonceModel->create($data)) {
                $countLocations++;
            }
        } catch (Exception $e) {
            echo "Erreur lors de la création d'une annonce de location: " . $e->getMessage() . "<br>";
            $errorCount++;
        }
    }
}

// Afficher le résultat
echo "<h1>Génération d'annonces</h1>";
echo "<p>$countVentes annonces de vente ajoutées</p>";
echo "<p>$countLocations annonces de location ajoutées</p>";

if ($errorCount > 0) {
    echo "<p>$errorCount erreurs rencontrées</p>";
}

echo "<p>N'oubliez pas d'ajouter des images pour ces annonces en les plaçant dans le dossier <code>public/annonces/images/</code> avec le numéro de l'annonce comme nom de fichier (ex: 1.jpg)</p>";
echo "<p><a href='/'>Retour à l'accueil</a></p>";
