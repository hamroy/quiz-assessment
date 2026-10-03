<?php

use App\Enums\UserRole;
use App\Livewire\Forms\UserForm;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public UserForm $form;

    public User $user;

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->form->userId = $user->id;
        $this->form->name = $user->name;
        $this->form->email = $user->email;
        $this->form->role = $user->role->value;
    }

    public function save(): void
    {
        $data = $this->form->validate();

        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        unset($data['password_confirmation']);
        $this->user->update($data);

        $this->redirectRoute('admin.users.index', navigate: true);
    }
}; ?>

<div class="py-12">
    <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800">
            <h1 class="mb-6 text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Edit User') }}</h1>
            <form wire:submit="save">
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input wire:model="form.name" id="name" class="mt-1 block w-full" type="text" required />
                    <x-input-error :messages="$errors->get('form.name')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input wire:model="form.email" id="email" class="mt-1 block w-full" type="email" required />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input wire:model="form.password" id="password" class="mt-1 block w-full" type="password" autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input wire:model="form.password_confirmation" id="password_confirmation" class="mt-1 block w-full" type="password" autocomplete="new-password" />
                </div>
                <div class="mt-4">
                    <x-input-label for="role" :value="__('Role')" />
                    <select wire:model="form.role" id="role" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-300">
                        @foreach (UserRole::cases() as $role)
                            <option value="{{ $role->value }}">{{ ucfirst($role->value) }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('form.role')" class="mt-2" />
                </div>
                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('admin.users.index') }}" wire:navigate class="text-sm text-gray-600">{{ __('Cancel') }}</a>
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
