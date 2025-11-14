<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Welcome — The Paranoia</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="antialiased bg-gray-50 text-gray-800">

<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="text-xl font-semibold text-indigo-600">The Paranoia</a>
                <div class="hidden md:flex ml-10 space-x-4">
                    <a href="#features" class="text-gray-600 hover:text-indigo-600">Features</a>
                    <a href="#pricing" class="text-gray-600 hover:text-indigo-600">Pricing</a>
                    <a href="#contact" class="text-gray-600 hover:text-indigo-600">Contact</a>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/admin') }}" class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-md border border-indigo-600 text-indigo-600 text-sm">Get started</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>

<header class="bg-white">
    <div class="max-w-7xl mx-auto px-4 py-16 text-center">
        <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900">Build confidence. Reduce risk.</h1>
        <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">A lightweight platform to manage customers, workflows, and notifications — securely and privately.</p>
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('register') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700">Get started</a>
            <a href="#features" class="px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100">Learn more</a>
        </div>
    </div>
</header>

<main class="max-w-7xl mx-auto px-4 pb-16">
    <section id="features" class="mt-12 grid gap-8 md:grid-cols-3">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-50 text-indigo-600">
                <!-- Icon: Secure -->
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.657 1.343-3 3-3h0a3 3 0 013 3v2a5 5 0 01-5 5H9a5 5 0 01-5-5v-2a3 3 0 013-3h0c1.657 0 3 1.343 3 3"></path></svg>
            </div>
            <h3 class="mt-4 text-lg font-medium">Privacy-first</h3>
            <p class="mt-2 text-sm text-gray-600">Designed to store only what you need and keep access tightly controlled.</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-50 text-indigo-600">
                <!-- Icon: Automate -->
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0v6a4 4 0 01-4 4H8a4 4 0 01-4-4V7"></path></svg>
            </div>
            <h3 class="mt-4 text-lg font-medium">Automations</h3>
            <p class="mt-2 text-sm text-gray-600">Trigger emails, reminders, and tasks with a few clicks — save time and reduce errors.</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-50 text-indigo-600">
                <!-- Icon: Insights -->
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3v2m0 14v2m8-10h2M3 11H1m16.95 6.95l1.41 1.41M4.64 4.64L3.22 3.22m12.02 0l1.41 1.41M4.64 19.36l-1.41 1.41"></path></svg>
            </div>
            <h3 class="mt-4 text-lg font-medium">Actionable insights</h3>
            <p class="mt-2 text-sm text-gray-600">Understand customer health, churn risk, and opportunities with clear dashboards.</p>
        </div>
    </section>

    <section id="pricing" class="mt-16">
        <div class="text-center">
            <h2 class="text-2xl font-semibold">Simple pricing</h2>
            <p class="mt-2 text-gray-600">One predictable monthly price. Free trial available.</p>
        </div>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium">Starter</h3>
                <p class="mt-2 text-3xl font-extrabold">$0<span class="text-base font-medium">/mo</span></p>
                <p class="mt-4 text-sm text-gray-600">Up to 3 users, basic features, community support.</p>
                <a href="{{ route('register') }}" class="mt-6 inline-block px-4 py-2 bg-indigo-600 text-white rounded">Start free</a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow border-2 border-indigo-50">
                <h3 class="text-lg font-medium">Pro</h3>
                <p class="mt-2 text-3xl font-extrabold">$29<span class="text-base font-medium">/mo</span></p>
                <p class="mt-4 text-sm text-gray-600">Team seats, automations, email support.</p>
                <a href="{{ route('register') }}" class="mt-6 inline-block px-4 py-2 bg-indigo-600 text-white rounded">Get Pro</a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium">Enterprise</h3>
                <p class="mt-2 text-3xl font-extrabold">Custom</p>
                <p class="mt-4 text-sm text-gray-600">Advanced security, SSO, dedicated support.</p>
                <a href="#contact" class="mt-6 inline-block px-4 py-2 border border-gray-300 rounded text-gray-700">Contact sales</a>
            </div>
        </div>
    </section>

    <section id="contact" class="mt-16">
        <div class="bg-gradient-to-r from-indigo-50 to-white p-8 rounded-lg">
            <div class="max-w-3xl mx-auto text-center">
                <h3 class="text-xl font-semibold">Questions or custom needs?</h3>
                <p class="mt-2 text-gray-600">Reach out and we'll get back to you within one business day.</p>
                <a href="mailto:hello@theparanoia.example" class="mt-4 inline-block px-5 py-3 bg-white border border-indigo-200 rounded shadow">hello@theparanoia.example</a>
            </div>
        </div>
    </section>
</main>

<footer class="bg-white border-t">
    <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col md:flex-row justify-between items-center">
        <p class="text-sm text-gray-600">&copy; {{ date('Y') }} The Paranoia. All rights reserved.</p>
        <div class="mt-4 md:mt-0 space-x-4">
            <a href="#" class="text-sm text-gray-600 hover:text-indigo-600">Privacy</a>
            <a href="#" class="text-sm text-gray-600 hover:text-indigo-600">Terms</a>
        </div>
    </div>
</footer>

</body>
</html>