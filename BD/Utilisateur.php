<?php
require 'connexion.php';

function insertUser($pdo, $data) {
    $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, adresse, telephone, email, photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['nom'],
        $data['prenom'],
        $data['adresse'],
        $data['telephone'],
        $data['email'],
        $data['photo']
        
    ]);
    return $pdo->lastInsertId();
}

function deleteUser($pdo, $user_id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
}

function updateUser($pdo, $user_id, $data) {
    $stmt = $pdo->prepare("UPDATE users SET nom = ?, prenom = ?, adresse = ?, telephone = ?, email = ?, photo = ? WHERE id = ?");
    $stmt->execute([
        $data['nom'],
        $data['prenom'],
        $data['adresse'],
        $data['telephone'],
        $data['email'],
        $data['photo'],
        $user_id
    ]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'insert') {
        $data = [
            'nom' => htmlspecialchars($_POST['nom'] ?? ''),
            'prenom' => htmlspecialchars($_POST['prenom'] ?? ''),
            'adresse' => htmlspecialchars($_POST['adresse'] ?? ''),
            'telephone' => htmlspecialchars($_POST['telephone'] ?? ''),
            'email' => htmlspecialchars($_POST['email'] ?? ''),
            'photo' => htmlspecialchars($_POST['photo'] ?? '')
        ];

        try {
            $pdo->beginTransaction();
            $user_id = insertUser($pdo, $data);
            $pdo->commit();
            echo "User inserted with ID: " . $user_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de l'insertion : " . $e->getMessage());
        }
    } elseif ($action === 'delete') {
        $user_id = htmlspecialchars($_POST['user_id'] ?? '');

        try {
            $pdo->beginTransaction();
            deleteUser($pdo, $user_id);
            $pdo->commit();
            echo "User deleted with ID: " . $user_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de la suppression : " . $e->getMessage());
        }
    } elseif ($action === 'update') {
        $user_id = htmlspecialchars($_POST['user_id'] ?? '');
        $data = [
            'nom' => htmlspecialchars($_POST['nom'] ?? ''),
            'prenom' => htmlspecialchars($_POST['prenom'] ?? ''),
            'adresse' => htmlspecialchars($_POST['adresse'] ?? ''),
            'telephone' => htmlspecialchars($_POST['telephone'] ?? ''),
            'email' => htmlspecialchars($_POST['email'] ?? ''),
            'photo' => htmlspecialchars($_POST['photo'] ?? '')
        ];

        try {
            $pdo->beginTransaction();
            updateUser($pdo, $user_id, $data);
            $pdo->commit();
            echo "User updated with ID: " . $user_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de la modification : " . $e->getMessage());
        }
    } else {
        die("Action non reconnue.");
    }
} else {
    die("Méthode de requête non supportée.");
}
?>