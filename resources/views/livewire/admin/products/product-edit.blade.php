<div>

    {{-- Editar producto --}}
    <div class="card">
        <h1 class="text-lg font-semibold text-white mb-4">{{ $product->name }}</h1>
        <div class="flex space-x-4 mb-4">
            {{-- ALERTA DE PRODUCTO DESHABILITADO O HABILITADO --}}
            <div class="mt-2">
                @if (!$product->is_enabled)
                    <span class="bg-red-700 text-gray-200 text-m font-bold p-2 rounded">
                        DESHABILITADO
                    </span>
                @else
                    <span class="bg-green-700 text-gray-100 text-m font-bold p-2 rounded">
                        HABILITADO
                    </span>
                @endif
            </div>


            <div>
                <button wire:click="validateBeforeEnable" class="btn btn-indigo">
                    @if ($product->is_enabled)
                        <span>
                            DESHABILITAR
                        </span>
                    @else
                        <span>
                            HABILITAR
                        </span>
                    @endif
                </button>
            </div>
        </div>

        <form wire:submit="store">
            {{-- INPUT CODIGO DE PRODUCTO --}}
            <div class="mb-4">
                <x-label class="mb-2">
                    Código
                </x-label>

                <x-input class="w-full" placeholder="Código del producto" wire:model="productEdit.sku">
                </x-input>
            </div>

            {{-- INPUT NOMBRE DE PRODUCTO --}}
            <div class="mb-4">
                <x-label class="mb-2">
                    Nombre
                </x-label>

                <x-input class="w-full" placeholder="Nombre del producto" wire:model="productEdit.name">
                </x-input>
            </div>

            {{-- INPUT DESCRIPCION DE PRODUCTO --}}
            <div class="mb-4">
                <x-label class="mb-2">
                    Descripción
                </x-label>

                <x-text-area class="w-full" placeholder="Descripción del producto" wire:model="productEdit.description">
                </x-text-area>
            </div>

            {{-- INPUT PRECIO DE COMPRA DE PRODUCTO --}}
            <div class="mb-4">
                <x-label class="mb-2">
                    Precio de compra
                </x-label>

                <x-input class="w-full" type="number" step="0,01" placeholder="Precio del producto"
                    wire:model="productEdit.purchase_price">
                </x-input>
            </div>

            {{-- INPUT PRECIO DE VENTA DE PRODUCTO --}}
            <div class="mb-4">
                <x-label class="mb-2">
                    Precio de venta
                </x-label>

                <x-input class="w-full" type="number" step="0,01" placeholder="Precio del producto"
                    wire:model="productEdit.sale_price">
                </x-input>
            </div>

            {{-- Listado de categorías --}}
            <div class="mb-4">
                <x-label class="mb-2">
                    Categoría
                </x-label>

                {{-- Select para las categorias --}}
                <x-select name="category_id" class="w-full" wire:model.live="category_id">
                    <option value="" disabled>
                        Selecciona una categoría
                    </option>

                    {{-- Recorremos lo que tenemos en la propiedad computada Categories() --}}
                    @foreach ($this->categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </x-select>
            </div>

            {{-- Listado de subcategorías --}}
            <div class="mb-4">
                <x-label class="mb-2">
                    Subcategoría
                </x-label>

                {{-- Select para las subcategorias --}}
                <x-select name="category_id" class="w-full" wire:model.live="productEdit.subcategory_id">
                    <option value="" disabled>
                        Selecciona una subcategoría
                    </option>

                    {{-- Recorremos lo que tenemos en la propiedad computada Subcategories() --}}
                    @foreach ($this->subcategories as $subcategory)
                        <option
                            value="{{ $subcategory->id }}
                                    @selected(old('subcategory_id') == $subcategory->id)">
                            {{ $subcategory->name }}
                        </option>
                    @endforeach
                </x-select>
            </div>

            {{-- SUBIR IMÁGENES --}}
            <div class="mb-4">
                <x-label class="mb-2">Subir imágenes</x-label>
                <input type="file" multiple accept="image/*" wire:model="images" class="mt-1 block w-full">
                @error('images.*')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- PREVISUALIZACIÓN DE IMÁGENES EXISTENTES --}}
            <div class="flex flex-wrap gap-4 mb-4">
                @foreach ($existingImages as $index => $image)
                    <div class="relative">
                        <img src="{{ Storage::url($image) }}" class="w-24 h-24 object-cover rounded-md">
                        <button type="button" wire:click="removeExistingImage({{ $index }})"
                            class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center">
                            &times;
                        </button>
                    </div>
                @endforeach
            </div>

            {{-- PREVISUALIZACIÓN DE NUEVAS IMÁGENES --}}
            <div class="flex flex-wrap gap-4 mb-4">
                @foreach ($previews as $index => $preview)
                    <div class="relative">
                        <img src="{{ $preview }}" class="w-24 h-24 object-cover rounded-md">
                        <button type="button" wire:click="removeImage({{ $index }})"
                            class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center">
                            &times;
                        </button>
                    </div>
                @endforeach
            </div>

            <x-validation-errors class="mb-4"></x-validation-errors>


            {{-- BOTON PARA ACTUALIZAR --}}
            <div class="flex justify-end">
                <x-button class="ml-2">
                    Actualizar
                </x-button>
            </div>

        </form>

    </div>

    {{-- ALERTA DE SWEETALERT PARA DESHABILITAR / HABILITAR --}}
    @push('js')
        <script>
            function confirmDelete() {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "No podrás volver atrás",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Eliminar de todas formas",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form').submit();
                    }
                });
            }
        </script>
    @endpush
</div>
