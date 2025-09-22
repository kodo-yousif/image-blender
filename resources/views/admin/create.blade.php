@extends('layout')

@section('content')
<div class="col-md-6 mx-auto">
    <div class="card p-4 shadow-sm">
        <h3 class="mb-4">➕ Create User</h3>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Expiry Date</label>
                <input type="date" name="expires_at" class="form-control">
                <small class="text-muted">Leave empty for unlimited access</small>
            </div>

            <input type="hidden" name="role" value="user">

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">← Back</a>
                <button type="submit" class="btn btn-success">Create</button>
            </div>
        </form>
    </div>
</div>
@endsection
