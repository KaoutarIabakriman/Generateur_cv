<?php
require 'connexion.php';

function insertEducation($pdo, $user_id, $data) {
    $stmt = $pdo->prepare("INSERT INTO education (user_id, institution, diplome, annee_obtention) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $user_id,
        $data['institution'],
        $data['diplome'],
        $data['annee_obtention']
    ]);
}

function deleteEducation($pdo, $education_id) {
    $stmt = $pdo->prepare("DELETE FROM education WHERE id = ?");
    $stmt->execute([$education_id]);
}

function updateEducation($pdo, $education_id, $data) {
    $stmt = $pdo->prepare("UPDATE education SET institution = ?, diplome = ?, annee_obtention = ? WHERE id = ?");
    $stmt->execute([
        $data['institution'],
        $data['diplome'],
        $data['annee_obtention'],
        $education_id
    ]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'insert') {
        $user_id = htmlspecialchars($_POST['user_id'] ?? '');
        $institutions = $_POST['educationInstitutions'] ?? [];
        $degrees = $_POST['educationDegrees'] ?? [];
        $years = $_POST['educationYears'] ?? [];

        try {
            $pdo->beginTransaction();

            
            foreach ($institutions as $index => $institution) {
                $data = [
                    'institution' => htmlspecialchars($institution),
                    'diplome' => htmlspecialchars($degrees[$index] ?? ''),
                    'annee_obtention' => htmlspecialchars($years[$index] ?? '')
                ];
                insertEducation($pdo, $user_id, $data);
            }

            $pdo->commit();
           
            header('Location: ../IHM/CentreIntr/interests.php');
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de l'insertion de la formation : " . $e->getMessage());
        }
    } elseif ($action === 'delete') {
        $education_id = htmlspecialchars($_POST['education_id'] ?? '');

        try {
            $pdo->beginTransaction();
            deleteEducation($pdo, $education_id);
            $pdo->commit();
           
            header('Location:../IHM/CentreIntr/interests.php');
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de la suppression de la formation : " . $e->getMessage());
        }
    } elseif ($action === 'update') {
        $education_id = htmlspecialchars($_POST['education_id'] ?? '');
        $data = [
            'institution' => htmlspecialchars($_POST['institution'] ?? ''),
            'diplome' => htmlspecialchars($_POST['diplome'] ?? ''),
            'annee_obtention' => htmlspecialchars($_POST['annee_obtention'] ?? '')
        ];

        try {
            $pdo->beginTransaction();
            updateEducation($pdo, $education_id, $data);
            $pdo->commit();
            
            
            header('Location: ../IHM/CentreIntr/interests.php');
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de la modification de la formation : " . $e->getMessage());
        }
    } else {
        die("Action non reconnue.");
    }
} else {
    die("Méthode de requête non supportée.");
}
?>
