<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function show(Offer $offer)
    {
        $offers = Offer::all();
        return view('offers.show', compact('offers'));
    }
}
