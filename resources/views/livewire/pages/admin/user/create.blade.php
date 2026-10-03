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

    public function save(): void
    {
        $data = $this->form->validate();
        $data['password'] = Hash::make($data['password']);
        $data['email_verified_at'] = now();
        User::create($data);

        $this->redirectRoute('admin.users.index', navigate: true);
    }
}; ?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" wire:navigate class="inline-flex items-center gap-1 text-sm font-medium text-brand-600 hover:text-brand-700">
            &larr; {{ __('Back to Users') }}
        </a>
    </div>

    <div class="card p-6 sm:p-8">
        <h1 class="mb-6 text-lg font-semibold text-gray-900">{{ __('Create User') }}</h1>
        <form wire:submit="save" class="space-y-5">
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input wire:model="form.name" id="name" class="mt-1 block w-full" type="text" required />
                <x-input-error :messages="$errors->get('form.name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input wire:model="form.email" id="email" class="mt-1 block w-full" type="email" required />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input wire:model="form.password" id="password" class="mt-1 block w-full" type="password" required />
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input wire:model="form.password_confirmation" id="password_confirmation" class="mt-1 block w-full" type="password" required />
            </div>
            <div>
                <x-input-label for="role" :value="__('Role')" />
                <select wire:model="form.role" id="role" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    @foreach (UserRole::cases() as $role)
                        <option value="{{ $role->value }}">{{ ucfirst($role->value) }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('form.role')" class="mt-2" />
            </div>
            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                <a href="{{ route('admin.users.index') }}" wire:navigate class="btn-secondary">{{ __('Cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</div>
