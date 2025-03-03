<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet">
    <title>Users</title>
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
<body class="bg-gradient-to-br border border from-cyan-50 to-cyan-100 min-h-screen flex items-center justify-center p-4">
    
<div class="bg-white jcc shadow-2xl rounded-3xl w-full max-w-4xl overflow-hidden">
<?php
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../Authentification/login.php');
            exit();
        }
        ?>
    <form id="cvForm" action="../../BD/Utilisateur.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="insert">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
        <div class="p-12">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-3 text-sm font-medium text-cyan-700">Nom</label>
                    <input type="text" name="nom" placeholder="Doe" class="w-full px-4 py-3 border-2 border-cyan-500 rounded-xl">
                </div>
                <div>
                    <label class="block mb-3 text-sm font-medium text-cyan-700">Prénom</label>
                    <input type="text" name="prenom" placeholder="John" class="w-full px-4 py-3 border-2 border-cyan-500 rounded-xl">
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block mb-3 text-sm font-medium text-cyan-700">Téléphone</label>
                    <input type="text" name="telephone" placeholder="0123456789" class="w-full px-4 py-3 border-2 border-cyan-500 rounded-xl">
                </div>
                <div>
                    <label class="block mb-3 text-sm font-medium text-cyan-700">Email</label>
                    <input type="email" name="email" placeholder="exemple@mail.com" class="w-full px-4 py-3 border-2 border-cyan-500 rounded-xl">
                </div>
            </div>
            <div class="grid md:grid-cols-1 gap-6 mt-6">
                <div>
                    <label class="block mb-3 text-sm font-medium text-cyan-700">Adresse</label>
                    <input type="text" name="adresse" placeholder="Votre adresse" class="w-full px-4 py-3 border-2 border-cyan-500 rounded-xl">
                </div>
            </div>
            <div class="grid md:grid-cols-1 gap-6 mt-6">
                <div>
                    <label class="block mb-3 text-sm font-medium text-cyan-700" for="photo">Photo (upload)</label>
                    <div class="relative">
                        <input type="file" name="photo" accept="image/*" class="w-full px-4 py-3 border-2 border-cyan-500 rounded-xl bg-transparent focus:outline-none focus:ring-2 focus:ring-cyan-500 pl-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-500 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
            <div class="mt-6 flex justify-between">
                        <a href="../Projects/projects.php" class="next bg-cyan-500 text-white px-6 py-3 rounded-lg hover:bg-cyan-600 focus:outline-none">Suivant</a>
                    </div>
            </div>
        </div>
    </form>
</div>
</body>
</html>