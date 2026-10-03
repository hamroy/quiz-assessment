@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 bg-white text-gray-900 focus:border-brand-500 focus:ring-brand-500 rounded-lg shadow-sm']) }}>
