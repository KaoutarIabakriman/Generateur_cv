<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet">
    <title>Compétences</title>
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
        <form id="cvForm" action="../../BD/Skills.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="insert">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

            <div class="step" id="step-3">
                
                <!-- Type de compétence -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-cyan-700">Type de compétence</label>
                    <select id="competencesType" class="w-full px-4 py-3 border-2 border-cyan-500 rounded-xl focus:ring-2 focus:ring-cyan-300" onchange="addSkillField()">
                        <option value="">Sélectionner</option>
                        <option value="technical">Compétences Techniques</option>
                        <option value="behavioral">Compétences Comportementales</option>
                    </select>
                </div>
                <div id="skillsContainer" class="mt-4 space-y-4"></div>

                <!-- Boutons navigation -->
                <div class="mt-6 flex justify-between">
                        <a href="../StageForm/stage.php"    class="prev bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg focus:outline-none">Précédent</a>
                        <a href="../Lang/lang.php" class="next bg-cyan-500 text-white px-6 py-3 rounded-lg hover:bg-cyan-600 focus:outline-none">Suivant</a>
                    </div>
            </div>
        </form>
    </div>

    <script>
        function addSkillField() {
            const skillType = document.getElementById('competencesType').value;
            const skillContainer = document.getElementById('skillsContainer');

            if (skillType) {
                const skillField = document.createElement('div');
                skillField.className = 'flex items-center gap-4 border p-3 rounded-xl';

                let labelText = skillType === 'technical' ? 'Compétences Techniques (séparées par des virgules)' 
                                                          : 'Compétences Comportementales (séparées par des virgules)';

                skillField.innerHTML = `
                    <div class="w-full">
                        <label class="text-sm font-medium text-cyan-700">${labelText}</label>
                        <input type="text" name="${skillType}Skills[]" class="w-full px-4 py-2 border-2 border-cyan-500 rounded-xl focus:ring-2 focus:ring-cyan-300 outline-none mt-1" required>
                    </div>
                    <button type="button" class="bg-blue-500 mt-4 hover:bg-blue-700 text-white py-2 px-4 rounded-lg flex items-center gap-2" onclick="removeField(this)">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                `;
                skillContainer.appendChild(skillField);
            }
        }

        function removeField(button) {
            button.parentElement.remove();
        }
    </script>
</body>
</html>