<?php
require_once '../../BD/Connexion.php';


if (!isset($_POST['user_id']) || !is_numeric($_POST['user_id'])) {
    header('Location: admindashboard.php?error=invalid_user');
    exit();
}

$user_id = $_POST['user_id'];

try {
    
    $pdo->beginTransaction();
    
    
    $stmt = $pdo->prepare("UPDATE users SET 
        nom = ?, 
        prenom = ?, 
        
        telephone = ?
        WHERE id = ?");
    
    $stmt->execute([
        $_POST['nom'] ?? '',
        $_POST['prenom'] ?? '',
        
        $_POST['telephone'] ?? '',
        $user_id
    ]);
    
    
    if (isset($_POST['education']) && is_array($_POST['education'])) {
        foreach ($_POST['education'] as $edu) {
            if (isset($edu['id']) && is_numeric($edu['id'])) {
                $stmt = $pdo->prepare("UPDATE education SET 
                    institution = ?, 
                    diplome = ?, 
                    annee_obtention = ?
                    WHERE id = ? AND user_id = ?");
                
                $stmt->execute([
                    $edu['institution'] ?? '',
                    $edu['diplome'] ?? '',
                    $edu['annee_obtention'] ?? '',
                    $edu['id'],
                    $user_id
                ]);
            }
        }
    }
    
    
    if (isset($_POST['education_new']) && is_array($_POST['education_new'])) {
        foreach ($_POST['education_new'] as $edu) {
            if (!empty($edu['institution']) || !empty($edu['diplome']) || !empty($edu['annee_obtention'])) {
                $stmt = $pdo->prepare("INSERT INTO education (user_id, institution, diplome, annee_obtention) 
                    VALUES (?, ?, ?, ?)");
                
                $stmt->execute([
                    $user_id,
                    $edu['institution'] ?? '',
                    $edu['diplome'] ?? '',
                    $edu['annee_obtention'] ?? ''
                ]);
            }
        }
    }
    
   
    if (isset($_POST['skills']) && is_array($_POST['skills'])) {
      
        if (isset($_POST['skills']['technical']) && is_array($_POST['skills']['technical'])) {
            foreach ($_POST['skills']['technical'] as $skill) {
                if (isset($skill['id']) && is_numeric($skill['id'])) {
                    $stmt = $pdo->prepare("UPDATE skills SET skill_name = ? WHERE id = ? AND user_id = ?");
                    $stmt->execute([$skill['name'] ?? '', $skill['id'], $user_id]);
                }
            }
        }
        
       
        if (isset($_POST['skills']['behavioral']) && is_array($_POST['skills']['behavioral'])) {
            foreach ($_POST['skills']['behavioral'] as $skill) {
                if (isset($skill['id']) && is_numeric($skill['id'])) {
                    $stmt = $pdo->prepare("UPDATE skills SET skill_name = ? WHERE id = ? AND user_id = ?");
                    $stmt->execute([$skill['name'] ?? '', $skill['id'], $user_id]);
                }
            }
        }
    }
    
   
    if (isset($_POST['skills_new']) && is_array($_POST['skills_new'])) {
        
        if (isset($_POST['skills_new']['technical']) && is_array($_POST['skills_new']['technical'])) {
            foreach ($_POST['skills_new']['technical'] as $skill) {
                if (!empty($skill)) {
                    $stmt = $pdo->prepare("INSERT INTO skills (user_id, skill_name, type) VALUES (?, ?, 'technical')");
                    $stmt->execute([$user_id, $skill]);
                }
            }
        }
        
     
        if (isset($_POST['skills_new']['behavioral']) && is_array($_POST['skills_new']['behavioral'])) {
            foreach ($_POST['skills_new']['behavioral'] as $skill) {
                if (!empty($skill)) {
                    $stmt = $pdo->prepare("INSERT INTO skills (user_id, skill_name, type) VALUES (?, ?, 'behavioral')");
                    $stmt->execute([$user_id, $skill]);
                }
            }
        }
    }

    if (isset($_POST['languages']) && is_array($_POST['languages'])) {
        foreach ($_POST['languages'] as $lang) {
            if (isset($lang['id']) && is_numeric($lang['id'])) {
                $stmt = $pdo->prepare("UPDATE languages SET 
                    langue = ?, 
                    niveau = ? 
                    WHERE id = ? AND user_id = ?");
                
                $stmt->execute([
                    $lang['nom'] ?? '', 
                    $lang['niveau'] ?? '',
                    $lang['id'],
                    $user_id
                ]);
            }
        }
    }

    if (isset($_POST['languages_new']) && is_array($_POST['languages_new'])) {
        foreach ($_POST['languages_new'] as $lang) {
            if (!empty($lang['nom']) || !empty($lang['niveau'])) {
                $stmt = $pdo->prepare("INSERT INTO languages (user_id, langue, niveau) 
                    VALUES (?, ?, ?)");
                
                $stmt->execute([
                    $user_id,
                    $lang['nom'] ?? '',  
                    $lang['niveau'] ?? 'A1'
                ]);
            }
        }
    }

    if (isset($_POST['projects']) && is_array($_POST['projects'])) {
        foreach ($_POST['projects'] as $project) {
            if (isset($project['id']) && is_numeric($project['id'])) {
                $stmt = $pdo->prepare("UPDATE projects SET 
                    nom = ?, 
                    date_projet = ?, 
                    type = ?
                    WHERE id = ? AND user_id = ?");
                $date_projet = !empty($project['date']) ? date('Y-m-d', strtotime($project['date'] . "-01-01")) : null;
                $stmt->execute([
                    $project['nom'] ?? '',
                    $date_projet,
                    $project['type'] ?? '',
                    $project['id'],
                    $user_id
                ]);
            }
        }
    }

    if (isset($_POST['projects_new']) && is_array($_POST['projects_new'])) {
        foreach ($_POST['projects_new'] as $project) {
            if (!empty($project['nom']) || !empty($project['date']) || !empty($project['type'])) {
                $stmt = $pdo->prepare("INSERT INTO projects (user_id, nom, date_projet, type) 
                    VALUES (?, ?, ?, ?)");
                
                $date_projet = !empty($project['date']) ? date('Y-m-d', strtotime($project['date'] . "-01-01")) : null;
                $stmt->execute([
                    $user_id,
                    $project['nom'] ?? '',
                    $date_projet,
                    $project['type'] ?? ''
                ]);
            }
        }
    }

    if (isset($_POST['internships']) && is_array($_POST['internships'])) {
        foreach ($_POST['internships'] as $internship) {
            if (isset($internship['id']) && is_numeric($internship['id'])) {
                $stmt = $pdo->prepare("UPDATE internships SET 
                    nom = ?, 
                    periode = ?, 
                    lieu = ?
                    WHERE id = ? AND user_id = ?");
                
                $stmt->execute([
                    $internship['nom'] ?? '',
                    $internship['periode'] ?? '',
                    $internship['lieu'] ?? '',
                    $internship['id'],
                    $user_id
                ]);
            }
        }
    }

    if (isset($_POST['internships_new']) && is_array($_POST['internships_new'])) {
        foreach ($_POST['internships_new'] as $internship) {
            if (!empty($internship['nom']) || !empty($internship['periode']) || !empty($internship['lieu'])) {
                $stmt = $pdo->prepare("INSERT INTO internships (user_id, nom, periode, lieu) 
                    VALUES (?, ?, ?, ?)");
                
                $stmt->execute([
                    $user_id,
                    $internship['nom'] ?? '',
                    $internship['periode'] ?? '',
                    $internship['lieu'] ?? ''
                ]);
            }
        }
    }

    if (isset($_POST['interests']) && is_array($_POST['interests'])) {
        foreach ($_POST['interests'] as $interest) {
            if (isset($interest['id']) && is_numeric($interest['id'])) {
                $stmt = $pdo->prepare("UPDATE interests SET 
                    interet = ?
                    WHERE id = ? AND user_id = ?");
                
                $stmt->execute([
                    $interest['interet'] ?? '',
                    $interest['id'],
                    $user_id
                ]);
            }
        }
    }

    if (isset($_POST['interests_new']) && is_array($_POST['interests_new'])) {
        foreach ($_POST['interests_new'] as $interest) {
            if (!empty($interest['interet'])) {
                $stmt = $pdo->prepare("INSERT INTO interests (user_id, interet) 
                    VALUES (?, ?)");
                
                $stmt->execute([
                    $user_id,
                    $interest['interet'] ?? ''
                ]);
            }
        }
    }
    
    
    $pdo->commit();
    
  
    header("Location: edit_cv.php?user_id=$user_id&success=true");
    exit();
    
} catch (PDOException $e) {
    
    $pdo->rollBack();
    header("Location: edit_cv.php?user_id=$user_id&error=true&message=" . urlencode($e->getMessage()));
    exit();

   
}
?>