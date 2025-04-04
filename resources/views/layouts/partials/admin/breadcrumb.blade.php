@if (count($breadcrumbs))
    <!-- Si existen elementos en $breadcrumbs, muestra el contenedor de navegación. -->

    <nav class="mb-4 text-white">
        {{-- Contenedor de las migas de pan en forma de lista ordenada --}}
        <ol class="flex flex-wrap">

            @foreach ($breadcrumbs as $breadcrumb)
                <li
                    class="text-sm leading-normal  
                    {{ !$loop->first ? "pl-2 before:float-left before:pr-2 before:content-['/']" : '' }}">
                    <!-- Itera cada elemento en $breadcrumbs. Si no es el primero, agrega una barra inclinada (/) antes del texto. -->

                    {{-- Si el breadcrumb tiene una ruta, la muestra como enlace; si no, solo muestra el nombre. --}}
                    @isset($breadcrumb['route'])
                        <a href="{{ $breadcrumb['route'] }}" class="opacity-50">
                            {{ $breadcrumb['name'] }}
                        </a>
                    @else
                        {{ $breadcrumb['name'] }}
                    @endisset

                </li>
            @endforeach

        </ol>
        {{-- Fin de la lista de migas de pan --}}

        @if (count($breadcrumbs) > 1)
            <h6 class="font-bold">
                {{ end($breadcrumbs)['name'] }}
            </h6>
            <!-- Muestra el nombre del último elemento de la lista de migas en negrita si hay más de un breadcrumb. -->
        @endif
    </nav>

@endif

