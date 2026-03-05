<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Hash;

class UsersManager extends Component
{
    Use WithPagination;

    public $name;
    public $email;
    public $password;
    public $nivel = 3;
    public $activo = true;
    public $userId;

    public function create()
    {
        $this->reset();
        $this->userId = null;
    }

    public function edit(User $user)
    {
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nivel = $user->nivel;
        $this->activo = $user->activo;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'nivel' => 'required|integer|min:1|max:3',
            'activo' => 'required|boolean',
        ]);

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'nivel' => $this->nivel,
                'activo' => $this->activo,
            ]);
            session()->flash('success', 'Usuario actualizado correctamente');
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make('12345678'),
                'nivel' => $this->nivel,
                'activo' => $this->activo,
            ]);
            session()->flash('success', 'Usuario creado correctamente');
        }
        $this->reset();
    }

    public function cancel()
    {
        $this->reset();
    }

    #[Computed]
    public function users()
    {
        return User::orderBy('name')
            ->paginate(10);
    }
    
    public function render()
    {
        return view('livewire.users-manager');
    }
}
