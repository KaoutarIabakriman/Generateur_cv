<?php

session_start();

require '../../BD/connexion.php';




$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    die("Utilisateur introuvable.");
}


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


$photoPath = $user['photo'] ? 'BD/uploads/' . $user['photo'] : 'BD/uploads/cv.png';

require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;


$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

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
        font-family: 'Segoe UI', Arial, sans-serif;
        color: #333;
        line-height: 1.6;
        background: white;
        margin: 0;
    }
    .container {
        max-width: 210mm;
        margin: 0 auto;
        padding: 25px 30px;
    }
    header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #eee;
    }
    header img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #e0e0e0;
        margin-bottom: 15px;
    }
    header h1 {
        font-size: 28px;
        color: #444;
        margin: 10px 0 5px;
        letter-spacing: -0.5px;
    }
    header p {
        font-size: 15px;
        color: #666;
        margin: 5px 0;
    }
    section {
        margin-bottom: 25px;
    }
    section h2 {
        font-size: 20px;
        color: #444;
        border-bottom: 2px solid #eee;
        padding-bottom: 6px;
        margin-bottom: 15px;
        position: relative;
    }
    .skills div, .languages div, .interests div {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .skills span, .languages span, .interests span {
        padding: 8px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 18px;
        font-size: 14px;
        color: #555;
        background: white;
        transition: all 0.2s;
    }
    .experience ul, .projects ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .experience li, .projects li {
        padding: 15px;
        margin-bottom: 12px;
        border: 1px solid #f0f0f0;
        border-radius: 6px;
        position: relative;
    }
    .experience li strong, .projects li strong {
        display: block;
        font-size: 16px;
        color: #444;
        margin-bottom: 4px;
    }
    .experience li span, .projects li span {
        font-size: 14px;
        color: #666;
        display: block;
    }
    .languages span {
        padding: 6px 12px;
        border-color: #d0d0d0;
    }
    .interests span {
        border-color: #eee;
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
            <h2>Compétences</h2>
            <div>";
foreach ($skills as $skill) {
    $html .= "<span>" . htmlspecialchars($skill['skill_name']) . " (" . htmlspecialchars($skill['type']) . ")</span>";
}
$html .= "</div></section>
        <section class='experience'>
            <h2>Expériences Professionnelles</h2>
            <ul>";
foreach ($internships as $internship) {
    $html .= "<li>
                  <strong>" . htmlspecialchars($internship['nom']) . "</strong><br>
                  <span>" . htmlspecialchars($internship['lieu']) . " (" . htmlspecialchars($internship['periode']) . ")</span>
              </li>";
}
$html .= "</ul></section>
        <section class='projects'>
            <h2>Projets</h2>
            <ul>";
foreach ($projects as $project) {
    $html .= "<li>
                  <strong>" . htmlspecialchars($project['nom']) . "</strong><br>
                  <span>" . htmlspecialchars($project['date_projet']) . " - " . htmlspecialchars($project['type']) . "</span>
              </li>";
}
$html .= "</ul></section>
        <section class='languages'>
            <h2> Langues</h2>
            <div>";
foreach ($languages as $language) {
    $html .= "<span>" . htmlspecialchars($language['langue']) . " - Niveau " . htmlspecialchars($language['niveau']) . "</span>";
}
$html .= "</div></section>
        <section class='interests'>
            <h2> Centres d'Intérêt</h2>
            <div>";
foreach ($interests as $interest) {
    $html .= "<span>" . htmlspecialchars($interest['interet']) . "</span>";
}
$html .= "</div></section>
    </div>
</body>
</html>";


$dompdf->loadHtml($html);


$dompdf->setPaper('A4', 'portrait');


$dompdf->render();


$savePath = 'cv/';
if (!is_dir($savePath)) {
    
    mkdir($savePath, 0777, true);
}


$pdfFilePath = $savePath . "CV_" . $user['prenom'] . '_' . $user['nom'] . '.pdf';
file_put_contents($pdfFilePath, $dompdf->output());


$dompdf->stream("CV_" . $user['prenom'] . "_" . $user['nom'] . ".pdf", array("Attachment" => 1));
?>
