<?php
require 'connexion.php';

function insertSkill($pdo, $user_id, $data) {
    $stmt = $pdo->prepare("INSERT INTO skills (user_id, type, skill_name) VALUES (?, ?, ?)");
    $stmt->execute([
        $user_id,
        $data['type'],
        $data['skill_name']
    ]);
}

function deleteSkill($pdo, $skill_id) {
    $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
    $stmt->execute([$skill_id]);
}

function updateSkill($pdo, $skill_id, $data) {
    $stmt = $pdo->prepare("UPDATE skills SET type = ?, skill_name = ? WHERE id = ?");
    $stmt->execute([
        $data['type'],
        $data['skill_name'],
        $skill_id
    ]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'insert') {
        $user_id = htmlspecialchars($_POST['user_id'] ?? '');
        $data = [
            'type' => htmlspecialchars($_POST['type'] ?? ''),
            'skill_name' => htmlspecialchars($_POST['skill_name'] ?? '')
        ];

        try {
            $pdo->beginTransaction();
            insertSkill($pdo, $user_id, $data);
            $pdo->commit();
            echo "Skill inserted for user ID: " . $user_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de l'insertion de la compétence : " . $e->getMessage());
        }} elseif ($action === 'delete') {
            $skill_id = htmlspecialchars($_POST['skill_id'] ?? '');
    
            try {
                $pdo->beginTransaction();
                deleteSkill($pdo, $skill_id);
                $pdo->commit();
                echo "Skill deleted with ID: " . $skill_id;
            } catch (Exception $e) {
                $pdo->rollBack();
                die("Erreur lors de la suppression de la compétence : " . $e->getMessage());
            }
        } elseif ($action === 'update') {
            $skill_id = htmlspecialchars($_POST['skill_id'] ?? '');
            $data = [
                'type' => htmlspecialchars($_POST['type'] ?? ''),
                'skill_name' => htmlspecialchars($_POST['skill_name'] ?? '')
            ];
            try {
                $pdo->beginTransaction();
                updateSkill($pdo, $skill_id, $data);
                $pdo->commit();
                echo "Skill updated with ID: " . $skill_id;
            } catch (Exception $e) {
                $pdo->rollBack();
                die("Erreur lors de la modification de la compétence : " . $e->getMessage());
            }
        } else {
            die("Action non reconnue.");
        }
    } else {
        die("Méthode de requête non supportée.");
    }
    ?>