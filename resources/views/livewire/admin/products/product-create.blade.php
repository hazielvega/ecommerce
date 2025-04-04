<div class="card">

    <form wire:submit.prevent="store">

        {{-- SUBIR IMÁGENES --}}
        <div class="mb-4">
            <x-label >Subir imágenes</x-label>
            <input type="file" multiple accept="image/*" wire:model="images" class="mt-1 block w-full">

            {{-- Mensaje de carga --}}
            @error('images.*')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- PREVISUALIZACIÓN DE IMÁGENES --}}
        <div class="flex flex-wrap gap-4 mb-4">
            @foreach ($previews as $preview)
                <div class="relative">
                    <img src="{{ $preview }}" class="w-24 h-24 object-cover rounded-md">
                    <button type="button" wire:click="removeImage({{ $loop->index }})"
                        class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center">
                        &times;
                    </button>
                </div>
            @endforeach
        </div>


        {{-- SELECT PARA LAS CATEGORIAS --}}
        <div class="mb-4">
            <x-label class="mb-2">
                Categoría
            </x-label>

            <x-select name="category_id" class="w-full" wire:model.live="category_id">
                <option value="" disabled>
                    Selecciona una categoría
                </option>

                {{-- Recorremos lo que tenemos en la propiedad computada Categories() --}}
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </x-select>
        </div>

        {{-- SELECT PARA LAS SUBCATEGORIAS --}}
        <div class="mb-4">
            <x-label class="mb-2">
                Subcategoría
            </x-label>

            <x-select name="category_id" class="w-full" wire:model.live="product.subcategory_id">
                <option value="" disabled>
                    Selecciona una subcategoría
                </option>

                {{-- Recorremos lo que tenemos en la propiedad computada Categories() --}}
                @foreach ($this->subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}
                            @selected(old('subcategory_id') == $subcategory->id)">
                        {{ $subcategory->name }}
                    </option>
                @endforeach
            </x-select>
        </div>

        {{-- INPUT CODIGO DE PRODUCTO --}}
        <div class="mb-4">
            <x-label class="mb-2">
                Código
            </x-label>

            <x-input class="w-full" placeholder="Código del producto" wire:model="product.sku">
            </x-input>
        </div>

        {{-- INPUT NOMBRE DE PRODUCTO --}}
        <div class="mb-4">
            <x-label class="mb-2">
                Nombre
            </x-label>

            <x-input class="w-full" placeholder="Nombre del producto" wire:model="product.name">
            </x-input>
        </div>

        {{-- INPUT DESCRIPCION DE PRODUCTO --}}
        <div class="mb-4">
            <x-label class="mb-2">
                Descripción
            </x-label>

            <x-text-area class="w-full" placeholder="Descripción del producto" wire:model="product.description">
            </x-text-area>
        </div>

        {{-- INPUT PRECIO DE COMPRA DE PRODUCTO --}}
        <div class="mb-4">
            <x-label class="mb-2">
                Precio de compra
            </x-label>

            <x-input class="w-full" type="number" step="0,01" placeholder="Precio de compra del producto"
                wire:model="product.purchase_price">
            </x-input>
        </div>

        {{-- INPUT PRECIO DE VENTA DE PRODUCTO --}}
        <div class="mb-4">
            <x-label class="mb-2">
                Precio de venta
            </x-label>

            <x-input class="w-full" type="number" step="0,01" placeholder="Precio de venta del producto"
                wire:model="product.sale_price">
            </x-input>
        </div>

        <x-validation-errors class="mb-4"></x-validation-errors>

        {{-- BOTON PARA GUARDAR --}}
        <div class="flex justify-end">
            <x-button>
                Guardar
            </x-button>
        </div>

    </form>
</div>
