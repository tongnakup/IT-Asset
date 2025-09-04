<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ItAsset;

class BrandSearch extends Component
{
    public $query = '';
    public $brands = [];

    public function updatedQuery()
    {
        if (strlen($this->query) >= 1) {
            $this->brands = ItAsset::where('brand', 'like', '%' . $this->query . '%')
                ->distinct()
                ->take(5)
                ->pluck('brand');
        } else {
            $this->brands = [];
        }
    }

    public function selectBrand($brand)
    {
        $this->query = $brand;
        $this->brands = [];
    }

    public function render()
    {
        return view('livewire.brand-search');
    }
}
