<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\users\index.blade.php -->
@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">User Management</h1>
        <a href="{{ route('admin.users.create') }}" class="neo-btn">
            <i class="fas fa-user-plus me-2"></i> Add New User
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="neo-card mb-4" style="padding: 12px;">
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Search</label>
                    <div class="input-group">
                        <input type="text" name="search" class="neo-form-control" placeholder="Search by name or email" value="{{ request('search') }}">
                        <button class="neo-btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-3">
                    <x-form-select
                        name="status"
                        label="Status"
                        :options="[
                            '' => 'All Statuses',
                            'active' => 'Active',
                            'inactive' => 'Inactive'
                        ]"
                        selected="{{ request('status') }}"
                    />
                </div>

                <div class="col-md-3">
                    <x-form-select
                        name="sort"
                        label="Sort By"
                        :options="[
                            'newest' => 'Newest First',
                            'oldest' => 'Oldest First',
                            'name_asc' => 'Name (A-Z)',
                            'name_desc' => 'Name (Z-A)',
                            'downloads' => 'Most Downloads',
                            'tokens' => 'Most Tokens'
                        ]"
                        selected="{{ request('sort', 'newest') }}"
                    />
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="neo-btn w-100">
                        <i class="fas fa-filter me-2"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Users List -->
    <div class="neo-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Users</h5>
            <span class="badge bg-primary" style="border: 2px solid #121212; padding: 8px 15px;">
                {{ $users->total() }} Users Found
            </span>
        </div>
        <div class="card-body p-0">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Tokens</th>
                                <th>Downloads</th>
                                <th>Registered</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ff4b2b&color=fff" alt="{{ $user->name }}" class="rounded-circle me-2" width="40" height="40">
                                            <div>
                                                <div class="fw-bold">{{ $user->name }}</div>
                                                <div class="small text-muted">{{ $user->is_admin ? 'Administrator' : 'User' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->token_balance }}</td>
                                    <td>{{ $user->downloads_count }}</td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <x-status-badge :status="$user->is_active ? 'active' : 'inactive'" />
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" title="View User">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.users.downloads', $user) }}" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Downloads">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="{{ route('admin.users.activities', $user) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Activities">
                                                <i class="fas fa-history"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}" title="Delete User">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete User Modal -->
                                <x-modal id="deleteUserModal{{ $user->id }}" title="Delete User">
                                    <p>Are you sure you want to delete user <strong>{{ $user->name }}</strong>?</p>
                                    <p>This action cannot be undone and will delete all associated downloads and activity logs.</p>

                                    <x-slot name="footer">
                                        <button type="button" class="btn neo-btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn neo-btn">Delete User</button>
                                        </form>
                                    </x-slot>
                                </x-modal>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center py-3">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @else
                <x-empty-state
                    title="No Users Found"
                    message="No users match your search criteria."
                    icon="fas fa-users"
                    action="true"
                    actionLink="{{ route('admin.users.index') }}"
                    actionText="Clear Filters"
                />
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
