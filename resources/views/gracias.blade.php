<x-app-layout>
    <x-container>
        <div class="flex flex-col m-2">
            <div class="text-center text-black py-8 bg-[#9ce49aee] mt-4 rounded-lg">
                <p class="text-3xl font-semibold">
                    Muchas Gracias
                </p>
                <p class="text-xl mt-4">
                    Compra realizada con éxito
                </p>
                {{-- Ir a mis pedidos --}}
                @if (auth()->check())
                    <div class="mt-4">
                        <a href="{{ route('orders.index') }}" class="btn">Ir a Mis Pedidos</a>
                    </div>
                @endif
                <p class="mt-4">
                    Se enviará un correo con el detalle de tu compra
                </p>

            </div>

            {{-- Alerta de exito de pago exitoso niubiz --}}
            @if (session('niubiz'))
                @php
                    $response = session('niubiz')['response'];
                @endphp

                {{-- Alerta de exito de pago exitoso niubiz --}}
                <div class="flex-1 p-4 mt-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                    role="alert">
                    {{-- Datos de la transacción --}}
                    <div>
                        <p class="mb-2">
                            {{ $response['dataMap']['ACTION_DESCRIPTION'] }}
                        </p>

                        <p>
                            <b>Número de pedido:</b>
                            {{ $response['order']['purchaseNumber'] }}
                        </p>

                        <p>
                            <b>Fecha y hora del pedido:</b>
                            {{ now()->createFromFormat('ymdHis', $response['dataMap']['TRANSACTION_DATE'])->format('d/m/Y H:i:s') }}
                        </p>

                        <p>
                            <b>Tarjeta:</b>
                            {{ $response['dataMap']['CARD'] }} ({{ $response['dataMap']['BRAND'] }})
                        </p>

                        <p>
                            <b>Importe:</b>
                            {{ $response['order']['amount'] }} {{ $response['order']['currency'] }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </x-container>
</x-app-layout>
