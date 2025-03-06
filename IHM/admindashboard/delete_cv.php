<?php
require_once '../../BD/Connexion.php';


if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    header('Location: admindashboard.php?error=invalid_user');
    exit();
}

$user_id = $_GET['user_id'];

try {
    
    $pdo->beginTransaction();
    
    
    $tables = ['education', 'skills', 'projects', 'internships', 'languages', 'interests'];
    
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE user_id = ?");
        $stmt->execute([$user_id]);
    }
    
   
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    
   
    $pdo->commit();
    
   
    header('Location: admindashboard.php?success=user_deleted');
    exit();
} catch (PDOException $e) {
    
    $pdo->rollBack();
    header('Location: admindashboard.php?error=delete_failed&message=' . urlencode($e->getMessage()));
    exit();
}
?>