@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-[10px] uppercase tracking-[0.2em] text-stone-400 dark:text-stone-500']) }}>
    {{ $value ?? $slot }}
</label>