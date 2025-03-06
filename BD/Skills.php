<?php
require 'connexion.php';


function insertSkill($pdo, $user_id, $skill_name, $skill_type) {
    $stmt = $pdo->prepare("INSERT INTO skills (user_id, type, skill_name) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $skill_type, $skill_name]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';  

    if ($action === 'insert') {
      
        $user_id = $_POST['user_id'] ?? '';

        if (empty($user_id)) {
            die("ID utilisateur requis.");
        }

       
        if (empty($_POST['technicalSkills']) && empty($_POST['behavioralSkills'])) {
            die("Aucune compétence n'a été fournie.");
        }

      
        try {
           
            $pdo->beginTransaction();

        
            if (!empty($_POST['technicalSkills'])) {
                foreach ($_POST['technicalSkills'] as $skill_name) {
                    insertSkill($pdo, $user_id, $skill_name, 'technical');
                }
            }

            
            if (!empty($_POST['behavioralSkills'])) {
                foreach ($_POST['behavioralSkills'] as $skill_name) {
                    insertSkill($pdo, $user_id, $skill_name, 'behavioral');
                }
            }

            
            $pdo->commit();

            header("Location: ../IHM/Lang/lang.php");
            exit();

        } catch (Exception $e) {
           
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
