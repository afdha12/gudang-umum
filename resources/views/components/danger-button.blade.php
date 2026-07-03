<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide shadow-md hover:from-red-500 hover:to-rose-500 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:scale-95 transition-all ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
