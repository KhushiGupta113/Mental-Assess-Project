<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-nature inline-flex items-center justify-center border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-th-primary focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

