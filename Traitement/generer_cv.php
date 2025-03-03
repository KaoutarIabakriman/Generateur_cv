<?php
require 'vendor/autoload.php'; 
require_once 'connexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $photoPath = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $filename = uniqid() . '_' . basename($_FILES['photo']['name']);
        $targetPath = $uploadDir . $filename;
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['photo']['tmp_name']);
        
        if (in_array($fileType, $allowedTypes)) {
            move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath);
            $photoPath = $targetPath;
        }
    }

    
    $data = [
        'personal' => [
            'nom' => htmlspecialchars($_POST['nom'] ?? ''),
            'prenom' => htmlspecialchars($_POST['prenom'] ?? ''),
            'telephone' => htmlspecialchars($_POST['telephone'] ?? ''),
            'email' => htmlspecialchars($_POST['email'] ?? ''),
            'adresse' => htmlspecialchars($_POST['adresse'] ?? ''),
            'photo' => $photoPath
        ],
        'projets' => [
            'noms' => $_POST['projectNames'] ?? [],
            'dates' => $_POST['experienceDates'] ?? [],
            'types' => $_POST['projectTypes'] ?? []
        ],
        'stages' => [
            'noms' => $_POST['internshipNames'] ?? [],
            'periodes' => $_POST['internshipPeriod'] ?? [],
            'lieux' => $_POST['internshipLocation'] ?? []
        ],
        'competences' => [
            'techniques' => $_POST['technicalSkills'] ?? [],
            'comportementales' => $_POST['behavioralSkills'] ?? []
        ],
        'langues' => [
            'noms' => $_POST['languages'] ?? [],
            'niveaux' => $_POST['languageLevels'] ?? []
        ],
        'formations' => [
            'etablissements' => $_POST['educationInstitutions'] ?? [],
            'diplomes' => $_POST['educationDegrees'] ?? [],
            'annees' => $_POST['educationYears'] ?? []
        ],
        'interets' => $_POST['interests'] ?? []
    ];
    
    try {
        $pdo->beginTransaction();

     
        $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, adresse, telephone, email, photo) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['personal']['nom'],
            $data['personal']['prenom'],
            $data['personal']['adresse'],
            $data['personal']['telephone'],
            $data['personal']['email'],
            $data['personal']['photo']
        ]);
        $user_id = $pdo->lastInsertId();

        // Insertion des projets
        foreach ($data['projets']['noms'] as $index => $nom) {
            if(!empty($nom)) {
                $stmt = $pdo->prepare("INSERT INTO projects (user_id, nom, date_projet, type)
                                      VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $user_id,
                    $nom,
                    $data['projets']['dates'][$index] ?? null,
                    $data['projets']['types'][$index] ?? 'personal'
                ]);
            }
        }

        foreach ($data['stages']['noms'] as $index => $nom) {
            if(!empty($nom)) {
                $stmt = $pdo->prepare("INSERT INTO internships (user_id, nom, periode, lieu)
                                      VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $user_id,
                    $nom,
                    $data['stages']['periodes'][$index] ?? null,
                    $data['stages']['lieux'][$index] ?? null
                ]);
            }
        }

        foreach ($data['competences']['techniques'] as $competence) {
            if(!empty($competence)) {
                $stmt = $pdo->prepare("INSERT INTO skills (user_id, type, skill_name)
                                      VALUES (?, 'technical', ?)");
                $stmt->execute([$user_id, $competence]);
            }
        }

  
        foreach ($data['competences']['comportementales'] as $competence) {
            if(!empty($competence)) {
                $stmt = $pdo->prepare("INSERT INTO skills (user_id, type, skill_name)
                                      VALUES (?, 'behavioral', ?)");
                $stmt->execute([$user_id, $competence]);
            }
        }

        
        foreach ($data['langues']['noms'] as $index => $langue) {
            if(!empty($langue)) {
                $stmt = $pdo->prepare("INSERT INTO languages (user_id, langue, niveau)
                                      VALUES (?, ?, ?)");
                $stmt->execute([
                    $user_id,
                    $langue,
                    $data['langues']['niveaux'][$index] ?? 'A1'
                ]);
            }
        }

    
        foreach ($data['formations']['etablissements'] as $index => $etablissement) {
            if(!empty($etablissement)) {
                $stmt = $pdo->prepare("INSERT INTO education (user_id, institution, diplome, annee_obtention)
                                      VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $user_id,
                    $etablissement,
                    $data['formations']['diplomes'][$index] ?? null,
                    $data['formations']['annees'][$index] ?? null
                ]);
            }
        }

        foreach ($data['interets'] as $interet) {
            if(!empty($interet)) {
                $stmt = $pdo->prepare("INSERT INTO interests (user_id, interet)
                                      VALUES (?, ?)");
                $stmt->execute([$user_id, $interet]);
            }
        }

        $pdo->commit();

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erreur lors de l'enregistrement : " . $e->getMessage());
    }

    $html = '<!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CV Généré</title>
        <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            cyan: {
                                50: \'#ecfeff\',
                                100: \'#cffafe\',
                                200: \'#a5f3fc\',
                                300: \'#67e8f9\',
                                400: \'#22d3ee\',
                                500: \'#06b6d4\',
                                600: \'#0891b2\',
                                700: \'#0e7490\',
                                800: \'#155e75\',
                                900: \'#164e63\',
                            },
                        },
                    },
                },
            }
        </script>
    </head>
    <body class="bg-gradient-to-br from-cyan-50 to-cyan-100 min-h-screen flex items-center justify-center p-4">
        <div class="bg-white shadow-2xl rounded-3xl w-full max-w-4xl overflow-hidden p-8">';

   
    $html .= '<div class="flex flex-col md:flex-row items-center border-b border-cyan-200 pb-6 mb-6">';
    if ($data['personal']['photo']) {
        $html .= '<div class="mb-4 md:mb-0">
                    <img src="'.$data['personal']['photo'].'" class="w-32 h-32 rounded-full object-cover border-4 border-cyan-500">
                  </div>';
    }
    $html .= '<div class="md:ml-6 text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-bold text-cyan-700">'.$data['personal']['prenom'].' '.$data['personal']['nom'].'</h1>
                <p class="text-gray-600 mt-2">'.$data['personal']['adresse'].'</p>
                <p class="text-gray-600">'.$data['personal']['telephone'].' | '.$data['personal']['email'].'</p>
              </div>
            </div>';

    $sections = [
        'Formations' => function() use ($data) {
            $html = '<div class="space-y-4">';
            foreach ($data['formations']['etablissements'] as $index => $etablissement) {
                if(isset($etablissement) && $etablissement != '') {
                    $html .= '<div class="p-4 bg-cyan-50 rounded-xl">
                                <h3 class="font-bold text-cyan-700">'.$etablissement.'</h3>
                                <p class="text-gray-700">'.$data['formations']['diplomes'][$index].' - '.$data['formations']['annees'][$index].'</p>
                              </div>';
                }
            }
            $html .= '</div>';
            return $html;
        },
        'Projets' => function() use ($data) {
            $html = '<div class="space-y-4">';
            foreach ($data['projets']['noms'] as $index => $projet) {
                if(isset($projet) && $projet != '') {
                    $html .= '<div class="p-4 bg-cyan-50 rounded-xl">
                                <h3 class="font-bold text-cyan-700">'.$projet.'</h3>
                                <p class="text-gray-700">'.$data['projets']['dates'][$index].' ';
               
                    $typeLabel = [
                        'academic' => 'Académique',
                        'personal' => 'Personnel',
                        'volunteering' => 'Bénévolat'
                    ];
                    
                    $type = $data['projets']['types'][$index] ?? '';
                    $html .= isset($typeLabel[$type]) ? '('.$typeLabel[$type].')' : '';
                    
                    $html .= '</p>
                              </div>';
                }
            }
            $html .= '</div>';
            return $html;
        },
        'Stages' => function() use ($data) {
            $html = '<div class="space-y-4">';
            foreach ($data['stages']['noms'] as $index => $stage) {
                if(isset($stage) && $stage != '') {
                    $html .= '<div class="p-4 bg-cyan-50 rounded-xl">
                                <h3 class="font-bold text-cyan-700">'.$stage.'</h3>
                                <p class="text-gray-700">'.$data['stages']['periodes'][$index].' - '.$data['stages']['lieux'][$index].'</p>
                              </div>';
                }
            }
            $html .= '</div>';
            return $html;
        },
        'Compétences' => function() use ($data) {
            $html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';
            
            $html .= '<div class="p-4 bg-cyan-50 rounded-xl">';
            $html .= '<h4 class="font-bold text-cyan-700 mb-2">Techniques</h4>';
            $html .= '<ul class="space-y-1">';
            foreach ($data['competences']['techniques'] as $competence) {
                if(isset($competence) && $competence != '') {
                    $html .= '<li class="flex items-center">
                                <svg class="w-4 h-4 text-cyan-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                '.htmlspecialchars($competence).'
                              </li>';
                }
            }
            $html .= '</ul></div>';
            
    
            $html .= '<div class="p-4 bg-cyan-50 rounded-xl">';
            $html .= '<h4 class="font-bold text-cyan-700 mb-2">Comportementales</h4>';
            $html .= '<ul class="space-y-1">';
            foreach ($data['competences']['comportementales'] as $competence) {
                if(isset($competence) && $competence != '') {
                    $html .= '<li class="flex items-center">
                                <svg class="w-4 h-4 text-cyan-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                '.htmlspecialchars($competence).'
                              </li>';
                }
            }
            $html .= '</ul></div>';
            
            $html .= '</div>';
            return $html;
        },
        'Langues' => function() use ($data) {
            $html = '<div class="p-4 bg-cyan-50 rounded-xl">';
            $html .= '<ul class="space-y-2">';
     
            $levelColors = [
                'A1' => 'bg-red-200',
                'A2' => 'bg-red-300',
                'B1' => 'bg-yellow-200',
                'B2' => 'bg-yellow-300',
                'C1' => 'bg-green-200',
                'C2' => 'bg-green-300'
            ];
            
            foreach ($data['langues']['noms'] as $index => $langue) {
                if(isset($langue) && $langue != '') {
                    $niveau = $data['langues']['niveaux'][$index] ?? 'A1';
                    $levelColor = $levelColors[$niveau] ?? 'bg-gray-200';
                    
                    $html .= '<li class="flex justify-between items-center">
                                <span class="font-medium">'.$langue.'</span>
                                <span class="px-3 py-1 rounded-full text-sm '.$levelColor.'">'.$niveau.'</span>
                              </li>';
                }
            }
            $html .= '</ul></div>';
            return $html;
        },
        'Centres d\'intérêt' => function() use ($data) {
            $html = '<div class="p-4 bg-cyan-50 rounded-xl">';
            $html .= '<ul class="grid grid-cols-1 md:grid-cols-3 gap-2">';
            foreach ($data['interets'] as $interet) {
                if(isset($interet) && $interet != '') {
                    $html .= '<li class="flex items-center">
                                <svg class="w-4 h-4 text-cyan-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                '.htmlspecialchars($interet).'
                              </li>';
                }
            }
            $html .= '</ul></div>';
            return $html;
        }
    ];

    foreach ($sections as $title => $contentGenerator) {
        $content = $contentGenerator();
        if(!empty($content) && $content != '<div class="space-y-4"></div>' && $content != '<div class="p-4 bg-cyan-50 rounded-xl"><ul class="space-y-2"></ul></div>' && $content != '<div class="p-4 bg-cyan-50 rounded-xl"><ul class="grid grid-cols-1 md:grid-cols-3 gap-2"></ul></div>') {
            $html .= '<div class="mb-8">
                        <h2 class="text-2xl font-bold text-cyan-700 mb-4 flex items-center">
                            <span class="w-8 h-8 rounded-full bg-cyan-500 text-white flex items-center justify-center mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            '.$title.'
                        </h2>
                        '.$content.'
                      </div>';
        }
    }


    $html .= '<div class="mt-8 text-center">
                <button onclick="window.print()" class="bg-cyan-500 hover:bg-cyan-600 text-white px-6 py-3 rounded-xl shadow-md transition duration-300 transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Imprimer mon CV
                </button>
            </div>';

    $html .= '</div>
    
    <style>
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .bg-gradient-to-br {
                background: white !important;
            }
            button {
                display: none !important;
            }
            .shadow-2xl {
                box-shadow: none !important;
            }
            .rounded-3xl {
                border-radius: 0 !important;
            }
        }
    </style>
    
    </body></html>';


    header('Content-Type: text/html');
    echo $html;
    exit;
} else {
    header('Location: formulaire_cv.php');
    exit;
}
?>