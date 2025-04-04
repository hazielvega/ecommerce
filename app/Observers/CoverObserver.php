<?php

namespace App\Observers;

use App\Models\Cover;

class CoverObserver
{
    // Este metodo va a recibir la portada que está a punto de ser creada
    public function creating(Cover $cover)
    {
        // Asignar el orden de la portada
        $cover->order = Cover::max('order') + 1;
    }
}
