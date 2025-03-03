<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet">
    <title>Interests</title>
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
        <form id="cvForm" action="../../BD/Interests.php" method="POST" enctype="multipart/form-data">
        <?php
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../Authentification/login.php');
            exit();
        }
        ?>
        <input type="hidden" name="action" value="insert">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

        <div class="step" id="step-5">
                    <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
                        <label class="block text-lg font-medium text-gray-700">Centres d'Intérêt</label>
                        <div id="interestContainer" class="space-y-4"></div>
                        <button type="button" class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 shadow-md mt-4" onclick="addInterestField()">
                            <i class="fas fa-plus"></i> Ajouter un centre d'intérêt
                        </button>
                        <div class="mt-6 flex justify-between">
                            <a href="../Education/education.php" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg">Précédent</a>
                            <button type="submit" class="bg-cyan-500 text-white px-6 py-3 rounded-lg hover:bg-cyan-600 focus:outline-none">Générer CV</button>
                        </div>
                    </div>
                </div>
        </form>
    </div>

    <script>
        function addInterestField() {
            const interestContainer = document.getElementById('interestContainer');
            const interestField = document.createElement('div');
            interestField.className = 'bg-gray-100 p-4 rounded-lg shadow-sm';
            interestField.innerHTML = `
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-cyan-700">Centre d'intérêt</label>
                        <input type="text" name="interests[]" class="w-full mt-1 px-3 py-2 border-2 border-cyan-500 rounded-lg" required>
                    </div>
                </div>
                <button type="button" class="mt-3 bg-blue-500 hover:bg-blue-800 text-white px-4 py-2 rounded-lg" onclick="removeField(this)">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            `;
            interestContainer.appendChild(interestField);
        }

        function removeField(button) {
            button.parentElement.remove();
        }

        function removeSkillField(button) {
            button.parentElement.parentElement.remove();
        }

        
        document.addEventListener("DOMContentLoaded", function() {
            showStep(currentStep);
        });
    </script>
</body>
</html>
