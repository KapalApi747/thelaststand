<?php

namespace App\Livewire\Tenant\Backend\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

#[Layout('t-dashboard-layout')]
class UserRegistration extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $roles = [];
    public $is_active = 1;
    public $profile_picture;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'password_confirmation' => 'required|string|min:8',
        'roles' => 'required|array|min:1',
        'is_active' => 'required|boolean',
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ];

    public function registerUser()
    {
        $this->validate();

        $profilePicturePath = $this->profile_picture
            ? $this->profile_picture->store('assets/img/users', 'tenancy')
            : null;

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_active' => $this->is_active,
            'profile_picture_path' => $profilePicturePath,
        ]);

        $user->assignRole($this->roles);

        session()->flash('message', 'User registered successfully!');

        $this->reset();
    }

    public function updated($validation)
    {
        $this->validateOnly($validation);
    }

    public function render()
    {
        $existingRoles = Role::all();

        return view('livewire.tenant.backend.users.user-registration', [
            'existingRoles' => $existingRoles,
        ]);
    }
}
