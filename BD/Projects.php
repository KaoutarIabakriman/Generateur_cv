<?php
session_start();
require_once 'connexion.php'; // Inclure votre fichier de connexion à la base de données

// Vérifiez que l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Vérification si l'action est 'insert'
    if ($_POST['action'] == 'insert') {
        // Insertion des projets dans la base de données
        if (isset($_POST['projects'])) {
            // Vérifiez si les tableaux de projets, dates et types sont définis
            $projects = $_POST['projects'];
            $dates = isset($_POST['dates']) ? $_POST['dates'] : [];
            $types = isset($_POST['types']) ? $_POST['types'] : [];

            // Insérer chaque projet dans la base de données
            for ($i = 0; $i < count($projects); $i++) {
                $projectName = $projects[$i];
                $projectDate = isset($dates[$i]) ? $dates[$i] : '';
                $projectType = isset($types[$i]) ? $types[$i] : '';

                // Préparer et exécuter la requête d'insertion
                $sql = "INSERT INTO projects (user_id, nom, date_projet, type) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                if ($stmt->execute([$user_id, $projectName, $projectDate, $projectType])) {
                    echo "Le projet a été ajouté avec succès.";
                } else {
                    echo "Erreur lors de l'ajout du projet.";
                }
            }
        }

        // Rediriger vers la page suivante (Stage)
        header('Location: ../IHM/StageForm/stage.php');
        exit();
    }
}
?>
