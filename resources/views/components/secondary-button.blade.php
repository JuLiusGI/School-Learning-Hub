<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-[#fefefe] border border-[#1a4731] rounded-md font-semibold text-xs text-[#1a4731] uppercase tracking-widest shadow-sm hover:bg-[#f5e6cc] focus:outline-none focus:ring-2 focus:ring-[#1a4731] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
