@extends('layouts.main')

@section('title', 'Profile')

@section('content')
    <div class="container-fluid max-w-7xl mx-auto mt-4 px-4">
        <div class="row gap-4 mb-4">
            <div class="col-12 bg-white shadow p-4 rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <div class="row gap-4 mb-4">
            <div class="col-12 bg-white shadow p-4 rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <div class="row gap-4 mb-4">
            <div class="col-12 bg-white shadow p-4 rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
