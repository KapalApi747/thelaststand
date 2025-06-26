<?php

namespace App\Livewire\Tenant\Backend\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

#[Layout('t-dashboard-layout')]
class AccountEdit extends Component
{
    use WithFileUploads;

    public $user;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $userRoles = [];
    public $allRoles = [];
    public $is_active;
    public $profile_picture;
    public $existingProfilePicture;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'userRoles' => 'required|array|min:1',
            'is_active' => 'required|boolean',
            'profile_picture' => 'nullable|image|max:2048',
        ];
    }

    public function mount(User $user)
    {
        $user->load('roles');
        $this->user = $user;

        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_active = $user->is_active ? '1' : '0';
        $this->existingProfilePicture = $user->profile_picture_path;
        $this->userRoles = $user->roles->pluck('name')->toArray();
        $this->allRoles = Role::all();
    }

    public function updateUser()
    {
        $this->validate();

        $updateInfo = [
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $updateInfo['password'] = Hash::make($this->password);
        }

        $this->user->update($updateInfo);

        if ($this->profile_picture) {
            $profilePicturePath = $this->profile_picture->store('assets/img/users', 'tenancy');
            $this->user->profile_picture_path = $profilePicturePath;
            $this->user->save();
        }

        if (Auth::guard('web')->user()->hasRole('admin')) {
            $this->user->syncRoles($this->userRoles);
        }

        session()->flash('message', 'User updated successfully!');
    }

    public function updated($validation)
    {
        $this->validateOnly($validation);
    }

    public function render()
    {
        return view('livewire.tenant.backend.users.account-edit');
    }
}
