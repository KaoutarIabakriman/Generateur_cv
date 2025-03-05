<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de CV</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-gray-50 text-gray-900">

    <header class="w-full bg-white shadow-md hover:shadow-lg transition-shadow duration-300 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">
                <!-- Logo avec animation -->
                <div class="flex items-center space-x-3 group">
                    <div class="w-7 h-7 bg-gradient-to-tr from-cyan-400 to-cyan-300 rounded-lg transform group-hover:rotate-12 transition-transform duration-300"></div>
                    <span class="text-xl font-bold bg-gradient-to-r from-cyan-600 to-cyan-300 bg-clip-text text-transparent">
                        StudentSpace
                    </span>
                </div>
    
                <!-- Navigation Links stylisés -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="../Partie1/index.php" class="relative text-gray-600 hover:text-gray-900 px-2 py-1 transition-all duration-200
                                      before:absolute before:-bottom-1 before:left-0 before:w-0 before:h-0.5 before:bg-gradient-to-r from-cyan-400 to-cyan-300 before:transition-all before:duration-300
                                      hover:before:w-full">
                        Accueil
                    </a>
                    <a href="../Partie2/index.html" class="relative text-gray-600 hover:text-gray-900 px-2 py-1 transition-all duration-200
                                      before:absolute before:-bottom-1 before:left-0 before:w-0 before:h-0.5 before:bg-gradient-to-r from-cyan-400 to-cyan-300 before:transition-all before:duration-300
                                      hover:before:w-full font-medium text-cyan-700">
                        Votre CV
                    </a>
                    <a href="#" class="relative text-gray-600 hover:text-gray-900 px-2 py-1 transition-all duration-200
                                      before:absolute before:-bottom-1 before:left-0 before:w-0 before:h-0.5 before:bg-gradient-to-r from-cyan-400 to-cyan-300 before:transition-all before:duration-300
                                      hover:before:w-full">
                        À propos
                    </a>
                </nav>
    
                <!-- Bouton amélioré -->
                <div class="flex items-center space-x-4">
                    <a href="Traitement/check_login.php" class="inline-flex items-center justify-center 
           text-white bg-gradient-to-r from-cyan-400 to-cyan-200 
           hover:from-cyan-500 hover:to-cyan-400 
           transition-all duration-300 px-6 py-3 ml-72
           rounded-lg text-lg shadow-md transform hover:scale-105">
                        <span>Generer votre Cv</span>
                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <section class="text-gray-700 body-font">
        <div class="container mx-auto flex px-5 py-24 md:flex-row flex-col items-center">
            <div class="lg:flex-grow md:w-1/2 lg:pr-24 md:pr-16 flex flex-col md:items-start md:text-left items-center text-center space-y-6">
                <h1 class="text-center mx-auto max-w-4xl font-extrabold 
                           text-3xl sm:text-4xl md:text-5xl 
                           leading-[1.2] md:leading-[1.15]
                           tracking-tight
                           bg-gradient-to-r from-cyan-800 via-cyan-400 to-cyan-300 
                           bg-clip-text text-transparent
                           drop-shadow-lg
                           transition-all duration-300
                           hover:scale-[1.01]">
                    <span class="inline-block">
                        Créez un CV Professionnel Facilement
                        <br class="hidden lg:inline-block"> en quelques minutes
                    </span>
                </h1>
                <p class="text-cyan-600 text-lg md:text-xl font-medium leading-relaxed mt-4 ml-24 mb-6">
                    Remplissez le formulaire et téléchargez votre CV en PDF instantanément.
                </p>
                <div class="flex justify-center">
                    <a href="Traitement/check_login.php" class="inline-flex items-center justify-center 
           text-white bg-gradient-to-r from-cyan-400 to-cyan-300 
           hover:from-cyan-500 hover:to-cyan-400 
           transition-all duration-300 px-6 py-3 ml-72
           rounded-lg text-lg shadow-md transform hover:scale-105">
                        📄 Générer votre CV
                    </a>
                </div>
                
            </div>
            
            <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6">
                <img class="object-cover object-center rounded" alt="hero" src="./IHM/images/cv.png" />  
            </div>
        </div>
    </section>

    <section class="container mx-auto py-16 px-6">
        <h2 class="text-4xl font-bold text-center text-gray-800">Pourquoi utiliser notre service ?</h2>
        <div class="grid md:grid-cols-3 gap-8 mt-10">
            <div class="p-6 bg-gradient-to-r from-cyan-300 to-cyan-500 rounded-lg shadow-md text-center transition-shadow duration-300 hover:shadow-lg">
                <h3 class="text-2xl font-semibold text-white">Facile à utiliser</h3>
                <p class="text-gray-200 mt-3">Créez votre CV en quelques clics grâce à notre interface intuitive.</p>
            </div>
            <div class="p-6 bg-gradient-to-r from-cyan-300 to-cyan-500 rounded-lg shadow-md text-center transition-shadow duration-300 hover:shadow-lg">
                <h3 class="text-2xl font-semibold text-white">Templates modernes</h3>
                <p class="text-gray-200 mt-3">Choisissez parmi plusieurs modèles de CV professionnels.</p>
            </div>
            <div class="p-6 bg-gradient-to-r from-cyan-300 to-cyan-500 rounded-lg shadow-md text-center transition-shadow duration-300 hover:shadow-lg">
                <h3 class="text-2xl font-semibold text-white">Téléchargement rapide</h3>
                <p class="text-gray-200 mt-3">Téléchargez votre CV en format PDF en un instant.</p>
            </div>
        </div>
    </section>

</body>
</html>