{{-- Le voy a pasar el parametro para que admin.blade me muestre la miga de pan --}}
<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
    ],
]">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-[#f7f3ba] rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                {{-- Imagen de perfil --}}
                <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                    alt="{{ Auth::user()->name }}" />

                <div class="ml-4 flex-1">
                    <h2 class="text-lg ">
                        Bienvenido, {{ auth()->user()->name }}
                    </h2>
                    {{-- cerrar sesion --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button class="text-sm hover:text-blue-400">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-[#f7f3ba] rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold flex items-center justify-center">
                NOMBRE DE LA EMPRESA
            </h2>
        </div>
    </div>

</x-admin-layout>
