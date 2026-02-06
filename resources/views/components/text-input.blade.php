@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-[#1a4731]/30 focus:border-[#1a4731] focus:ring-[#1a4731] rounded-md shadow-sm bg-[#fefefe] text-[#1a4731]']) }}>
