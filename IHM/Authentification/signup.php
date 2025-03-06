<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }
        .nav-link {
            position: relative;
            overflow: hidden;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: currentColor;
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease-out;
        }
        .nav-link:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }
        .mobile-menu {
            transition: transform 0.3s ease-out, opacity 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gray-100">
<header class="bg-white dark:bg-gray-800 shadow-lg">
        <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
        <span class="text-xl font-bold bg-gradient-to-r from-cyan-600 to-cyan-300 bg-clip-text text-transparent">
                        StudentSpace
                    </span>
            <div class="hidden md:flex space-x-6">
                <a href="#" class="nav-link text-gray-700 dark:text-gray-200 hover:text-cyan-500 dark:hover:text-white transition-colors duration-300">Home</a>
                <a href="#" class="nav-link text-gray-700 dark:text-gray-200 hover:text-cyan-500 dark:hover:text-white transition-colors duration-300">About</a>
                <a href="#" class="nav-link text-gray-700 dark:text-gray-200 hover:text-cyan-500 dark:hover:text-white transition-colors duration-300">Services</a>
                <a href="#" class="nav-link text-gray-700 dark:text-gray-200 hover:text-cyan-500 dark:hover:text-white transition-colors duration-300">Contact</a>
            </div>
            <div class="hidden md:flex items-center space-x-4">
                
                <a href="login.php" class="bg-cyan-400 text-white px-4 py-2 rounded-lg hover:bg-cyan-500 transition-colors duration-300">LogIn</a>
            </div>
            
</nav>
</div>

        </div>
    </header>  
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center text-2xl  bg-gradient-to-r from-cyan-600 to-cyan-300 bg-clip-text text-transparent hover:text-cyan-500">Sign Up</h2>
            <form action="../../Traitement/signup_process.php" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="w-full px-3 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="prenom" class="block text-gray-700">Prenom</label>
                    <input type="text" name="prenom" id="prenom" class="w-full px-3 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="w-full px-3 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" name="password" id="password"  class="w-full  px-3 py-2 border rounded-lg" required>
                </div>
                <button type="submit" class="w-full bg-cyan-400 hover:bg-cyan-500 text-white py-2 rounded-lg">Sign Up</button>
            </form>
        </div>
    </div>
    <footer class="mt-20 xl:mt-32 mx-auto w-full relative text-center bg-cyan-500 text-white">
    <div class="px-6 py-8 md:py-14 xl:pt-20 xl:pb-12">
    <h3 class="font-semibold text-xl text-white text-center leading-snug py-4 transition-colors duration-300 hover:text-gray-300">
    Créez un CV Professionnel Facilement en quelques minutes. Remplissez le formulaire et téléchargez votre CV en PDF instantanément.</h3>


        <a class="mt-8 xl:mt-12 px-12 py-5 text-lg font-medium leading-tight inline-block bg-white text-cyan-500 rounded-full shadow-xl border border-transparent hover:bg-cyan-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-sky-999 focus:ring-sky-500"
            href="../../index.php">Get
            started</a>
        <div class="mt-14 xl:mt-20">
            <nav class="flex flex-wrap justify-center text-lg font-medium">
                <div class="px-5 py-2 hover:text-gray-300 hover:underline  transition-colors duration-300"><a href="#">Home</a></div>
                <div class="px-5 py-2 hover:text-gray-300 hover:underline  transition-colors duration-300"><a href="#">About</a></div>
                <div class="px-5 py-2 hover:text-gray-300 hover:underline  transition-colors duration-300"><a href="#">Services</a></div>
                <div class="px-5 py-2 hover:text-gray-300 hover:underline  transition-colors duration-300"><a href="#">Contact</a></div>
            </nav>
            <p class="mt-7 text-base">© 2025 PhpMates</p>
        </div>
    </div>
</footer>

</body>
</html>