<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#ff6b6b] border border-transparent rounded-md font-semibold text-xs text-[#1a4731] uppercase tracking-widest hover:bg-[#ff5252] active:bg-[#ff3d3d] focus:outline-none focus:ring-2 focus:ring-[#ff6b6b] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
