<x-app-layout>
    <x-container>
        <div class="text-gray-700" x-data="{ pago: 1 }">
            <div class="grid grid-cols-1 lg:grid-cols-2 mt-6 rounded-lg overflow-auto bg-[#c2bf92] m-2 gap-4">

                {{-- Métodos de pago --}}
                <div class="col-span-1">
                    <div class="lg:max-w-[40rem] py-12 px-6 lg:pr-8 sm:pl-6 lg:pl-8 ml-auto space-y-6">

                        <h1 class="text-2xl font-semibold mb-4">Resúmen de compra</h1>

                        {{-- Información del destinatario --}}
                        <div class="bg-white shadow rounded-lg p-4">
                            <h2 class="text-lg font-semibold mb-2">Destinatario</h2>
                            @if ($receiver)
                                <p><b>Nombre:</b> {{ $receiver->name }} {{ $receiver->last_name }}</p>
                                <p><b>Documento:</b> {{ $receiver->document_number }}</p>
                                <p><b>Email:</b> {{ $receiver->email }}</p>
                                <p><b>Teléfono:</b> {{ $receiver->phone }}</p>
                            @else
                                <p>No hay destinatario seleccionado.</p>
                            @endif
                        </div>

                        {{-- Dirección de envío --}}
                        <div class="bg-white shadow rounded-lg p-4">
                            <h2 class="text-lg font-semibold mb-2">Dirección de Envío</h2>
                            @if ($shipping_address)
                                <p>{{ $shipping_address->calle }} {{ $shipping_address->numero }}</p>
                                <p>{{ $shipping_address->ciudad }}, {{ $shipping_address->provincia }}</p>
                                <p>{{ $shipping_address->codigo_postal }}</p>
                            @else
                                <p>No hay dirección de envío seleccionada.</p>
                            @endif
                        </div>

                        {{-- Dirección de facturación --}}
                        <div class="bg-white shadow rounded-lg p-4">
                            <h2 class="text-lg font-semibold mb-2">Dirección de Facturación</h2>
                            @if ($billing_address)
                                <p>{{ $billing_address->calle }} {{ $billing_address->numero }}</p>
                                <p>{{ $billing_address->ciudad }}, {{ $billing_address->provincia }}</p>
                                <p>{{ $billing_address->codigo_postal }}</p>
                            @else
                                <p>No hay dirección de facturación seleccionada.</p>
                            @endif
                        </div>

                        {{-- Métodos de pago --}}
                        <div class="shadow rounded-lg overflow-hidden border bg-[#d1cfb0]">
                            <ul class="divide-y divide-gray-200">
                                <li>
                                    <label class="p-4 flex items-center cursor-pointer">
                                        <input type="radio" x-model="pago" value="1">
                                        <div class="lg:flex lg:items-center">
                                            <p class="ml-2">Tarjeta de crédito/débito</p>
                                            <img class="h-16 lg:ml-6"
                                                src="https://static.wixstatic.com/media/85e2bf_7b77c4a0cde043f2a35bcf96f7ddfbd6~mv2.png"
                                                alt="">
                                        </div>
                                    </label>

                                    <div class="p-4 bg-gray-100 text-center text-black border-t border-gray-400"
                                        x-show="pago == 1">
                                        <i class="fa-regular fa-credit-card text-3xl"></i>
                                        <p class="mt-2">
                                            Se abrirá una nueva ventana para realizar el pago de forma segura.
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

                {{-- Resumen de compra --}}
                <div class="col-span-1 bg-white">
                    <div class="lg:max-w-[40rem] py-12 px-4 lg:pl-8 sm:pr-6 lg:pr-8 mr-auto">

                        {{-- Lista de productos --}}
                        <ul class="space-y-4 mb-4">
                            @foreach ($content as $item)
                                <li class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img class="h-16 w-16 object-cover" src="{{ $item->options['image'] }}"
                                            alt="">
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium uppercase">{{ $item->name }}</p>
                                        <p class="text-sm">
                                            @foreach ($item->options->features as $feature)
                                                {{ $feature }}
                                            @endforeach
                                        </p>
                                        <p>${{ $item->price }}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <p class="text-sm">Cantidad: {{ $item->qty }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Subtotal --}}
                        <div class="flex justify-between">
                            <p>Subtotal:</p>
                            <p>${{ $subtotal }}</p>
                        </div>

                        {{-- Envío --}}
                        <div class="flex justify-between">
                            <p>Precio de envío: <i class="fa-solid fa-truck"></i></p>
                            <p>${{ $shipping }}</p>
                        </div>

                        <hr class="my-3">

                        {{-- Total --}}
                        <div class="flex justify-between mb-4">
                            <p class="text-lg font-semibold">Total:</p>
                            <p>${{ $total }}</p>
                        </div>

                        {{-- Botón para confirmar --}}
                        <div>
                            <button class="btn w-full" onclick="VisanetCheckout.open()">
                                Finalizar Compra
                            </button>
                        </div>

                        {{-- Alerta de pago denegado --}}
                        @if (session('niubiz'))
                            @php
                                $niubiz = session('niubiz');
                                $response = $niubiz['response'];
                                $purchaseNumber = $niubiz['purchaseNumber'];
                            @endphp

                            @isset($response['data'])
                                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 mt-6" role="alert">
                                    <p class="mb-4">
                                        {{ $response['data']['ACTION_DESCRIPTION'] }}
                                    </p>
                                    <p><b>Número de pedido:</b> {{ $purchaseNumber }}</p>
                                    <p><b>Fecha y hora del pedido:</b>
                                        {{ now()->createFromFormat('ymdHis', $response['data']['TRANSACTION_DATE'])->format('d/m/Y H:i:s') }}
                                    </p>
                                    @isset($response['data']['CARD'])
                                        <p><b>Tarjeta:</b> {{ $response['data']['CARD'] }} ({{ $response['data']['BRAND'] }})
                                        </p>
                                    @endisset
                                </div>
                            @endisset
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </x-container>



    {{-- Script para procesar el pago --}}
    @push('js')
        <script type="text/javascript" src="{{ config('services.niubiz.url_js') }}"></script>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {

                let purchasenumber = Math.floor(Math.random() * 1000000000);
                let amount = {{ $total }};

                VisanetCheckout.configure({
                    sessiontoken: "{{ $session_token }}",
                    channel: 'web',
                    merchantid: "{{ config('services.niubiz.merchant_id') }}",
                    purchasenumber: purchasenumber,
                    amount: amount,
                    expirationminutes: '20',
                    timeouturl: 'about:blank',
                    merchantlogo: 'img/comercio.png',
                    formbuttoncolor: '#000000',
                    action: "{{ route('checkout.paid') }}?amount=" + amount + "&purchaseNumber=" +
                        purchasenumber,
                    complete: function(params) {
                        alert(JSON.stringify(params));
                    }
                });
            })
        </script>
    @endpush
</x-app-layout>
