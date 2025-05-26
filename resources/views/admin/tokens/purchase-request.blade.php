<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\tokens\purchase-requests.blade.php -->

@extends('layouts.admin')

@section('title', 'Token Purchase Requests')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Token Purchase Requests</h1>
        <a href="{{ route('admin.tokens.index') }}" class="neo-btn btn-secondary">
            <i class="fas fa-coins me-2"></i> Token Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #FF9800, #FF5722);">
                    <i class="fas fa-clock fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ $stats['pending_requests'] }}</h3>
                    <p class="mb-0">Pending Requests</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #4CAF50, #8BC34A);">
                    <i class="fas fa-check fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ $stats['approved_requests'] }}</h3>
                    <p class="mb-0">Approved</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #f44336, #FF5722);">
                    <i class="fas fa-times fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ $stats['rejected_requests'] }}</h3>
                    <p class="mb-0">Rejected</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #9C27B0, #673AB7);">
                    <i class="fas fa-money-bill-wave fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">Rp {{ number_format($stats['total_pending_value'], 0, ',', '.') }}</h3>
                    <p class="mb-0">Pending Value</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="neo-card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="status" class="form-select neo-form-control">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="user_search" class="form-control neo-form-control" placeholder="Search user..." value="{{ request('user_search') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control neo-form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control neo-form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="neo-btn w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="neo-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Package</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($request->user->name) }}&size=32&background=ff4b2b&color=fff"
                                        class="rounded-circle me-2" style="width: 32px; height: 32px;">
                                    <div>
                                        <div class="fw-bold">{{ $request->user->name }}</div>
                                        <small class="text-muted">{{ $request->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $request->package_name }}</div>
                                <small class="text-muted">{{ number_format($request->token_amount) }} tokens</small>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $request->formatted_price }}</div>
                                @if($request->discount > 0)
                                <span class="badge bg-success">{{ $request->discount }}% OFF</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ ucwords(str_replace('_', ' ', $request->payment_method)) }}</div>
                                @if($request->payment_proof)
                                <a href="{{ Storage::url($request->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="fas fa-image"></i> View Proof
                                </a>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $request->status_badge }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                                @if($request->processed_by)
                                <br><small class="text-muted">by {{ $request->processedBy->name }}</small>
                                @endif
                            </td>
                            <td>
                                <div>{{ $request->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $request->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                @if($request->canBeProcessed())
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-success"
                                            onclick="showApproveModal({{ $request->id }}, '{{ $request->user->name }}', {{ $request->token_amount }})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                            onclick="showRejectModal({{ $request->id }}, '{{ $request->user->name }}')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @else
                                <span class="text-muted">Processed</span>
                                @if($request->admin_notes)
                                <br><small class="text-muted" title="{{ $request->admin_notes }}">{{ Str::limit($request->admin_notes, 20) }}</small>
                                @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No purchase requests found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{ $requests->links() }}
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Purchase Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to approve this purchase request?</p>
                    <div class="alert alert-info">
                        <strong id="approveUserName"></strong> will receive <strong id="approveTokens"></strong> tokens.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Admin Notes (Optional)</label>
                        <textarea name="admin_notes" class="form-control" rows="3" placeholder="Any notes about this approval..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Approve Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Purchase Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to reject this purchase request from <strong id="rejectUserName"></strong>?</p>
                    <div class="mb-3">
                        <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea name="admin_notes" class="form-control" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showApproveModal(requestId, userName, tokens) {
    document.getElementById('approveUserName').textContent = userName;
    document.getElementById('approveTokens').textContent = tokens;
    document.getElementById('approveForm').action = `/admin/tokens/purchase-requests/${requestId}/approve`;
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}

function showRejectModal(requestId, userName) {
    document.getElementById('rejectUserName').textContent = userName;
    document.getElementById('rejectForm').action = `/admin/tokens/purchase-requests/${requestId}/reject`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
@endpush
