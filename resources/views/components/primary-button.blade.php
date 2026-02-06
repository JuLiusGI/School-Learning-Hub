<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#1a4731] border border-transparent rounded-md font-semibold text-xs text-[#fefefe] uppercase tracking-widest hover:bg-[#143726] focus:bg-[#143726] active:bg-[#0f2a1e] focus:outline-none focus:ring-2 focus:ring-[#1a4731] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
