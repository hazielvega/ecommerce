<?php

namespace App\Observers;

use App\Mail\UserCreatedMail;
use App\Models\Receiver;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Receiver::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'last_name' => $user->last_name,
            'document_number' => $user->document_number,
            'email' => $user->email,
            'phone' => $user->phone,
            'default' => true
        ]);
        // Enviar correo al usuario recién creado
        Mail::to($user->email)->send(new UserCreatedMail($user));
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
