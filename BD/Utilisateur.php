<?php
session_start();
require_once 'connexion.php'; // Inclure votre fichier de connexion à la base de données

// Vérifiez que l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Vérification si l'action est 'insert'
    if ($_POST['action'] == 'insert') {
        // Récupérer les données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $telephone = $_POST['telephone'];
        $email = $_POST['email'];
        $adresse = $_POST['adresse'];
        
        // Traitement de l'upload de la photo
        $photo = null;
        if (!empty($_FILES['photo']['name'])) {
            $target_dir = "uploads/";
            $photo_name = time() . "_" . basename($_FILES["photo"]["name"]);
            $target_file = $target_dir . $photo_name;
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

            if (in_array($_FILES["photo"]["type"], $allowed_types) && move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo = $target_file;
            } else {
                die("Erreur lors du téléchargement de l'image.");
            }
        }

        // Insertion dans la base de données
        $sql = "UPDATE users SET nom = ?, prenom = ?, telephone = ?, email = ?, adresse = ?, photo = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $telephone, $email, $adresse, $photo, $user_id]);

        // Rediriger vers la page suivante (Projets)
        header('Location: ../IHM/Projects/projects.php');
        exit();
    }
}
?>
