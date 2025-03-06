<?php
require_once '../../BD/Connexion.php';


$stmt = $pdo->prepare("
    SELECT u.id, u.nom, u.prenom, u.email, 
           (SELECT COUNT(*) FROM education WHERE user_id = u.id) as has_education,
           (SELECT COUNT(*) FROM skills WHERE user_id = u.id) as has_skills,
           (SELECT COUNT(*) FROM projects WHERE user_id = u.id) as has_projects,
           (SELECT COUNT(*) FROM internships WHERE user_id = u.id) as has_internships,
           (SELECT COUNT(*) FROM languages WHERE user_id = u.id) as has_languages,
           (SELECT COUNT(*) FROM interests WHERE user_id = u.id) as has_interests
    FROM users u
    ORDER BY u.nom, u.prenom
");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cyan: {
                            50: '#ecfeff',
                            100: '#cffafe',
                            200: '#a5f3fc',
                            300: '#67e8f9',
                            400: '#22d3ee',
                            500: '#06b6d4',
                            600: '#0891b2',
                            700: '#0e7490',
                            800: '#155e75',
                            900: '#164e63',
                        },
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-gradient-to-br from-cyan-50 to-cyan-100 min-h-screen p-4">
    <div class="container mx-auto">
    <?php if (isset($_GET['success']) && $_GET['success'] == 'user_deleted'): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg shadow">
                <p>L'utilisateur et toutes ses données ont été supprimés avec succès.</p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg shadow">
                <p>Une erreur s'est produite<?php echo isset($_GET['message']) ? ': ' . htmlspecialchars($_GET['message']) : '.'; ?></p>
            </div>
        <?php endif; ?>
        <div class="bg-white shadow-2xl rounded-3xl w-full p-6 mb-6">
            <h1 class="text-3xl font-bold text-cyan-700 mb-6">Admin Dashboard</h1>
                    <div class="flex justify-between items-center mb-8">
                <p class="text-gray-600">Gérer les CV des utilisateurs</p>
                <a href="add_user.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-user-plus mr-2"></i> Ajouter un utilisateur
             </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 bg-cyan-100 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">ID</th>
                            <th class="py-3 px-4 bg-cyan-100 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">Nom</th>
                            <th class="py-3 px-4 bg-cyan-100 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">Prénom</th>
                            <th class="py-3 px-4 bg-cyan-100 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">Email</th>
                            <th class="py-3 px-4 bg-cyan-100 text-left text-xs font-medium text-cyan-700 uppercase tracking-wider">Status CV</th>
                            <th class="py-3 px-4 bg-cyan-100 text-center text-xs font-medium text-cyan-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($users as $user): ?>
                            <?php 
                               
                                $hasCV = $user['has_education'] > 0 || 
                                         $user['has_skills'] > 0 || 
                                         $user['has_projects'] > 0 || 
                                         $user['has_internships'] > 0 ||
                                         $user['has_languages'] > 0 ||
                                         $user['has_interests'] > 0;
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4 text-sm text-gray-500"><?php echo $user['id']; ?></td>
                                <td class="py-4 px-4 text-sm text-gray-900"><?php echo htmlspecialchars($user['nom']); ?></td>
                                <td class="py-4 px-4 text-sm text-gray-900"><?php echo htmlspecialchars($user['prenom']); ?></td>
                                <td class="py-4 px-4 text-sm text-gray-900"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="py-4 px-4 text-sm">
                                    <?php if ($hasCV): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            CV disponible
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Pas de CV
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-4 text-sm text-center">
                                <?php if ($hasCV): ?>
                                        <a href="edit_cv.php?user_id=<?php echo $user['id']; ?>" 
                                           class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded mr-2" 
                                           title="Modifier le CV">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <button onclick="confirmDelete(<?php echo $user['id']; ?>)" 
                                                class="text-white bg-red-500 hover:bg-red-600 px-3 py-1 rounded" 
                                                title="Supprimer le CV">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    <?php else: ?>
                                        <a href="edit_cv.php?user_id=<?php echo $user['id']; ?>&create=1" 
                                           class="text-white bg-green-500 hover:bg-green-600 px-3 py-1 rounded" 
                                           title="Créer un CV">
                                            <i class="fas fa-plus-circle"></i> Créer CV
                                        </a>
                                    <?php endif; ?>
                                        
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(userId) {
            if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur et son CV? Cette action est irréversible.")) {
                window.location.href = "delete_cv.php?user_id=" + userId;
            }
        }
    </script>
</body>
</html>