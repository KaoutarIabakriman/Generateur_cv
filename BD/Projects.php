<?php
require 'connexion.php';

function insertProject($pdo, $user_id, $data) {
    $stmt = $pdo->prepare("INSERT INTO projects (user_id, nom, date_projet, type) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $user_id,
        $data['nom'],
        $data['date_projet'],
        $data['type']
    ]);
}

function deleteProject($pdo, $project_id) {
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$project_id]);
}

function updateProject($pdo, $project_id, $data) {
    $stmt = $pdo->prepare("UPDATE projects SET nom = ?, date_projet = ?, type = ? WHERE id = ?");
    $stmt->execute([
        $data['nom'],
        $data['date_projet'],
        $data['type'],
        $project_id
    ]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'insert') {
        $user_id = htmlspecialchars($_POST['user_id'] ?? '');
        $data = [
            'nom' => htmlspecialchars($_POST['nom'] ?? ''),
            'date_projet' => htmlspecialchars($_POST['date_projet'] ?? ''),
            'type' => htmlspecialchars($_POST['type'] ?? 'personal')
        ];

        try {
            $pdo->beginTransaction();
            insertProject($pdo, $user_id, $data);
            $pdo->commit();
            echo "Project inserted for user ID: " . $user_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de l'insertion du projet : " . $e->getMessage());
        }
    } elseif ($action === 'delete') {
        $project_id = htmlspecialchars($_POST['project_id'] ?? '');

        try {
            $pdo->beginTransaction();
            deleteProject($pdo, $project_id);
            $pdo->commit();
            echo "Project deleted with ID: " . $project_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de la suppression du projet : " . $e->getMessage());
        }
    } elseif ($action === 'update') {
        $project_id = htmlspecialchars($_POST['project_id'] ?? '');
        $data = [
            'nom' => htmlspecialchars($_POST['nom'] ?? ''),
            'date_projet' => htmlspecialchars($_POST['date_projet'] ?? ''),
            'type' => htmlspecialchars($_POST['type'] ?? 'personal')
        ];

        try {
            $pdo->beginTransaction();
            updateProject($pdo, $project_id, $data);
            $pdo->commit();
            echo "Project updated with ID: " . $project_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de la modification du projet : " . $e->getMessage());
        }
    } else {
        die("Action non reconnue.");
    }
} else {
    die("Méthode de requête non supportée.");
}
?>