@props([
    'title' => 'Are you sure?',
    'message' => '',
    'variant' => 'danger',
    'confirmLabel' => 'Confirm',
    'cancelLabel' => 'Cancel',
])

@php
$variantClasses = match ($variant) {
    'danger' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
    'warning' => 'bg-yellow-500 hover:bg-yellow-600 focus:ring-yellow-500',
    'primary' => 'bg-brand-600 hover:bg-brand-700 focus:ring-brand-500',
    'info' => 'bg-sky-600 hover:bg-sky-700 focus:ring-sky-500',
    default => 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
};
@endphp

<div
    x-data
    x-show="$store.confirm.open"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="confirm-title"
>
    <div
        class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75 transition-opacity"
        x-show="$store.confirm.open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="$store.confirm.cancel()"
    ></div>

    <div class="flex min-h-full items-center justify-center p-4">
        <div
            class="w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden transform transition-all"
            x-show="$store.confirm.open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        >
            <div class="p-6">
                <h3
                    id="confirm-title"
                    class="text-lg font-medium text-gray-900 dark:text-gray-100"
                    x-text="$store.confirm.title"
                ></h3>

                <div class="mt-2">
                    <p
                        class="text-sm text-gray-600 dark:text-gray-400"
                        x-text="$store.confirm.message"
                    ></p>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-100 dark:bg-gray-700 flex justify-end gap-3">
                <button
                    type="button"
                    x-on:click="$store.confirm.cancel()"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                >{{ $cancelLabel }}</button>

                <button
                    type="button"
                    x-on:click="$store.confirm.confirm()"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 {{ $variantClasses }}"
                >{{ $confirmLabel }}</button>
            </div>
        </div>
    </div>
</div>
