<?php
require 'connexion.php';

// Fonction pour insérer les compétences dans la base de données
function insertSkill($pdo, $user_id, $skill_name, $skill_type) {
    $stmt = $pdo->prepare("INSERT INTO skills (user_id, type, skill_name) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $skill_type, $skill_name]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';  // Récupérer l'action depuis le formulaire

    if ($action === 'insert') {
        // Sanitize et valider l'ID de l'utilisateur
        $user_id = $_POST['user_id'] ?? '';

        if (empty($user_id)) {
            die("ID utilisateur requis.");
        }

        // Vérifier si les compétences techniques et comportementales sont envoyées
        if (empty($_POST['technicalSkills']) && empty($_POST['behavioralSkills'])) {
            die("Aucune compétence n'a été fournie.");
        }

        // Insérer les compétences
        try {
            // Démarrer une transaction
            $pdo->beginTransaction();

            // Insérer les compétences techniques
            if (!empty($_POST['technicalSkills'])) {
                foreach ($_POST['technicalSkills'] as $skill_name) {
                    insertSkill($pdo, $user_id, $skill_name, 'technical');
                }
            }

            // Insérer les compétences comportementales
            if (!empty($_POST['behavioralSkills'])) {
                foreach ($_POST['behavioralSkills'] as $skill_name) {
                    insertSkill($pdo, $user_id, $skill_name, 'behavioral');
                }
            }

            // Valider la transaction
            $pdo->commit();

            // Rediriger après l'insertion réussie
            header("Location: ../IHM/Lang/lang.php");
            exit();

        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $pdo->rollBack();
            die("Erreur lors de l'insertion des compétences : " . $e->getMessage());
        }
    } else {
        die("Action non reconnue.");
    }
} else {
    die("Méthode de requête non prise en charge.");
}
?>
