<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\users\create.blade.php -->
@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Create New User</h1>
        <a href="{{ route('admin.users.index') }}" class="neo-btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Users
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="neo-card">
                <div class="card-header">
                    <h5 class="mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <x-form-input
                            name="name"
                            label="Full Name"
                            placeholder="Enter user's full name"
                            required
                        />

                        <x-form-input
                            name="email"
                            label="Email Address"
                            type="email"
                            placeholder="Enter user's email address"
                            required
                        />

                        <x-form-input
                            name="password"
                            label="Password"
                            type="password"
                            placeholder="Enter strong password"
                            required
                        />

                        <x-form-input
                            name="password_confirmation"
                            label="Confirm Password"
                            type="password"
                            placeholder="Confirm password"
                            required
                        />

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">User Role</label>
                                <div class="form-check mb-2" style="padding-left: 30px;">
                                    <input class="form-check-input" style="border: 2px solid #212529; width: 20px; height: 20px;" type="radio" name="is_admin" id="role_user" value="0" checked>
                                    <label class="form-check-label ms-2" for="role_user">
                                        Regular User
                                    </label>
                                </div>
                                <div class="form-check" style="padding-left: 30px;">
                                    <input class="form-check-input" style="border: 2px solid #212529; width: 20px; height: 20px;" type="radio" name="is_admin" id="role_admin" value="1">
                                    <label class="form-check-label ms-2" for="role_admin">
                                        Administrator
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <x-form-input
                                    name="token_balance"
                                    label="Initial Token Balance"
                                    type="number"
                                    placeholder="0"
                                    value="10"
                                    min="0"
                                />
                            </div>
                        </div>

                        <div class="form-check mb-4" style="padding-left: 30px;">
                            <input class="form-check-input" style="border: 2px solid #212529; width: 20px; height: 20px;" type="checkbox" name="is_active" id="is_active" value="1" checked>
                            <label class="form-check-label ms-2" for="is_active">
                                Account Active
                            </label>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="reset" class="neo-btn btn-secondary me-2">
                                <i class="fas fa-redo me-2"></i> Reset
                            </button>
                            <button type="submit" class="neo-btn">
                                <i class="fas fa-user-plus me-2"></i> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
