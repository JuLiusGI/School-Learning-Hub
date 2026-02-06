@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#1a4731] text-start text-base font-medium text-[#1a4731] bg-[#f5e6cc] focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-[#1a4731]/80 hover:text-[#1a4731] hover:bg-[#f5e6cc] hover:border-[#f5e6cc] focus:outline-none focus:text-[#1a4731] focus:bg-[#f5e6cc] focus:border-[#f5e6cc] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
