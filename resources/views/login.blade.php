@extends('layout')

@section('body-class', 'login-page')

@section('content')

<div class="col-md-4 mx-auto mt-5">
    <div class="text-center mb-4">
        <img src="{{ asset('images/logo.png') }}" alt="Hair AI Logo" style="height:100px;">
        <h3 class="mt-2 fw-bold">Hair AI</h3>
        <p class="text-muted">KRD Intelligent</p>
    </div>

    <div class="card p-4 shadow-sm login-card">
        <h5 class="mb-3 text-center">🔐 Login</h5>

        @if(session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</div>
@endsection
