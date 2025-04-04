<x-app-layout>

    <x-container class="mt-12 px-4">
        <div class="text-[40px] text-white font-semibold m-4">
            <p>
                Se muestran resultados de : "{{ $search }}"
            </p>
        </div>

    </x-container>
    {{-- Llamo al componente filter pasandole la busqueda--}}
    @livewire('filter', [
        's' => $search,
    ])


</x-app-layout>