<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\users\edit.blade.php -->
@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Edit User</h1>
        <a href="{{ route('admin.users.show', $user) }}" class="neo-btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to User Profile
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="neo-card">
                <div class="card-header">
                    <h5 class="mb-0">Edit User Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <x-form-input
                            name="name"
                            label="Full Name"
                            placeholder="Enter user's full name"
                            :value="$user->name"
                            required
                        />

                        <x-form-input
                            name="email"
                            label="Email Address"
                            type="email"
                            placeholder="Enter user's email address"
                            :value="$user->email"
                            required
                        />

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <div class="border p-3" style="border: 2px dashed #ccc !important; border-radius: 8px;">
                                <div class="fw-bold mb-2">Change Password (Optional)</div>
                                <p class="text-muted small mb-3">Leave blank if you don't want to change the password</p>

                                <x-form-input
                                    name="password"
                                    label="New Password"
                                    type="password"
                                    placeholder="Enter new password"
                                />

                                <x-form-input
                                    name="password_confirmation"
                                    label="Confirm New Password"
                                    type="password"
                                    placeholder="Confirm new password"
                                />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">User Role</label>
                                <div class="form-check mb-2" style="padding-left: 30px;">
                                    <input class="form-check-input" style="border: 2px solid #212529; width: 20px; height: 20px;" type="radio" name="is_admin" id="role_user" value="0" {{ $user->is_admin ? '' : 'checked' }}>
                                    <label class="form-check-label ms-2" for="role_user">
                                        Regular User
                                    </label>
                                </div>
                                <div class="form-check" style="padding-left: 30px;">
                                    <input class="form-check-input" style="border: 2px solid #212529; width: 20px; height: 20px;" type="radio" name="is_admin" id="role_admin" value="1" {{ $user->is_admin ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2" for="role_admin">
                                        Administrator
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <x-form-input
                                    name="token_balance"
                                    label="Token Balance"
                                    type="number"
                                    placeholder="0"
                                    :value="$user->token_balance"
                                    min="0"
                                />
                            </div>
                        </div>

                        <div class="form-check mb-4" style="padding-left: 30px;">
                            <input class="form-check-input" style="border: 2px solid #212529; width: 20px; height: 20px;" type="checkbox" name="is_active" id="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}>
                            <label class="form-check-label ms-2" for="is_active">
                                Account Active
                            </label>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.users.show', $user) }}" class="neo-btn btn-secondary me-2">
                                Cancel
                            </a>
                            <button type="submit" class="neo-btn">
                                <i class="fas fa-save me-2"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
