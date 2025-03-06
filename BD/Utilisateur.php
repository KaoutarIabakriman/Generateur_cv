<?php
session_start();
require_once 'connexion.php';


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

  
    if ($_POST['action'] == 'insert') {
        
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $telephone = $_POST['telephone'];
        $email = $_POST['email'];
        $adresse = $_POST['adresse'];
        
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

        
        $sql = "UPDATE users SET nom = ?, prenom = ?, telephone = ?, email = ?, adresse = ?, photo = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $telephone, $email, $adresse, $photo, $user_id]);

        
        header('Location: ../IHM/Projects/projects.php');
        exit();
    }
}
?>
