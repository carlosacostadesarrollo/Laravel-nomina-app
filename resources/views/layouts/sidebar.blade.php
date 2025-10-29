<nav class="fixed top-0 left-0 w-64 h-full bg-white shadow-xl p-4 z-20 overflow-y-auto" x-data>
    
    <div class="flex items-center space-x-2 mb-8 border-b pb-4">
        {{-- MODIFICAR AQUÍ el nombre de la empresa --}}
        <span class="text-2xl font-extrabold text-indigo-700">Nómina-RRHH Arbitros de Santander</span> 
    </div>

    <ul class="space-y-1">
        
        <li>
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <i class="fa-solid fa-house w-5 h-5 text-lg"></i>
                <span class="ml-3">Dashboard</span>
            </x-nav-link>
        </li>

        <li class="pt-4 pb-1 text-xs font-semibold uppercase text-gray-500">Gestión Laboral</li>
        
        <li>
            <x-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">
                <i class="fa-solid fa-users w-5 h-5 text-lg"></i>
                <span class="ml-3">Empleados</span>
            </x-nav-link>
        </li>
        <li>
            <x-nav-link :href="route('contracts.index')" :active="request()->routeIs('contracts.*')">
                <i class="fa-solid fa-file-signature w-5 h-5 text-lg"></i>
                <span class="ml-3">Contratos</span>
            </x-nav-link>
        </li>

        <li class="pt-4 pb-1 text-xs font-semibold uppercase text-gray-500">Nómina</li>
        
        <li>
            <x-nav-link :href="route('nomina.index')" :active="request()->routeIs('nomina.*')">
                <i class="fa-solid fa-money-check-dollar w-5 h-5 text-lg"></i>
                <span class="ml-3">Procesar Nómina</span>
            </x-nav-link>
        </li>
        <li>
            <x-nav-link :href="route('novedades.index')" :active="request()->routeIs('novedades.*')">
                <i class="fa-solid fa-list-check w-5 h-5 text-lg"></i>
                <span class="ml-3">Registro de Novedades</span>
            </x-nav-link>
        </li>
        
        @php
            // Determina si alguna ruta de configuración está activa para abrir el submenú por defecto
            $isConfigActive = request()->routeIs('configuracion.*') || 
                              request()->routeIs('job_titles.*') || 
                              request()->routeIs('sindicatos.*') || 
                              request()->routeIs('embargos.*') || 
                              request()->routeIs('dependientes.*');
        @endphp

        <li x-data="{ open: @json($isConfigActive) }">
            <button @click="open = !open" 
                    class="flex items-center justify-between w-full p-3 rounded-lg transition duration-150 ease-in-out text-gray-600 hover:bg-gray-100 hover:text-gray-900">
                <div class="flex items-center">
                    <i class="fa-solid fa-screwdriver-wrench w-5 h-5 text-lg"></i>
                    <span class="ml-3 font-medium">Datos Maestros</span>
                </div>
                <i class="fa-solid" :class="{'fa-chevron-up': open, 'fa-chevron-down': !open}"></i>
            </button>
            
            <ul x-show="open" x-transition.duration.200ms class="ml-4 mt-1 space-y-1 border-l-2 border-gray-200 pl-3">
                
                <li>
                    <x-nav-link :href="route('configuracion.create')" :active="request()->routeIs('configuracion.*')">
                        <i class="fa-solid fa-gear w-5 h-5 text-sm"></i>
                        <span class="ml-2">Configuración General</span>
                    </x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="route('job_titles.index')" :active="request()->routeIs('job_titles.*')">
                        <i class="fa-solid fa-briefcase w-5 h-5 text-sm"></i>
                        <span class="ml-2">Cargos y Salarios</span>
                    </x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="route('sindicatos.index')" :active="request()->routeIs('sindicatos.*')">
                        <i class="fa-solid fa-handshake w-5 h-5 text-sm"></i>
                        <span class="ml-2">Sindicatos</span>
                    </x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="route('embargos.index')" :active="request()->routeIs('embargos.*')">
                        <i class="fa-solid fa-building-columns w-5 h-5 text-sm"></i>
                        <span class="ml-2">Empresas para Embargos</span>
                    </x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="route('dependientes.index')" :active="request()->routeIs('dependientes.*')">
                        <i class="fa-solid fa-child-reaching w-5 h-5 text-sm"></i>
                        <span class="ml-2">Registrar Dependientes</span>
                    </x-nav-link>
                </li>
            </ul>
        </li>
        
    </ul>
</nav>