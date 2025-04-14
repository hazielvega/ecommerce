<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
            <div class="bg-red-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 text-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                        Pago no procesado
                    </h2>
                    <!-- Icono de error -->
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-200 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>

                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">
                        ¡Hubo un problema con tu pago!
                    </h3>

                    <div class="mt-2 max-w-xl text-sm text-gray-500 mb-6">
                        <p>El pago no pudo ser procesado. Por favor, intenta nuevamente.</p>

                        @if (session('error'))
                            <p class="mt-2 text-red-600 font-medium">{{ session('error') }}</p>
                        @endif
                    </div>

                    <div class="mt-5">
                        <!-- Botón para reintentar -->
                        <a href="{{ route('cart.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 disabled:opacity-25 transition">
                            Volver al carrito
                        </a>

                        <!-- Botón secundario -->
                        <a href="{{ route('welcome.index') }}"
                            class="ml-3 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition">
                            Ir a la tienda
                        </a>
                    </div>

                    <!-- Información adicional para el usuario -->
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">¿Necesitas ayuda?</h4>
                        <p class="text-sm text-gray-500">
                            Contáctanos a <a href="mailto:soporte@tutienda.com"
                                class="text-red-600 hover:text-red-500">soporte@tutienda.com</a> o al <a
                                href="tel:+5491112345678" class="text-red-600 hover:text-red-500">+54 9 11 1234-5678</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
