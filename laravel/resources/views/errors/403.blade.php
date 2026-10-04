<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Access denied (403)
        </h2>
    </x-slot>
    <p class="mt-2 text-sm text-gray-500">
        Signed in as: {{ auth()->user()->email }}
    </p>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-700">
                     This page is for administrators only.
                     If you believe this is an error, please contact an administrator.
                </p>

                <div class="mt-4 flex gap-4">
                   <a class="underline" href="{{ route('dashboard') }}">← Back to dashboard</a>
                   <a class="underline" href="{{ route('products.index') }}">View products</a>
                </div>


            </div>
        </div>
    </div>
</x-app-layout>
