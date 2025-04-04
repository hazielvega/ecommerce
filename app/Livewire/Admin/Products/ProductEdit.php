<?php

namespace App\Livewire\Admin\Products;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Variant;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductEdit extends Component
{
    // Importamos esto para poder subir imagenes
    use WithFileUploads;

    // Recibimos el producto con una propiedad que tenga el mismo nombre con el cuál se lo envió
    public $product;
    // variable para editar
    public $productEdit;

    public $categories;
    // Van a almacenar los id de las opciones seleccionadas
    public $category_id = '';

    public $images = [], $previews = [], $existingImages = [];

    // Recupero todos los datos que necesito de mi base de datos
    public function mount($product)
    {
        // Almaceno en el edit solo los campos que voy a editar
        $this->productEdit = $product->only('sku', 'name', 'description', 'image_path', 'purchase_price', 'sale_price', 'subcategory_id');

        $this->categories = Category::all();
        $this->category_id = $product->subcategory->category->id;

        // Convertimos las imágenes JSON en un array para previsualizar
        $this->existingImages = json_decode($product->image_path, true) ?? [];

        // Si el producto no tiene variantes, se lo deshabilita
        if ($product->variants->isEmpty()) {
            $this->product->is_enabled = false;
        }
    }


    // Mantenerse a la escucha de un cambio en la seleccion de categorias
    public function updatedCategoryId()
    {
        // Si se actualiza el valor de category_id, los campos relacionados cambian a una cadena vacía
        $this->productEdit['subcategory_id'] = '';
    }

    // Actualiza la lista de imágenes y genera previews
    public function updatedImages()
    {
        $this->previews = collect($this->images)->map(fn($img) => $img->temporaryUrl())->toArray();
    }

    // Eliminar una imagen antes de guardar
    public function removeImage($index)
    {
        array_splice($this->images, $index, 1);
        array_splice($this->previews, $index, 1);
    }

    // Eliminar una imagen ya guardada
    public function removeExistingImage($index)
    {
        Storage::delete($this->existingImages[$index]);
        array_splice($this->existingImages, $index, 1);
    }

    // Metodo para recuperar todas las variantes del producto
    #[Computed()]
    public function variants()
    {
        return Variant::where('product_id', $this->product->id)->get();
    }

    // Recupero todas las subcategorías asignadas a la categoría seleccionada
    #[Computed()]
    public function subcategories()
    {
        return Subcategory::where('category_id', $this->category_id)->get();
    }

    // Creo un nuevo producto con la información que se manda desde el formulario presente en la vista del componente
    public function store()
    {
        $this->validate([
            // "productEdit." se tratan de elementos del array productEdit[] 
            'images.*' => 'nullable|image|max:1024',
            'productEdit.subcategory_id' => 'required|exists:subcategories,id',/* Debe existir en la tabla subcategories en el campo id */
            'productEdit.sku' => 'required|unique:products,sku, ' . $this->product->id,  /*Debe ser unico en la tabla products en el campo sku*/
            'productEdit.name' => 'required|max:255',/* Maximo 255 caracteres */
            'productEdit.description' => 'nullable',
            'productEdit.purchase_price' => 'required|numeric|min:0',
            'productEdit.sale_price' => 'required|numeric|min:0',
        ], [], [
            'productEdit.sku' => 'código',
            'productEdit.name' => 'nombre',
            'image' => 'imágen',
            'productEdit.purchase_price' => 'precio de compra',
            'productEdit.sale_price' => 'precio de venta',
            'productEdit.subcategory_id' => 'subcategoría',
        ]);

        // Guardar nuevas imágenes y agregar a las existentes
        $newImages = collect($this->images)->map(fn($image) => $image->store('products'))->toArray();
        $this->productEdit['image_path'] = json_encode(array_merge($this->existingImages, $newImages));

        // Actualizamos el producto
        $this->product->update($this->productEdit);

        // Alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Actualizado',
            'text' => 'Producto actualizado correctamente',
        ]);

        // Redirijo a la pantalla de edicion del producto
        return redirect()->route('admin.products.edit', $this->product);
    }

    // Metodo para habilitar o deshabilitar un producto
    public function toggleEnabled()
    {
        // Cambia el estado del atributo is_enabled
        $this->product->is_enabled = !$this->product->is_enabled;

        // Guarda los cambios en la base de datos
        $this->product->save();

        // Mensaje de confirmación
        if ($this->product->is_enabled) {
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Activado',
                'text' => 'Producto activado correctamente',
            ]);
        } else {
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Desactivado',
                'text' => 'Producto desactivado correctamente',
            ]);
        }

        // Refresca el producto para actualizar la vista
        $this->product = $this->product->fresh();
    }

    // Metodo para validar antes de habilitar un producto
    public function validateBeforeEnable()
    {
        // Verifica si hay variantes relacionadas al producto
        if ($this->product->variants->isEmpty()) {
            $message = "No puedes activar el producto debido a que no tiene variantes asociadas.\n";
            // Emitir evento para mostrar alerta en la vista
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Atención',
                'text' => $message,
            ]);

            return;
        }

        // Si la validación es exitosa, llamamos al metodo para habilitar el producto
        $this->toggleEnabled();
    }



    public function render()
    {
        return view('livewire.admin.products.product-edit');
    }
}
