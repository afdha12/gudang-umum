<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 tracking-wide shadow-sm hover:bg-gray-50 hover:shadow focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 active:scale-95 transition-all ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
