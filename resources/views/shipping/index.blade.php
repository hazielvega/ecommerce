<x-app-layout>

    <x-container class="mt-4 lg:mt-12">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-2">

            {{-- Primera columna --}}
            <div class="lg:col-span-2">
                @livewire('shipping-addresses')
            </div>

            {{-- Segunda columna --}}
            <div class="lg:col-span-1">
                {{-- Resumen del carrito --}}
                <div class="card bg-[#c2bf92]">
                    {{-- Encabezado --}}
                    <div class="p-4 flex justify-between items-center bg-gray-600 rounded-lg text-white">
                        <p class="font-semibold">
                            Resúmen de compra ({{ Cart::instance('shopping')->count() }} productos)
                        </p>
                        <a href="{{ route('cart.index') }}" class="text-sm font-thin hover:underline">
                            Editar
                        </a>
                    </div>

                    {{-- Listado del carrito --}}
                    <div class="bg-white rounded-lg px-4 py-4">
                        <ul class="divide-y divide-slate-500">
                            @foreach (Cart::content() as $item)
                                <li class="flex items-center space-x-4">
                                    {{-- Imagen --}}
                                    <figure class="shrink-0">
                                        <img src="{{ $item->options->image }}" class="h-12 aspect-square"
                                            alt="">
                                    </figure>

                                    {{-- Nombre, precio y caracteristicas --}}
                                    <div class="flex-1">
                                        <p class="font-semibold uppercase">
                                            {{ $item->name }}
                                        </p>

                                        <p>
                                            @foreach ($item->options->features as $feature)
                                                {{ $feature }}
                                            @endforeach
                                        </p>

                                        <p class="text-xs">
                                            ${{ $item->price }}
                                        </p>
                                    </div>

                                    {{-- Cantidad --}}
                                    <div class="shrink-0">
                                        <p class="text-xs">
                                            Cantidad: {{ $item->qty }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                            {{-- Precio de envio --}}
                            <li class="flex justify-between">
                                <p class="text-sm font-semibold">
                                    Envío
                                </p>

                                <p class="text-sm font-semibold">
                                    ${{ $shipping }}
                                </p>
                            </li>
                        </ul>

                        <hr class="my-4">

                        {{-- Total --}}
                        <div class="flex justify-between bg-indigo-300 p-2 rounded-lg">
                            <p class="text-lg">
                                Total
                            </p>

                            <p class="text-lg">
                                ${{ $total }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-container>
</x-app-layout>

