@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'border-stone-200 bg-white text-stone-600 focus:border-black focus:ring-black rounded-none shadow-sm transition duration-300 placeholder-stone-300'
]) !!}>