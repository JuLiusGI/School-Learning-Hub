@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#1a4731] text-sm font-medium leading-5 text-[#1a4731] focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[#1a4731]/80 hover:text-[#1a4731] hover:border-[#f5e6cc] focus:outline-none focus:text-[#1a4731] focus:border-[#f5e6cc] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
