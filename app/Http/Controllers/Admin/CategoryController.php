<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use FFI;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Category::create($request->all());

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Agregado',
            'text' => 'Categoría agregada correctamente',
        ]);

        return redirect()->route('admin.categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $category->update($request->all());

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Actualizada',
            'text' => 'Categoría actualizada correctamente',
        ]);

        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if($category->subcategories->count() > 0){
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se puede eliminar la categoría porque tiene asociada una o más subcategorías',
            ]);
    
            return redirect()->route('admin.categories.edit', $category);
        }
        $category->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Eliminado',
            'text' => 'Categoría eliminada correctamente',
        ]);

        return redirect()->route('admin.categories.index');
    }
}
