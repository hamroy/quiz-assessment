<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('home', absolute: false), navigate: true);
    }
}; ?>

<div>
    <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ __('Create an account') }}</h1>
    <p class="mt-1 text-sm text-gray-600">{{ __('Start your self-understanding journey.') }}</p>

    <form wire:submit="register" class="mt-6">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
            <input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name"
                class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
            <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"
                class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>

            <input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password"
                class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('Confirm Password') }}</label>

            <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit" class="btn-primary w-full">
                {{ __('Register') }}
            </button>
        </div>

        <div class="mt-4 text-center text-sm">
            <a class="text-brand-600 hover:text-brand-700" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>
        </div>
    </form>
</div>
