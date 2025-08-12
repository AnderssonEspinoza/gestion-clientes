<!-- resources/views/layouts/navigation.blade.php -->
<nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- LOGO -->
            <div class="flex-shrink-0 flex items-center text-xl font-bold text-gray-800 dark:text-white">
                 AKANA TECHNOLOGY
            </div>

            <!-- Menú Desktop -->
            <div class="hidden md:flex space-x-8 items-center">
                <a href="{{ route('clientes.index') }}" class="text-gray-700 dark:text-gray-200 hover:text-red-500">
                    Clientes Disponibles
                </a>
                <a href="{{ route('clientes.mis') }}" class="text-gray-700 dark:text-gray-200 hover:text-blue-500">
                    Mis Clientes
                </a>
                <a href="#" class="text-gray-700 dark:text-gray-200 hover:text-blue-500">
                    Reportes
                </a>
            </div>

            <!-- USUARIO + LOGOUT (DESKTOP) -->
            <div class="hidden md:flex items-center space-x-4">
                <span class="text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded">
                        Salir
                    </button>
                </form>
            </div>

            <!-- BOTÓN HAMBURGUESA (SOLO MÓVIL) -->
            <div class="flex items-center md:hidden">
                <button id="menu-toggle" class="text-gray-700 dark:text-gray-200 focus:outline-none">
                    ☰
                </button>
            </div>
        </div>
    </div>
     

    <!-- MENÚ MÓVIL -->
    <div id="mobile-menu" class="hidden md:hidden bg-gray-100 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('clientes.index') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800">
            Clientes Disponibles
        </a>
        <a href="{{ route('clientes.mis') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800">
            Mis Clientes
        </a>
        <a href="#" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-800">
            Reportes
        </a>
        <div class="border-t border-gray-200 dark:border-gray-700"></div>
        <div class="px-4 py-2 flex items-center justify-between">
            <span class="text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded">
                    Salir
                </button>
            </form>
        </div>
    </div>
    

    <script>
        document.getElementById('menu-toggle').addEventListener('click', function () {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</nav>
