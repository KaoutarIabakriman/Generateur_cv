<?php
// Démarrer la session
session_start();

require '../../BD/connexion.php';



// Vérifier si l'utilisateur est connecté
$user_id = $_SESSION['user_id'];
// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    die("Utilisateur introuvable.");
}

// Récupérer les données associées à l'utilisateur
function fetchUserData($pdo, $query, $user_id) {
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$interests = fetchUserData($pdo, "SELECT interet FROM interests WHERE user_id = ?", $user_id);
$internships = fetchUserData($pdo, "SELECT * FROM internships WHERE user_id = ?", $user_id);
$languages = fetchUserData($pdo, "SELECT * FROM languages WHERE user_id = ?", $user_id);
$projects = fetchUserData($pdo, "SELECT * FROM projects WHERE user_id = ?", $user_id);
$skills = fetchUserData($pdo, "SELECT * FROM skills WHERE user_id = ?", $user_id);

// Vérifier si la photo de profil existe, sinon utiliser une photo par défaut
$photoPath = $user['photo'] ? 'BD/uploads/' . $user['photo'] : 'BD/uploads/cv.png';

// Charger domPDF
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Initialiser domPDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

// HTML du CV
$html = "
<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>CV de " . htmlspecialchars($user['prenom'] . ' ' . $user['nom']) . "</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 210mm;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        header {
            text-align: center;
            margin-bottom: 30px;
        }
        header img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            font-size: 28px;
            color: #333;
            margin: 10px 0;
        }
        header p {
            font-size: 16px;
            color: #777;
        }
        section {
            margin-bottom: 30px;
        }
        section h2 {
            font-size: 22px;
            color: #333;
            border-bottom: 2px solid #f4f4f4;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .skills span, .languages span, .interests span {
            background-color: #e0e0e0;
            padding: 8px 15px;
            margin: 5px;
            border-radius: 15px;
            font-size: 14px;
            display: inline-block;
        }
        .skills span {
            background-color: #e0e0e0;
        }
        .languages span {
            background-color: #d0f0f0;
        }
        .interests span {
            background-color: #f0f0f0;
        }
        .experience, .projects {
            list-style: none;
            padding: 0;
        }
        .experience li, .projects li {
            background-color: #f8f8f8;
            padding: 12px;
            margin-bottom: 12px;
            border-radius: 6px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
        }
        .experience li strong, .projects li strong {
            font-size: 16px;
            color: #333;
        }
        .experience li span, .projects li span {
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class='container'>
        <header>
            <img src='" . htmlspecialchars($photoPath) . "' alt='Photo de profil'>
            <h1>" . htmlspecialchars($user['prenom'] . ' ' . $user['nom']) . "</h1>
            <p>" . htmlspecialchars($user['adresse']) . " | " . htmlspecialchars($user['telephone']) . " | " . htmlspecialchars($user['email']) . "</p>
        </header>
        <section class='skills'>
            <h2>🛠️ Compétences</h2>
            <div>";
foreach ($skills as $skill) {
    $html .= "<span>" . htmlspecialchars($skill['skill_name']) . " (" . htmlspecialchars($skill['type']) . ")</span>";
}
$html .= "</div></section>
        <section class='experience'>
            <h2>💼 Expériences Professionnelles</h2>
            <ul>";
foreach ($internships as $internship) {
    $html .= "<li>
                  <strong>" . htmlspecialchars($internship['nom']) . "</strong><br>
                  <span>" . htmlspecialchars($internship['lieu']) . " (" . htmlspecialchars($internship['periode']) . ")</span>
              </li>";
}
$html .= "</ul></section>
        <section class='projects'>
            <h2>🚀 Projets</h2>
            <ul>";
foreach ($projects as $project) {
    $html .= "<li>
                  <strong>" . htmlspecialchars($project['nom']) . "</strong><br>
                  <span>" . htmlspecialchars($project['date_projet']) . " - " . htmlspecialchars($project['type']) . "</span>
              </li>";
}
$html .= "</ul></section>
        <section class='languages'>
            <h2>🌍 Langues</h2>
            <div>";
foreach ($languages as $language) {
    $html .= "<span>" . htmlspecialchars($language['langue']) . " - Niveau " . htmlspecialchars($language['niveau']) . "</span>";
}
$html .= "</div></section>
        <section class='interests'>
            <h2>🎨 Centres d'Intérêt</h2>
            <div>";
foreach ($interests as $interest) {
    $html .= "<span>" . htmlspecialchars($interest['interet']) . "</span>";
}
$html .= "</div></section>
    </div>
</body>
</html>";

// Charger le HTML dans domPDF
$dompdf->loadHtml($html);

// (Optionnel) Définir la taille du papier
$dompdf->setPaper('A4', 'portrait');

// Rendre le PDF
$dompdf->render();

// Définir le chemin pour sauvegarder le fichier
$savePath = '/cv';
if (!is_dir($savePath)) {
    // Créer le dossier si nécessaire
    mkdir($savePath, 0777, true);
}

// Sauvegarder le PDF dans le dossier
$pdfFilePath = $savePath . "CV_" . $user['prenom'] . '_' . $user['nom'] . '.pdf';
file_put_contents($pdfFilePath, $dompdf->output());

// Télécharger le PDF
$dompdf->stream("CV_" . $user['prenom'] . "_" . $user['nom'] . ".pdf", array("Attachment" => 1));
?>
