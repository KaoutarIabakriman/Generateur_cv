<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet">
    <title>Langues</title>
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
        ?>
        <form id="cvForm" action="../../BD/Languages.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="insert">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

            <div class="step" id="step-3">
                
             
                <div class="mt-6">
                    <label class="block text-sm font-medium text-cyan-700">Langues</label>
                    <button type="button" class="bg-cyan-500 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-cyan-600" onclick="addLanguageField()">
                        <i class="fas fa-plus"></i> Ajouter une langue
                    </button>
                </div>
                <div id="languageContainer" class="mt-4 space-y-4"></div>

               
                <div class="mt-6 flex justify-between">
                    <a href="../Compet/compet.php" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg">Précédent</a>
                    <button type="submit" name="submit" class="prev bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg focus:outline-none">Suivant</button>

                </div>
            </div>
        </form>
    </div>

    <script>
        function addLanguageField() {
            const languageContainer = document.getElementById('languageContainer');
            const languageField = document.createElement('div');
            languageField.className = 'grid grid-cols-1 md:grid-cols-3 gap-4 items-center border p-3 rounded-xl';

            languageField.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-cyan-700 mb-1">Langue</label>
                    <input type="text" name="languages[]" class="w-full px-4 py-2 border-2 border-cyan-500 rounded-xl focus:ring-2 focus:ring-cyan-300 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-cyan-700 mb-1">Niveau</label>
                    <select name="languageLevels[]" class="w-full px-4 py-2 border-2 border-cyan-500 rounded-xl focus:ring-2 focus:ring-cyan-300">
                        <option value="A1">A1 (Débutant)</option>
                        <option value="A2">A2 (Élémentaire)</option>
                        <option value="B1">B1 (Intermédiaire)</option>
                        <option value="B2">B2 (Avancé)</option>
                        <option value="C1">C1 (Courant)</option>
                        <option value="C2">C2 (Bilingue/Natif)</option>
                    </select>
                </div>
                <button type="button" class="bg-blue-500 mt-4 w-fit hover:bg-blue-700 text-white py-2 px-4 rounded-lg flex items-center gap-2" onclick="removeField(this)">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            `;
            languageContainer.appendChild(languageField);
        }

        function removeField(button) {
            button.parentElement.remove();
        }
    </script>
</body>
</html>