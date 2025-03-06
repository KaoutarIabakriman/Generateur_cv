<?php
session_start();
require_once 'connexion.php'; 

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    
    if ($_POST['action'] == 'insert') {
        
        if (isset($_POST['projects'])) {
          
            $projects = $_POST['projects'];
            $dates = isset($_POST['dates']) ? $_POST['dates'] : [];
            $types = isset($_POST['types']) ? $_POST['types'] : [];

          
            for ($i = 0; $i < count($projects); $i++) {
                $projectName = $projects[$i];
                $projectDate = isset($dates[$i]) ? $dates[$i] : '';
                $projectType = isset($types[$i]) ? $types[$i] : '';

             
                $sql = "INSERT INTO projects (user_id, nom, date_projet, type) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                if ($stmt->execute([$user_id, $projectName, $projectDate, $projectType])) {
                    echo "Le projet a été ajouté avec succès.";
                } else {
                    echo "Erreur lors de l'ajout du projet.";
                }
            }
        }

        
        header('Location: ../IHM/StageForm/stage.php');
        exit();
    }
}
?>
