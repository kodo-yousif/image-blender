@extends('layout')

@section('content')
<div class="card p-4 shadow-sm">
    <h3 class="mb-3">👤 Manage Users</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3 text-end">
        <a href="{{ route('admin.create') }}" class="btn btn-primary">➕ Create New User</a>
    </div>

    <table class="table table-bordered align-middle text-center">
    <thead>
        <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Expires At</th>
            <th>Remaining Days</th>
            <th>Created</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $u)
        <tr>
            <td>{{ $u->username }}</td>
            <td>{{ ucfirst($u->role) }}</td>
            <td>{{ $u->expires_at?->format('Y/m/d') ?? 'Unlimited' }}</td>
            <td>
                @if(!$u->expires_at)
                    ∞
                @elseif($u->expires_at->isPast())
                    <span class="text-danger">Expired</span>
                @else
                    @php
                        $diff = now()->diff($u->expires_at);
                        $months = $diff->m + ($diff->y * 12);
                        $days = $diff->d;
                        $hours = $diff->h;
                    @endphp

                    @if($months > 0)
                        {{ $months }} {{ Str::plural('Month', $months) }}
                        @if($days > 0)
                            and {{ $days }} {{ Str::plural('Day', $days) }}
                        @endif
                    @elseif($days > 0)
                        {{ $days }} {{ Str::plural('Day', $days) }}
                    @else
                        {{ $hours }} {{ Str::plural('Hour', $hours) }}
                    @endif
                @endif
            </td>
            <td>{{ $u->created_at->format('Y/m/d') }}</td>
            <td>
                @if(!$u->expires_at || $u->expires_at->isFuture())
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Expired</span>
                @endif
            </td>
            <td>
            @if($u->role !== 'admin')
                @if(!$u->expires_at || $u->expires_at->isFuture())
                    <form action="{{ route('admin.users.expire', $u->id) }}" method="POST" onsubmit="return confirm('Expire this user now?')">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Expire Now</button>
                    </form>
                @else
                    <span class="text-muted">—</span>
                @endif
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        </tr>
        @endforeach
    </tbody>
</table>

    <div class="d-flex justify-content-center mt-3">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
