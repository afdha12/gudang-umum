@extends('errors.layout')

@section('title', 'Akses Ditolak')

@section('content')

    <!-- component -->
    <div
        class="lg:px-24 lg:py-24 md:py-20 md:px-44 px-4 py-24 items-center flex justify-center flex-col-reverse lg:flex-row md:gap-28 gap-16">
        <div class="xl:pt-24 w-full xl:w-1/2 relative pb-12 lg:pb-0">
            <div class="relative">
                <div class="absolute">
                    <div class="">
                        <h1 class="my-2 text-gray-800 font-bold text-2xl">
                            Oops! Anda tidak memiliki akses ke halaman ini.
                        </h1>
                        <p class="pb-4 my-2 text-gray-800">
                            Sepertinya Anda mencoba mengakses halaman yang tidak diperbolehkan.
                            Silakan kembali ke halaman sebelumnya atau pergi ke beranda.
                        </p>
                        <button onclick="window.history.back();"
                            class="sm:w-full lg:w-auto my-2 border rounded md py-4 px-8 text-center bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-700 focus:ring-opacity-50">
                            Kembali
                        </button>
                    </div>
                </div>
                <div>
                    <img src="{{ asset('assets/img/403.png') }}" alt="403 Forbidden" />
                </div>
            </div>
        </div>
        <div>
            <img src="{{ asset('assets/img/restriction.png') }}" alt="403 Illustration" />
        </div>
    </div>

@endsection
