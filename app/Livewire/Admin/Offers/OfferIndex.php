<?php

namespace App\Livewire\Admin\Offers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Offer;
use Carbon\Carbon;

class OfferIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'start_date';
    public $sortDirection = 'desc';
    public $statusFilter = 'all';
    public $dateFilter = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'start_date'],
        'sortDirection' => ['except' => 'desc'],
        'statusFilter' => ['except' => 'all'],
        'dateFilter' => ['except' => ''],
        'perPage' => ['except' => 10]
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->dateFilter = '';
        $this->resetPage();
    }

    public function render()
    {
        $today = Carbon::today()->toDateString();

        $offers = Offer::query()
            ->withCount('products')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->when($this->dateFilter, function ($query) use ($today) {
                if ($this->dateFilter === 'active') {
                    $query->whereDate('start_date', '<=', $today)
                        ->whereDate('end_date', '>=', $today);
                } elseif ($this->dateFilter === 'upcoming') {
                    $query->whereDate('start_date', '>', $today);
                } elseif ($this->dateFilter === 'expired') {
                    $query->whereDate('end_date', '<', $today);
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.offers.offer-index', [
            'offers' => $offers,
            'totalOffers' => Offer::count(),
            'activeOffers' => Offer::where('is_active', true)->count(),
            'expiredOffers' => Offer::whereDate('end_date', '<', now())->count(),
        ]);
    }
}
