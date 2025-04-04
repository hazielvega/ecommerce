<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use LaravelLang\Lang\Plugins\Fortify\V1;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Primero elimino la imagen
        Storage::delete($product->image_path);

        // Elimino el producto
        $product->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Eliminado',
            'text' => 'Producto eliminado correctamente',
        ]);

        return redirect()->route('admin.products.index');
    }


    // Metodo para editar una variante
    public function variants(Product $product, Variant $variant)
    {
        return view('admin.products.variants', compact('product', 'variant'));
    }

    // Metodo para actualizar una variante
    public function variantsUpdate(Request $request, Product $product, Variant $variant)
    {
        $data = $request->validate([
            'image' => 'image|max:1024',
            'sku' => 'required',
            'stock' => 'required|numeric|min:0',
        ]);

        // Si se quiere actualizar la imagen
        if ($request->image) {
            // Primero elimino la imagen si es que existe
            if ($variant->image_path) {
                Storage::delete($variant->image_path);
            }

            // Actualizo la imagen de la variante
            $data['image_path'] = $request->image->store('products');
        }

        $variant->update($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Actualizado',
            'text' => 'Variante actualizada correctamente',
        ]);

        return redirect()->route('admin.products.variants', [$product, $variant]);
    }

    // Reporte de productos en excel
    public function export()
    {
        return Excel::download(new ProductsExport, 'productos.xlsx');
    }
}
