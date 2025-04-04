<?php

namespace App\Livewire\Admin\Products;

use Livewire\Component;

class ShowVariants extends Component
{
    public $product;
    public $open;

    public function mount($product)
    {
        $this->product = $product;
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.admin.products.show-variants');
    }
}
