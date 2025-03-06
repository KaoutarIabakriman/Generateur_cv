<?php
require_once '../../BD/Connexion.php';

if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    header('Location: admindashboard.php?error=invalid_user');
    exit();
}

$user_id = $_GET['user_id'];


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: admindashboard.php?error=user_not_found');
    exit();
}

function fetchUserData($pdo, $query, $user_id) {
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


$education = fetchUserData($pdo, "SELECT * FROM education WHERE user_id = ?", $user_id);
$skills = fetchUserData($pdo, "SELECT * FROM skills WHERE user_id = ?", $user_id);
$projects = fetchUserData($pdo, "SELECT * FROM projects WHERE user_id = ?", $user_id);
$internships = fetchUserData($pdo, "SELECT * FROM internships WHERE user_id = ?", $user_id);
$languages = fetchUserData($pdo, "SELECT id, user_id, langue AS nom, niveau FROM languages WHERE user_id = ?", $user_id);
$interests = fetchUserData($pdo, "SELECT * FROM interests WHERE user_id = ?", $user_id);


$technicalSkills = fetchUserData($pdo, "SELECT id, user_id, type, skill_name AS nom FROM skills WHERE user_id = ? AND type = 'technical'", $user_id);

$behavioralSkills = fetchUserData($pdo, "SELECT id, user_id, type, skill_name AS nom FROM skills WHERE user_id = ? AND type = 'behavioral'", $user_id);
$isCreating = isset($_GET['create']) && $_GET['create'] == 1;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier CV - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .skill-item, .interest-item { transition: all 0.3s ease; }
        .remove-item:hover { transform: scale(1.1); }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-4">
    <div class="container mx-auto max-w-7xl">
        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg shadow">
                <p>✔️ Les modifications ont été enregistrées avec succès.</p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg shadow">
                <p>❌ Erreur : <?php echo isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Une erreur s\'est produite'; ?></p>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow-xl rounded-lg p-8 mb-6">
            <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">
        <i class="fas <?php echo $isCreating ? 'fa-plus-circle' : 'fa-user-edit'; ?> mr-2"></i>
        <?php if ($isCreating): ?>
            Créer un CV pour <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?>
        <?php else: ?>
            Modifier le CV de <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?>
        <?php endif; ?>
    </h1>
                <a href="admindashboard.php" class="btn bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>

            <form action="update_cv.php" method="POST" class="space-y-8">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                <?php if ($isCreating): ?>
                    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-lg">
                        <p><i class="fas fa-info-circle mr-2"></i> Vous êtes en train de créer un CV pour cet utilisateur. Complétez les sections ci-dessous.</p>
                    </div>
                <?php endif; ?>
              
                <section class="bg-gray-50 p-6 rounded-xl">
                    <h2 class="text-2xl font-bold text-blue-500 mb-6">
                        <i class="fas fa-user-circle mr-2"></i>Informations Personnelles
                    </h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                                <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" 
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                           <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" 
               class="w-full px-4 py-2 border rounded-lg bg-gray-100 cursor-not-allowed" readonly>
        <p class="text-xs text-gray-500 mt-1">L'email ne peut pas être modifié car il sert d'identifiant unique</p>
    </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                                <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" 
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                                <input type="text" name="telephone" value="<?php echo htmlspecialchars($user['telephone'] ?? ''); ?>" 
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                            <input type="text" name="adresse" value="<?php echo htmlspecialchars($user['adresse'] ?? ''); ?>" 
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </section>

               
                <section class="bg-gray-50 p-6 rounded-xl">
                    <h2 class="text-2xl font-bold text-blue-500 mb-6">
                        <i class="fas fa-graduation-cap mr-2"></i>Formation
                    </h2>
                    <div id="educationContainer" class="space-y-4">
                        <?php foreach ($education as $index => $edu): ?>
                            <div class="education-item bg-white p-4 rounded-lg shadow-sm">
                                <input type="hidden" name="education[<?php echo $index; ?>][id]" value="<?php echo $edu['id']; ?>">
                                <div class="grid md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Établissement</label>
                                        <input type="text" name="education[<?php echo $index; ?>][institution]" 
                                               value="<?php echo htmlspecialchars($edu['institution']); ?>" 
                                               class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Diplôme</label>
                                        <input type="text" name="education[<?php echo $index; ?>][diplome]" 
                                               value="<?php echo htmlspecialchars($edu['diplome']); ?>" 
                                               class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Année</label>
                                        <input type="text" name="education[<?php echo $index; ?>][annee_obtention]" 
                                               value="<?php echo htmlspecialchars($edu['annee_obtention']); ?>" 
                                               class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                </div>
                                <button type="button" class="mt-3 text-red-500 hover:text-red-700 text-sm remove-item">
                                    <i class="fas fa-trash mr-1"></i>Supprimer
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="addEducation" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Ajouter une formation
                    </button>
                </section>

              
                <section class="bg-gray-50 p-6 rounded-xl">
                    <h2 class="text-2xl font-bold text-blue-500 mb-6">
                        <i class="fas fa-tools mr-2"></i>Compétences
                    </h2>
                    
                    
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-blue-600 mb-4">Techniques</h3>
                        <div id="technicalSkillsContainer" class="space-y-2">
                            <?php foreach ($technicalSkills as $index => $skill): ?>
                                <div class="skill-item flex items-center gap-2">
                                    <input type="hidden" name="skills[technical][<?php echo $index; ?>][id]" value="<?php echo $skill['id']; ?>">
                                    <input type="text" name="skills[technical][<?php echo $index; ?>][name]" 
                                           value="<?php echo htmlspecialchars($skill['nom']); ?>" 
                                           class="flex-1 px-3 py-2 border rounded-md">
                                    <button type="button" class="text-red-500 hover:text-red-700 remove-skill">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="addTechnicalSkill" class="mt-3 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md">
                            <i class="fas fa-plus mr-1"></i>Ajouter
                        </button>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold text-blue-600 mb-4">Comportementales</h3>
                        <div id="behavioralSkillsContainer" class="space-y-2">
                            <?php foreach ($behavioralSkills as $index => $skill): ?>
                                <div class="skill-item flex items-center gap-2">
                                    <input type="hidden" name="skills[behavioral][<?php echo $index; ?>][id]" value="<?php echo $skill['id']; ?>">
                                    <input type="text" name="skills[behavioral][<?php echo $index; ?>][name]" 
                                           value="<?php echo htmlspecialchars($skill['nom']); ?>" 
                                           class="flex-1 px-3 py-2 border rounded-md">
                                    <button type="button" class="text-red-500 hover:text-red-700 remove-skill">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="addBehavioralSkill" class="mt-3 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md">
                            <i class="fas fa-plus mr-1"></i>Ajouter
                        </button>
                    </div>
                </section>

               
                <section class="bg-gray-50 p-6 rounded-xl">
                    <h2 class="text-2xl font-bold text-blue-500 mb-6">
                        <i class="fas fa-project-diagram mr-2"></i>Projets
                    </h2>
                    <div id="projectsContainer" class="space-y-4">
                        <?php foreach ($projects as $index => $project): ?>
                            <div class="project-item bg-white p-4 rounded-lg shadow-sm">
                                <input type="hidden" name="projects[<?php echo $index; ?>][id]" value="<?php echo $project['id']; ?>">
                                <div class="grid md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom du projet</label>
                                        <input type="text" name="projects[<?php echo $index; ?>][nom]" 
                                               value="<?php echo htmlspecialchars($project['nom'] ?? ''); ?>" 
                                               class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                                        <input type="text" name="projects[<?php echo $index; ?>][date]" 
                                               value="<?php echo htmlspecialchars($project['date'] ?? ''); ?>" 
                                               class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                        <input type="text" name="projects[<?php echo $index; ?>][type]" 
                                               value="<?php echo htmlspecialchars($project['type'] ?? ''); ?>" 
                                               class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                </div>
                                <button type="button" class="mt-3 text-red-500 hover:text-red-700 text-sm remove-item">
                                    <i class="fas fa-trash mr-1"></i>Supprimer
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="addProject" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Ajouter un projet
                    </button>
                </section>

            
                <section class="bg-gray-50 p-6 rounded-xl">
                    <h2 class="text-2xl font-bold text-blue-500 mb-6">
                        <i class="fas fa-briefcase mr-2"></i>Stages
                    </h2>
                    <div id="internshipsContainer" class="space-y-4">
                        <?php foreach ($internships as $index => $internship): ?>
                            <div class="internship-item bg-white p-4 rounded-lg shadow-sm">
                                <input type="hidden" name="internships[<?php echo $index; ?>][id]" value="<?php echo $internship['id']; ?>">
                                <div class="grid md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                                        <input type="text" name="internships[<?php echo $index; ?>][nom]" 
                                               value="<?php echo htmlspecialchars($internship['nom'] ?? ''); ?>" 
                                               class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                                        <input type="text" name="internships[<?php echo $index; ?>][periode]" 
                                               value="<?php echo htmlspecialchars($internship['periode'] ?? ''); ?>" 
                                               class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Lieu</label>
                                        <input type="text" name="internships[<?php echo $index; ?>][lieu]" 
                                               value="<?php echo htmlspecialchars($internship['lieu'] ?? ''); ?>" 
                                               class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                </div>
                                <button type="button" class="mt-3 text-red-500 hover:text-red-700 text-sm remove-item">
                                    <i class="fas fa-trash mr-1"></i>Supprimer
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="addInternship" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Ajouter un stage
                    </button>
                </section>

            
                <section class="bg-gray-50 p-6 rounded-xl">
                    <h2 class="text-2xl font-bold text-blue-500 mb-6">
                        <i class="fas fa-language mr-2"></i>Langues
                    </h2>
                    <div id="languagesContainer" class="space-y-4">
                        <?php foreach ($languages as $index => $language): ?>
                            <div class="language-item bg-white p-4 rounded-lg shadow-sm">
                                <input type="hidden" name="languages[<?php echo $index; ?>][id]" value="<?php echo $language['id']; ?>">
                                <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Langue</label>
                                    <input type="text" name="languages[<?php echo $index; ?>][nom]" 
                                           value="<?php echo htmlspecialchars($language['nom']); ?>" 
                                           class="w-full px-3 py-2 border rounded-md">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                                    <input type="text" name="languages[<?php echo $index; ?>][niveau]" 
                                           value="<?php echo htmlspecialchars($language['niveau']); ?>" 
                                           class="w-full px-3 py-2 border rounded-md">
                                </div>
                            </div>
                            <button type="button" class="mt-3 text-red-500 hover:text-red-700 text-sm remove-item">
                                <i class="fas fa-trash mr-1"></i>Supprimer
                            </button>
                        </div>
                    <?php endforeach; ?>
                    </div>
                    <button type="button" id="addLanguage" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Ajouter une langue
                    </button>
                </section>

           
                <section class="bg-gray-50 p-6 rounded-xl">
                    <h2 class="text-2xl font-bold text-blue-500 mb-6">
                        <i class="fas fa-heart mr-2"></i>Intérêts
                    </h2>
                    <div id="interestsContainer" class="space-y-4">
                        <?php foreach ($interests as $index => $interest): ?>
                            <div class="interest-item bg-white p-4 rounded-lg shadow-sm">
                                <input type="hidden" name="interests[<?php echo $index; ?>][id]" value="<?php echo $interest['id']; ?>">
                                <div class="flex items-center gap-4">
                                    <input type="text" name="interests[<?php echo $index; ?>][interet]" 
                                           value="<?php echo htmlspecialchars($interest['interet']); ?>" 
                                           class="flex-1 px-3 py-2 border rounded-md">
                                    <button type="button" class="text-red-500 hover:text-red-700 text-sm remove-item">
                                        <i class="fas fa-trash mr-1"></i>Supprimer
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="addInterest" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Ajouter un intérêt
                    </button>
                </section>

                <div class="text-center">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-lg text-lg font-semibold transition-all">
                        <i class="fas fa-save mr-2"></i>Enregistrer toutes les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        
        const languageTemplate = {
            class: 'language-item bg-white p-4 rounded-lg shadow-sm',
            html: (index) => `
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Langue</label>
                        <input type="text" name="languages_new[${index}][nom]" class="w-full px-3 py-2 border rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                        <input type="text" name="languages_new[${index}][niveau]" class="w-full px-3 py-2 border rounded-md">
                    </div>
                </div>
                <button type="button" class="mt-3 text-red-500 hover:text-red-700 text-sm remove-item">
                    <i class="fas fa-trash mr-1"></i>Supprimer
                </button>
            `
        };

        const interestTemplate = {
            class: 'interest-item bg-white p-4 rounded-lg shadow-sm',
            html: (index) => `
                <div class="flex items-center gap-4">
                    <input type="text" name="interests_new[${index}]" class="flex-1 px-3 py-2 border rounded-md">
                    <button type="button" class="text-red-500 hover:text-red-700 text-sm remove-item">
                        <i class="fas fa-trash mr-1"></i>Supprimer
                    </button>
                </div>
            `
        };

       
        document.getElementById('addLanguage').addEventListener('click', () => {
            const index = document.getElementById('languagesContainer').children.length;
            createNewElement(languageTemplate, 'languagesContainer', index);
            addRemoveListeners();
        });

        document.getElementById('addInterest').addEventListener('click', () => {
            const index = document.getElementById('interestsContainer').children.length;
            createNewElement(interestTemplate, 'interestsContainer', index);
            addRemoveListeners();
        });

       
        function createNewElement(template, containerId, index) {
            const container = document.getElementById(containerId);
            const div = document.createElement('div');
            div.className = template.class;
            div.innerHTML = template.html(index);
            container.appendChild(div);
        }

        function addRemoveListeners() {
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.target.closest('.education-item, .project-item, .internship-item, .language-item, .interest-item').remove();
                });
            });
        }

        
        addRemoveListeners();
    </script>
</body>
</html>
                                