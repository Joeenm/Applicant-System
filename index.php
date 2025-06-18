<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Postulantes | Bienvenida</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 1s ease-in-out',
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-6xl w-full mx-auto animate-fade-in">
        <!-- Header -->
        <header class="text-center mb-12">
            <div class="flex justify-center mb-6 animate-float">
                <div class="bg-white p-4 rounded-full shadow-lg">
                    <i class="fas fa-user-graduate text-5xl text-indigo-600"></i>
                </div>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Sistema de <span class="text-indigo-600">Postulantes</span></h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Gestión eficiente de procesos de selección y administración de candidatos</p>
        </header>

        <!-- Cards Section -->
        <div class="grid md:grid-cols-2 gap-8 mb-12">
            <!-- Postulante Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                <div class="p-2 bg-indigo-100">
                    <i class="fas fa-user-tie text-4xl text-indigo-600 mx-auto block text-center py-4"></i>
                </div>
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-3">Área de Postulantes</h2>
                    <p class="text-gray-600 mb-6">Registra tu postulación para participar en nuestros procesos de selección.</p>
                    <a href="/Applicant-System/views/user/postulation.php" 
                       class="inline-flex items-center justify-center w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105">
                        Registrar Postulación
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Admin Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                <div class="p-2 bg-emerald-100">
                    <i class="fas fa-lock text-4xl text-emerald-600 mx-auto block text-center py-4"></i>
                </div>
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-3">Panel Administrativo</h2>
                    <p class="text-gray-600 mb-6">Acceso exclusivo para administradores del sistema.</p>
                    <a href="/Applicant-System/views/admin/login.php" 
                       class="inline-flex items-center justify-center w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105">
                        Iniciar Sesión
                        <i class="fas fa-sign-in-alt ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-center pt-8 border-t border-gray-200">
            <div class="flex justify-center space-x-6 mb-4">
                <a href="#" class="text-gray-500 hover:text-indigo-600 transition-colors">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="text-gray-500 hover:text-indigo-600 transition-colors">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="text-gray-500 hover:text-indigo-600 transition-colors">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
            <p class="text-gray-500">
                © <?php echo date('Y'); ?> Sistema de Postulantes. Todos los derechos reservados.
            </p>
            <p class="text-gray-400 text-sm mt-2">
                <a href="#" class="hover:underline">Términos y Condiciones</a> | 
                <a href="#" class="hover:underline">Política de Privacidad</a>
            </p>
        </footer>
    </div>
</body>
</html>