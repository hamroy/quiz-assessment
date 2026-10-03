<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $destination = auth()->user()->isAdmin() ? route('dashboard') : route('home');

        $this->redirectIntended(default: $destination, navigate: true);
    }
}; ?>

<div>
    <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ __('Welcome back') }}</h1>
    <p class="mt-1 text-sm text-gray-600">{{ __('Log in to continue your assessment.') }}</p>

    <!-- Session Status -->
    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form wire:submit="login" class="mt-6">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
            <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username"
                class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>

            <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password"
                class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                    class="rounded border-gray-300 text-brand-600 shadow-sm focus:ring-brand-500">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn-primary w-full">
                {{ __('Log in') }}
            </button>
        </div>

        <div class="mt-4 flex items-center justify-between text-sm">
            @if (Route::has('password.request'))
                <a class="text-brand-600 hover:text-brand-700" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            @if (Route::has('register'))
                <a class="text-brand-600 hover:text-brand-700" href="{{ route('register') }}" wire:navigate>
                    {{ __('Create an account') }}
                </a>
            @endif
        </div>
    </form>
</div>
