<?php
require 'connexion.php';

function insertInternship($pdo, $user_id, $data) {
    $stmt = $pdo->prepare("INSERT INTO internships (user_id, nom, periode, lieu) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $user_id,
        $data['nom'],
        $data['periode'],
        $data['lieu']
    ]);
}

function deleteInternship($pdo, $internship_id) {
    $stmt = $pdo->prepare("DELETE FROM internships WHERE id = ?");
    $stmt->execute([$internship_id]);
}

function updateInternship($pdo, $internship_id, $data) {
    $stmt = $pdo->prepare("UPDATE internships SET nom = ?, periode = ?, lieu = ? WHERE id = ?");
    $stmt->execute([
        $data['nom'],
        $data['periode'],
        $data['lieu'],
        $internship_id
    ]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'insert') {
        $user_id = htmlspecialchars($_POST['user_id'] ?? '');
        $data = [
            'nom' => htmlspecialchars($_POST['nom'] ?? ''),
            'periode' => htmlspecialchars($_POST['periode'] ?? ''),
            'lieu' => htmlspecialchars($_POST['lieu'] ?? '')
        ];

        try {
            $pdo->beginTransaction();
            insertInternship($pdo, $user_id, $data);
            $pdo->commit();
            echo "Internship inserted for user ID: " . $user_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de l'insertion du stage : " . $e->getMessage());
        }
    } elseif ($action === 'delete') {
        $internship_id = htmlspecialchars($_POST['internship_id'] ?? '');

        try {
            $pdo->beginTransaction();
            deleteInternship($pdo, $internship_id);
            $pdo->commit();
            echo "Internship deleted with ID: " . $internship_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de la suppression du stage : " . $e->getMessage());
        }
    } elseif ($action === 'update') {
        $internship_id = htmlspecialchars($_POST['internship_id'] ?? '');
        $data = [
            'nom' => htmlspecialchars($_POST['nom'] ?? ''),
            'periode' => htmlspecialchars($_POST['periode'] ?? ''),
            'lieu' => htmlspecialchars($_POST['lieu'] ?? '')
        ];

        try {
            $pdo->beginTransaction();
            updateInternship($pdo, $internship_id, $data);
            $pdo->commit();
            echo "Internship updated with ID: " . $internship_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de la modification du stage : " . $e->getMessage());
        }
    } else {
        die("Action non reconnue.");
    }
} else {
    die("Méthode de requête non supportée.");
}
?>