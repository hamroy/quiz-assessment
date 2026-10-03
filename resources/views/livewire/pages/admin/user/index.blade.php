<?php

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component
{
    use WithPagination;

    public string $error = '';

    public function delete(int $userId): void
    {
        $user = User::findOrFail($userId);

        if ($user->is(auth()->user())) {
            $this->error = __('You cannot delete your own account.');

            return;
        }

        $user->delete();
        $this->error = '';
    }

    public function with(): array
    {
        return ['users' => User::query()->orderBy('name')->paginate(15)];
    }
}; ?>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Users') }}</h1>
                <a href="{{ route('admin.users.create') }}" wire:navigate class="rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">{{ __('Create User') }}</a>
            </div>

            @if ($error)
                <p class="mb-4 text-sm text-red-600">{{ $error }}</p>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead><tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">{{ __('Name') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">{{ __('Email') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">{{ __('Role') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">{{ __('Actions') }}</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-4 py-3">{{ $user->name }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">{{ ucfirst($user->role->value) }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.users.edit', $user) }}" wire:navigate class="text-indigo-600">{{ __('Edit') }}</a>
                                    <button type="button" wire:click="delete({{ $user->id }})" wire:confirm="{{ __('Delete this user?') }}" class="ms-3 text-red-600">{{ __('Delete') }}</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">{{ __('No users found.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $users->links() }}</div>
        </div>
    </div>
</div>
