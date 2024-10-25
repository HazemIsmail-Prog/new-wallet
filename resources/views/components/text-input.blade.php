@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full secondary-bg border-none light-text focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md']) }}>
