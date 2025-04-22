<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Portadas',
    ],
]">


    {{-- Boton para agregar una nueva portada --}}
    <x-slot name="action">
        <a href="{{ route('admin.covers.create') }}" class="btn btn-indigo">
            Agregar
        </a>
    </x-slot>

    <div class="card bg-gray-900">
        {{-- Lista de portadas --}}
        <ul class="space-y-4" id="covers">
            @foreach ($covers as $cover)
                <li class="bg-gray-600 rounded-lg  shadow-lg overflow-hidden lg:flex text-gray-300 lg:cursor-move"
                    data-id="{{ $cover->id }}">

                    {{-- Imagen de la portada --}}
                    <img class="w-full lg:w-64 aspect-[3/1] object-cover object-center" src="{{ $cover->image }}"
                        alt="">

                    <div class="p-4 lg:flex-1 lg:flex lg:justify-between lg:items-center space-y-3 lg:space-y-0">

                        {{-- Titulo de la portada  y estado --}}
                        <div>
                            <h1 class="font-semibold text-xl">
                                {{ $cover->title }}
                            </h1>
                            {{-- badge para el estado de la portada --}}
                            <p>
                                @if ($cover->is_active)
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                        Activa
                                    </span>
                                @else
                                    <span
                                        class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                        Inactiva
                                    </span>
                                @endif
                            </p>
                        </div>

                        {{-- Fecha de inicio --}}
                        <div>
                            <p class="text-sm font-bold">
                                Fecha de inicio:
                            </p>
                            <p>
                                {{ $cover->start_at->format('d/m/Y') }}
                            </p>
                        </div>

                        {{-- Fecha de finalización --}}
                        <div>
                            <p class="text-sm font-bold">
                                Fecha de finalización:
                            </p>
                            <p>
                                {{ $cover->end_at ? $cover->end_at->format('d/m/Y') : 'No tiene fecha de finalización' }}
                            </p>
                        </div>

                        {{-- Boton para editar --}}
                        <div>
                            <a href="{{ route('admin.covers.edit', $cover) }}" class="btn">
                                Editar
                            </a>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>


    {{-- Script para utilizar el pluggin Sortable --}}
    @push('js')
        {{-- Utilizo este script para ordenar las portadas --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.3/Sortable.min.js"></script>

        <script>
            new Sortable(covers, {
                animation: 150,
                ghostClass: 'bg-blue-500',
                store: {
                    set: (sortable) =>{
                        const sorts = sortable.toArray();

                        axios.post("{{ route('api.sort.covers') }}", {
                            sorts: sorts
                        }).catch((error) => {
                            console.log(error);
                        });
                    }
                }
            });
        </script>
    @endpush

</x-admin-layout>
