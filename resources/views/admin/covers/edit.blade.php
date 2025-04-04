<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Portadas',
        'route' => route('admin.covers.index'),
    ],
    [
        'name' => 'Editar',
    ],
]">

    {{-- Formulario para editar una portada --}}
    <div class="card">
        <form action="{{ route('admin.covers.update', $cover) }}" method="POST"
            enctype="multipart/form-data"> {{-- Esto es para subir una imagen --}}
            @csrf

            @method('PUT')

            {{-- Input para subir una imagen --}}
            <figure class=" mb-4 relative">
                {{-- Boton para subir una imagen --}}
                <div class="absolute top-8 right-8">
                    <label class="flex items-center px-4 py-2 rounded-lg bg-blue-400 cursor-pointer">
                        <i class="fas fa-camera mr-2"></i>
                        Subir imagen

                        {{-- Input para subir una imagen  --}}
                        <input type="file" class="hidden" accept="image/*"
                            name="image"
                            onchange="previewImage(event, '#imgPreview')">
                    </label>
                </div>

                {{-- Accedemos al accesor image creado en el modelo --}}
                <img id="imgPreview" src="{{ $cover->image }}" alt="Portada"
                    class="aspect-[3/1] w-full object-cover object-center">
            </figure>

            {{-- Input para el Titulo --}}
            <div class="mb-4">
                <x-label>
                    Titulo
                </x-label>

                {{-- Input para el título --}}
                <x-input class="w-full" placeholder="Título de la portada" name="title"
                    value="{{ old('title', $cover->title) }}" />
            </div>

            {{-- Input para la fecha de inicio --}}
            <div class="mb-4">
                <x-label>
                    Fecha de inicio
                </x-label>

                {{-- Input para la fecha --}}
                <x-input type="date" class="w-full" name="start_at"
                    value="{{ old('start_at', $cover->start_at->format('Y-m-d')) }}" />
            </div>

            {{-- Input para la fecha de fin --}}
            <div class="mb-4">
                <x-label>
                    Fecha de fin (Opcional)
                </x-label>

                {{-- Input para la fecha --}}
                <x-input type="date" class="w-full" name="end_at"
                    value="{{ old('end_at'), $cover->end_at ? $cover->end_at->format('Y-m-d') : ''}}" />
            </div>

            {{-- Input para el booleano is_active --}}
            <div class="mb-4 flex space-x-2">
                <label>
                    <x-input type="radio" name="is_active" value="1" :checked="$cover->is_active">
                    </x-input>
                    Activo
                </label>

                <label>
                    <x-input type="radio" name="is_active" value="0" :checked="!$cover->is_active">
                    </x-input>
                    Inactivo
                </label>
            </div>

            {{-- Validacion de los campos --}}
            <x-validation-errors class="mb-4"></x-validation-errors>

            {{-- Boton para eliminar o actualizar una portada --}}
            <div class="flex justify-end">
                {{-- Boton para eliminar una portada --}}

                {{-- Boton para actualizar una portada --}}
                <x-button class="ml-2">
                    Actualizar
                </x-button>
            </div>
        </form>
    </div>


    {{-- Script para previsualizar una imagen --}}
    @push('js')
        <script>
            function previewImage(event, querySelector) {

                //Recuperamos el input que desencadeno la acción
                const input = event.target;

                //Recuperamos la etiqueta img donde cargaremos la imagen
                $imgPreview = document.querySelector(querySelector);

                // Verificamos si existe una imagen seleccionada
                if (!input.files.length) return

                //Recuperamos el archivo subido
                file = input.files[0];

                //Creamos la url
                objectURL = URL.createObjectURL(file);

                //Modificamos el atributo src de la etiqueta img
                $imgPreview.src = objectURL;

            }
        </script>
    @endpush

</x-admin-layout>
