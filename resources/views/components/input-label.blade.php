@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-[#1a4731]']) }}>
    {{ $value ?? $slot }}
</label>
