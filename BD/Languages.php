<?php
require 'connexion.php';

function insertLanguage($pdo, $user_id, $data) {
    $stmt = $pdo->prepare("INSERT INTO languages (user_id, langue, niveau) VALUES (?, ?, ?)");
    $stmt->execute([
        $user_id,
        $data['langue'],
        $data['niveau']
    ]);
}

function deleteLanguage($pdo, $language_id) {
    $stmt = $pdo->prepare("DELETE FROM languages WHERE id = ?");
    $stmt->execute([$language_id]);
}

function updateLanguage($pdo, $language_id, $data) {
    $stmt = $pdo->prepare("UPDATE languages SET langue = ?, niveau = ? WHERE id = ?");
    $stmt->execute([
        $data['langue'],
        $data['niveau'],
        $language_id
    ]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'insert') {
        $user_id = htmlspecialchars($_POST['user_id'] ?? '');
        $data = [
            'langue' => htmlspecialchars($_POST['langue'] ?? ''),
            'niveau' => htmlspecialchars($_POST['niveau'] ?? 'A1')
        ];

        try {
            $pdo->beginTransaction();
            insertLanguage($pdo, $user_id, $data);
            $pdo->commit();
            echo "Language inserted for user ID: " . $user_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de l'insertion de la langue : " . $e->getMessage());
        }
    } elseif ($action === 'delete') {
        $language_id = htmlspecialchars($_POST['language_id'] ?? '');

        try {
            $pdo->beginTransaction();
            deleteLanguage($pdo, $language_id);
            $pdo->commit();
            echo "Language deleted with ID: " . $language_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de la suppression de la langue : " . $e->getMessage());
        }
    } elseif ($action === 'update') {
        $language_id = htmlspecialchars($_POST['language_id'] ?? '');
        $data = [
            'langue' => htmlspecialchars($_POST['langue'] ?? ''),
            'niveau' => htmlspecialchars($_POST['niveau'] ?? 'A1')
        ];

        try {
            $pdo->beginTransaction();
            updateLanguage($pdo, $language_id, $data);
            $pdo->commit();
            echo "Language updated with ID: " . $language_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de la modification de la langue : " . $e->getMessage());
        }
    } else {
        die("Action non reconnue.");
    }
} else {
    die("Méthode de requête non supportée.");
}
?>