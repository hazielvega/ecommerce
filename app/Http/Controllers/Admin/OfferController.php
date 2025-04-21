<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function edit(Offer $offer)
    {
        return view('admin.offers.edit', compact('offer'));
    }

    public function create()
    {
        return view('admin.offers.create');
    }
}
