<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet">
    <title>Projets</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
<body class="bg-gradient-to-br from-cyan-50 to-cyan-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white shadow-2xl rounded-3xl w-full max-w-4xl p-6">
    <?php
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../Authentification/login.php');
            exit();
        }

       
        require_once '../../BD/Connexion.php';
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare("SELECT nom, prenom, email FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            header('Location: ../Authentification/login.php');
            exit();
        }
        ?>
        <form id="cvForm" action="../../BD/Projects.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="insert">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

    <div class="step" id="step-2">
        <div class="grid md:grid-cols-1 gap-6">
            <div>
                <label class="block mb-3 text-sm font-medium text-cyan-700">Projets</label>
                <button type="button" class="bg-cyan-500 hover:bg-cyan-600 text-white px-6 py-3 rounded-lg flex items-center gap-2 mt-2" onclick="addProjectField()">
                    <i class="fas fa-plus"></i> Ajouter un projet
                </button>
            </div>
        </div>

      
        <div id="projectContainer" class="mt-4"></div>

        <div class="mt-6 flex justify-between">
            <a href="../utilisateur/users.php" class="prev bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg focus:outline-none">Précédent</a>
                <button type="submit" class="next bg-cyan-500 text-white px-6 py-3 rounded-lg hover:bg-cyan-600 focus:outline-none">Suivant</button>
            </div>
        </div>
        </div>
    </div>
    
</form>

<script>
    function addProjectField() {
        const projectContainer = document.getElementById('projectContainer');
        const projectField = document.createElement('div');
        projectField.className = 'form-group border p-4 mb-4 rounded-lg shadow-sm bg-gray-100';
        projectField.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="font-medium text-cyan-700 block mb-2">Nom du projet</label>
                    <input type="text" name="projects[]" class="w-full px-4 py-2 border-2 border-cyan-500 rounded-xl focus:ring-2 focus:ring-cyan-300 outline-none" required>
                </div>
                <div>
                    <label class="font-medium text-cyan-700 block mb-2">Date du projet</label>
                    <input type="date" name="dates[]" class="w-full px-4 py-2 border-2 border-cyan-500 rounded-xl focus:ring-2 focus:ring-cyan-300 outline-none" required>
                </div>
                <div>
                    <label class="font-medium text-cyan-700 block mb-2">Type de projet</label>
                    <select name="types[]" class="w-full px-4 py-2 border-2 border-cyan-500 rounded-xl focus:ring-2 focus:ring-cyan-300 outline-none" required>
                        <option value="academic">Académique</option>
                        <option value="personal">Personnel</option>
                        <option value="volunteering">Bénévolat</option>
                    </select>
                </div>
            </div>
            <button type="button" class="bg-blue-500 hover:bg-blue-800 text-white py-2 px-4 rounded-lg flex items-center gap-2 mt-2" onclick="removeField(this)">
                <i class="fas fa-trash"></i> Supprimer
            </button>
        `;
        projectContainer.appendChild(projectField);
    }

    function removeField(button) {
        button.parentElement.remove();
    }
</script>
  
</body>
</html>
