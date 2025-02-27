<?php
require 'connexion.php';

function insertInterest($pdo, $user_id, $data) {
    $stmt = $pdo->prepare("INSERT INTO interests (user_id, interet) VALUES (?, ?)");
    $stmt->execute([
        $user_id,
        $data['interet']
    ]);
}

function deleteInterest($pdo, $interest_id) {
    $stmt = $pdo->prepare("DELETE FROM interests WHERE id = ?");
    $stmt->execute([$interest_id]);
}

function updateInterest($pdo, $interest_id, $data) {
    $stmt = $pdo->prepare("UPDATE interests SET interet = ? WHERE id = ?");
    $stmt->execute([
        $data['interet'],
        $interest_id
    ]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'insert') {
        $user_id = htmlspecialchars($_POST['user_id'] ?? '');
        $data = [
            'interet' => htmlspecialchars($_POST['interet'] ?? '')
        ];

        try {
            $pdo->beginTransaction();
            insertInterest($pdo, $user_id, $data);
            $pdo->commit();
            echo "Interest inserted for user ID: " . $user_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de l'insertion de l'intérêt : " . $e->getMessage());
        }
    } elseif ($action === 'delete') {
        $interest_id = htmlspecialchars($_POST['interest_id'] ?? '');

        try {
            $pdo->beginTransaction();
            deleteInterest($pdo, $interest_id);
            $pdo->commit();
            echo "Interest deleted with ID: " . $interest_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de la suppression de l'intérêt : " . $e->getMessage());
        }
    } elseif ($action === 'update') {
        $interest_id = htmlspecialchars($_POST['interest_id'] ?? '');
        $data = [
            'interet' => htmlspecialchars($_POST['interet'] ?? '')
        ];

        try {
            $pdo->beginTransaction();
            updateInterest($pdo, $interest_id, $data);
            $pdo->commit();
            echo "Interest updated with ID: " . $interest_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de la modification de l'intérêt : " . $e->getMessage());
        }
    } else {
        die("Action non reconnue.");
    }
} else {
    die("Méthode de requête non supportée.");
}
?>