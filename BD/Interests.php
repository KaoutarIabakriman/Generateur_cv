<?php
require 'connexion.php';

function insertInterest($pdo, $user_id, $interet) {
    // Prépare la requête pour insérer l'intérêt dans la table
    $stmt = $pdo->prepare("INSERT INTO interests (user_id, interet) VALUES (?, ?)");
    $stmt->execute([
        $user_id,
        $interet
    ]);
}

function deleteInterest($pdo, $interest_id) {
    // Prépare la requête pour supprimer l'intérêt de la table
    $stmt = $pdo->prepare("DELETE FROM interests WHERE id = ?");
    $stmt->execute([$interest_id]);
}

function updateInterest($pdo, $interest_id, $data) {
    // Prépare la requête pour mettre à jour l'intérêt dans la table
    $stmt = $pdo->prepare("UPDATE interests SET interet = ? WHERE id = ?");
    $stmt->execute([
        $data['interet'],
        $interest_id
    ]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation de l'action et récupération des données
    $action = $_POST['action'] ?? '';
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;

    if ($action === 'insert' && $user_id) {
        // Récupération des centres d'intérêt
        if (isset($_POST['interests']) && is_array($_POST['interests'])) {
            $interests = $_POST['interests'];
            
            // Validation des centres d'intérêt
            foreach ($interests as $interet) {
                $interet = trim(htmlspecialchars($interet));
                if ($interet === '') {
                    die("Un des centres d'intérêt est vide.");
                }

                // Insertion dans la base de données
                try {
                    $pdo->beginTransaction();
                    insertInterest($pdo, $user_id, $interet);
                    $pdo->commit();
                    header('Location: ../index.php');
                } catch (Exception $e) {
                    $pdo->rollBack();
                    die("Erreur lors de l'insertion de l'intérêt : " . $e->getMessage());
                }
            }
        } else {
            die("Aucun centre d'intérêt n'a été sélectionné.");
        }
    } elseif ($action === 'delete' && isset($_POST['interest_id'])) {
        // Validation de l'ID de l'intérêt
        $interest_id = intval($_POST['interest_id']);

        if ($interest_id <= 0) {
            die("ID de l'intérêt invalide.");
        }

        try {
            $pdo->beginTransaction();
            deleteInterest($pdo, $interest_id);
            $pdo->commit();
            echo "L'intérêt avec l'ID " . $interest_id . " a été supprimé.";
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de la suppression de l'intérêt : " . $e->getMessage());
        }
    } elseif ($action === 'update' && isset($_POST['interest_id']) && isset($_POST['interet'])) {
        // Validation des données de mise à jour
        $interest_id = intval($_POST['interest_id']);
        $interet = trim(htmlspecialchars($_POST['interet']));

        if ($interest_id <= 0 || $interet === '') {
            die("ID de l'intérêt ou nouveau centre d'intérêt invalide.");
        }

        $data = [
            'interet' => $interet
        ];

        try {
            $pdo->beginTransaction();
            updateInterest($pdo, $interest_id, $data);
            $pdo->commit();
            echo "L'intérêt avec l'ID " . $interest_id . " a été mis à jour.";
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de la mise à jour de l'intérêt : " . $e->getMessage());
        }
    } else {
        die("Action non reconnue ou paramètres manquants.");
    }
} else {
    die("Méthode de requête non supportée.");
}
?>
