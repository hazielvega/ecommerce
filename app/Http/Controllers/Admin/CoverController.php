<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoverController extends Controller
{
    // Metodo para mostrar todas las portadas
    public function index()
    {
        $covers = Cover::orderBy('order', 'ASC')->get();
        return view('admin.covers.index', compact('covers')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.covers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validaciones
        $data = $request->validate([
            'image' => 'required|image',
            'title' => 'required|string|max:255',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'is_active' => 'required|boolean',
        ]);

        //almacenar la imagen
        $data['image_path'] = Storage::put('covers', $data['image']);

        //crear el cover
        Cover::create($data);

        // alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Agregado',
            'text' => 'Portada agregada correctamente',
        ]);

        //redireccionar
        return redirect()->route('admin.covers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cover $cover)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cover $cover)
    {
        return view('admin.covers.edit', compact('cover'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cover $cover)
    {
        //validaciones
        $data = $request->validate([
            'image' => 'nullable|image|max:1024',
            'title' => 'required|string|max:255',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'is_active' => 'required|boolean',
        ]);

        //almacenar la imagen
        if(isset($data['image'])) {
            Storage::delete($cover->image_path);
            $data['image_path'] = Storage::put('covers', $data['image']);
        }

        //actualizar el cover
        $cover->update($data);

        // alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Actualizado',
            'text' => 'Portada actualizada correctamente',
        ]);

        //redireccionar
        return redirect()->route('admin.covers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cover $cover)
    {
        //
    }
}
