<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-dvh h-dvh max-w-md mx-auto base-bg flex flex-col">
        {{-- <livewire:layout.navigation /> --}}

        <!-- Page Heading -->
        @if (isset($header))
            <header class="secondary-bg shadow  max-w-md mx-auto w-full">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class=" flex-1 overflow-y-auto max-w-md mx-auto w-full">
            {{ $slot }}
        </main>

        <div
            class=" max-w-md mx-auto w-full mt-auto flex justify-between items-center  secondary-bg light-text shadow-lg z-10 h-16">
            <x-bottom-nav-link icon="wallet" title="Countries" :active="request()->routeIs('country.*')" href="{{ route('country.index') }}" />
            <x-bottom-nav-link icon="wallet" title="Contacts" :active="request()->routeIs('contact.*')" href="{{ route('contact.index') }}" />
            <x-bottom-nav-link icon="wallet" title="Categories" :active="request()->routeIs('category.*')"
                href="{{ route('category.index') }}" />
            <x-bottom-nav-link icon="transactions" title="Transactions" :active="request()->routeIs('transaction.*')"
                href="{{ route('transaction.index') }}" />
            <x-bottom-nav-link icon="wallet" title="Wallets" :active="request()->routeIs('wallet.*')" href="{{ route('wallet.index') }}" />
        </div>
    </div>
</body>

</html>
