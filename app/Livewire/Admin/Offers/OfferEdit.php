<?php

namespace App\Livewire\Admin\Offers;

use Livewire\Component;
use App\Models\Category;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Livewire\WithPagination;

class OfferEdit extends Component
{
    use WithPagination;

    public Offer $offer;
    public array $selected_products = [];
    public $search = '';
    public $selected_category = '';
    public $selected_subcategory = '';
    public $subcategories = [];

    public $name;
    public $description;
    public $discount_percentage;
    public $start_date;
    public $end_date;
    public $is_active;

    protected $listeners = ['removeSelectedProduct'];

    public function mount(Offer $offer)
    {
        $this->offer = $offer;
        $this->name = $offer->name;
        $this->description = $offer->description;
        $this->discount_percentage = $offer->discount_percentage;
        $this->start_date = $offer->start_date->format('Y-m-d\TH:i');
        $this->end_date = $offer->end_date->format('Y-m-d\TH:i');
        $this->is_active = $offer->is_active;

        // Cargar productos seleccionados
        $this->selected_products = $offer->products->mapWithKeys(function ($product) {
            return [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->sale_price,
                ]
            ];
        })->toArray();
    }

    public function updatedSelectedCategory($value)
    {
        $this->subcategories = Subcategory::where('category_id', $value)->get();
        $this->selected_subcategory = '';
        $this->resetPage();
    }

    public function addProductsFromCategory()
    {
        $query = Product::query()->where('is_enabled', 1);

        if ($this->selected_subcategory) {
            $query->where('subcategory_id', $this->selected_subcategory);
        } elseif ($this->selected_category) {
            $query->whereHas('subcategory', function ($q) {
                $q->where('category_id', $this->selected_category);
            });
        }

        $products = $query->get();

        foreach ($products as $product) {
            if (!array_key_exists($product->id, $this->selected_products)) {
                $this->selected_products[$product->id] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->sale_price,
                ];
            }
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'Productos agregados',
            'message' => count($products) . ' productos han sido agregados a la oferta.',
        ]);
    }

    public function removeSelectedProduct($productId)
    {
        unset($this->selected_products[$productId]);
    }

    public function updateOffer()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:offers,name,' . $this->offer->id,
            'description' => 'nullable|string|max:500',
            'discount_percentage' => 'required|numeric|min:1|max:99',
            'start_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->lt(now())) {
                        $fail('La fecha de inicio no puede ser en el pasado para ofertas existentes.');
                    }
                },
                'before:end_date'
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->diffInDays($this->start_date) > 365) {
                        $fail('La oferta no puede durar más de 1 año');
                    }
                }
            ],
            'selected_products' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    if (count($value) < 1) {
                        $fail('Debe seleccionar al menos un producto');
                    }
                }
            ]
        ], [], [
            'name' => 'nombre',
            'description' => 'descripción',
            'discount_percentage' => 'porcentaje de descuento',
            'start_date' => 'fecha de inicio',
            'end_date' => 'fecha de finalización',
            'selected_products' => 'productos seleccionados'
        ]);

        try {
            DB::transaction(function () {
                $this->offer->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'discount_percentage' => $this->discount_percentage,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'is_active' => $this->is_active,
                ]);

                $productIds = array_keys($this->selected_products);
                $this->offer->products()->sync($productIds);
            });

            $this->dispatch('swal', [
                'type' => 'success',
                'title' => 'Oferta actualizada',
                'message' => 'La oferta se ha actualizado correctamente.',
            ]);

            return redirect()->route('admin.products.index');
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'type' => 'error',
                'title' => 'Error al actualizar oferta',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        $products = Product::query()
            ->where('is_enabled', 1)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->selected_subcategory, function ($query) {
                $query->where('subcategory_id', $this->selected_subcategory);
            })
            ->when($this->selected_category && !$this->selected_subcategory, function ($query) {
                $query->whereHas('subcategory', function ($q) {
                    $q->where('category_id', $this->selected_category);
                });
            })
            ->orderBy('name')
            ->limit(20)
            ->get();

        $categories = Category::all();

        return view('livewire.admin.offers.offer-edit', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}