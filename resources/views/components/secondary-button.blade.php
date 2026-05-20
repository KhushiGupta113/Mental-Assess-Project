<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 t-card border border-th-border-strong rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:t-surface focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>


