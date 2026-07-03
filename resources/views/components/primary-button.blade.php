<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide shadow-md hover:from-blue-500 hover:to-indigo-500 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:scale-95 transition-all ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
