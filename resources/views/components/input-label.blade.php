@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-[10px] uppercase tracking-[.18em] text-[#17351d]']) }}>
    {{ $value ?? $slot }}
</label>
