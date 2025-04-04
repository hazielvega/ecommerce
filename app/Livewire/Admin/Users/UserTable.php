<?php

namespace App\Livewire\Admin\Users;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;

class UserTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            // No quiero que esta columna se muestre
            Column::make("Id", "id")
                ->hideIf(1),
            // Nombre de usuario
            Column::make("Nombres", "name")
                ->sortable()
                ->searchable(),
            // Apellido
            Column::make("Apellidos", "last_name")
                ->sortable(),
            // Email
            Column::make("Email", "email")
                ->sortable(),
            // Telefono
            Column::make("Teléfono", "phone")
                ->sortable(),
            // Rol
            Column::make("Rol")
                ->label(function ($row) {
                    return view('admin.users.role', ['user' => $row]);
                })

        ];
    }

    // Metodo para cambiar el rol de un usuario
    public function changeRole($user_id)
    {
        $user = User::find($user_id);
        $user->hasRole('admin') ? $user->removeRole('admin') : $user->assignRole('admin');
        $user->save();
    }
}
