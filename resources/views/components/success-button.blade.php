<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide shadow-md hover:from-emerald-400 hover:to-teal-400 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 active:scale-95 transition-all ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
