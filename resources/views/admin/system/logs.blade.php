<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\system\logs.blade.php -->
@extends('layouts.admin')

@section('title', 'System Logs')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">System Logs</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.system.settings') }}" class="neo-btn btn-secondary">
                <i class="fas fa-cog me-2"></i> System Settings
            </a>
            <a href="{{ route('admin.system.info') }}" class="neo-btn btn-secondary">
                <i class="fas fa-info-circle me-2"></i> System Info
            </a>
            <a href="{{ route('admin.dashboard') }}" class="neo-btn btn-secondary">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Log Files List -->
        <div class="col-md-4">
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i> Log Files
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if(isset($logFilesList) && count($logFilesList) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($logFilesList as $file)
                                <a href="{{ route('admin.system.view-log', $file['name']) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                                    {{ isset($filename) && $filename === $file['name'] ? 'active' : '' }}">
                                    <div>
                                        <i class="fas fa-file-code me-2"></i>
                                        {{ $file['name'] }}
                                        <small class="d-block text-muted">{{ $file['modified'] }}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary me-2" style="border: 1px solid #212529;">
                                            {{ $file['size'] }}
                                        </span>
                                        <form action="{{ route('admin.system.clear-log', $file['name']) }}" method="POST" onsubmit="return confirm('Are you sure you want to clear this log file?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Clear Log">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <i class="fas fa-file-alt fa-2x mb-3 text-muted"></i>
                            <p class="mb-0">No log files found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Log Content -->
        <div class="col-md-8">
            <div class="neo-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-code me-2"></i>
                        {{ isset($filename) ? $filename : 'laravel.log' }}
                    </h5>
                    @if(!empty($logContents))
                        <div>
                            <button id="toggleFilters" class="neo-btn btn-sm">
                                <i class="fas fa-filter me-1"></i> Filters
                            </button>
                            <button id="scrollToBottom" class="neo-btn btn-sm ms-1">
                                <i class="fas fa-arrow-down me-1"></i> Bottom
                            </button>
                            <button id="scrollToTop" class="neo-btn btn-sm ms-1">
                                <i class="fas fa-arrow-up me-1"></i> Top
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Filters -->
                <div id="logFilters" class="card-body border-bottom" style="display: none; background-color: #f8f9fa; border-bottom: 2px solid #212529;">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Log Level</label>
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="log-filter-btn neo-btn btn-sm" data-level="emergency">Emergency</button>
                                <button class="log-filter-btn neo-btn btn-sm" data-level="alert">Alert</button>
                                <button class="log-filter-btn neo-btn btn-sm" data-level="critical">Critical</button>
                                <button class="log-filter-btn neo-btn btn-sm" data-level="error">Error</button>
                                <button class="log-filter-btn neo-btn btn-sm" data-level="warning">Warning</button>
                                <button class="log-filter-btn neo-btn btn-sm" data-level="notice">Notice</button>
                                <button class="log-filter-btn neo-btn btn-sm" data-level="info">Info</button>
                                <button class="log-filter-btn neo-btn btn-sm" data-level="debug">Debug</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Search</label>
                            <input type="text" id="logSearch" class="neo-form-control" placeholder="Search in logs...">
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if(!empty($logContents))
                        <div id="logContent" class="log-container">
                            <pre class="mb-0"><code>{{ $logContents }}</code></pre>
                        </div>
                    @else
                        <div class="p-5 text-center">
                            <i class="fas fa-file-alt fa-3x mb-3 text-muted"></i>
                            <h5>No Log Data Available</h5>
                            <p class="mb-3">The log file is empty or does not exist.</p>
                            <a href="{{ route('admin.system.maintenance') }}" class="neo-btn">
                                <i class="fas fa-tools me-2"></i> Go to Maintenance
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .neo-card {
        border: 3px solid #212529;
        border-radius: 8px;
        box-shadow: 5px 5px 0 rgba(0, 0, 0, 0.2);
        background: #ffffff;
        overflow: hidden;
    }

    .neo-card .card-header {
        border-bottom: 2px solid #212529;
        padding: 1rem;
    }

    .neo-btn {
        display: inline-block;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        user-select: none;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border: 2px solid #212529;
        border-radius: 0.375rem;
        box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.2);
        transition: transform 0.1s, box-shadow 0.1s;
        cursor: pointer;
        text-decoration: none;
        color: #212529;
    }

    .neo-btn:hover {
        transform: translate(-1px, -1px);
        box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.2);
    }

    .neo-btn:active {
        transform: translate(1px, 1px);
        box-shadow: 2px 2px 0 rgba(0, 0, 0, 0.2);
    }

    .neo-btn.btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .neo-btn.btn-secondary {
        background: #f8f9fa;
    }

    .neo-form-control {
        border: 2px solid #212529;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);
        width: 100%;
    }

    .neo-form-control:focus {
        border-color: #ff4b2b;
        box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.2);
        outline: none;
    }

    .log-container {
        background-color: #212529;
        color: #f8f9fa;
        max-height: 700px;
        overflow-y: auto;
        padding: 1rem;
    }

    .log-container pre {
        color: #f8f9fa;
        margin: 0;
    }

    /* Log highlighting */
    .log-line-emergency { color: #ff5252; font-weight: bold; }
    .log-line-alert { color: #ff9800; font-weight: bold; }
    .log-line-critical { color: #f44336; font-weight: bold; }
    .log-line-error { color: #e91e63; }
    .log-line-warning { color: #ffeb3b; }
    .log-line-notice { color: #8bc34a; }
    .log-line-info { color: #2196f3; }
    .log-line-debug { color: #9e9e9e; }

    .log-line-hidden { display: none; }
    .log-search-highlight { background-color: yellow; color: black; }

    .log-filter-btn.active {
        background: #212529;
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle filters display
        const toggleFilters = document.getElementById('toggleFilters');
        const logFilters = document.getElementById('logFilters');

        if (toggleFilters && logFilters) {
            toggleFilters.addEventListener('click', function() {
                logFilters.style.display = logFilters.style.display === 'none' ? 'block' : 'none';
            });
        }

        // Scroll controls
        const scrollToBottom = document.getElementById('scrollToBottom');
        const scrollToTop = document.getElementById('scrollToTop');
        const logContent = document.getElementById('logContent');

        if (scrollToBottom && logContent) {
            scrollToBottom.addEventListener('click', function() {
                logContent.scrollTop = logContent.scrollHeight;
            });
        }

        if (scrollToTop && logContent) {
            scrollToTop.addEventListener('click', function() {
                logContent.scrollTop = 0;
            });
        }

        // Process log content for highlighting
        const logLines = document.querySelectorAll('#logContent pre code');
        if (logLines.length) {
            const logContent = logLines[0].innerHTML;
            let processedContent = '';

            // Split by lines
            const lines = logContent.split('\n');

            lines.forEach(line => {
                let className = 'log-line';

                if (line.includes('[emergency]')) className += ' log-line-emergency';
                else if (line.includes('[alert]')) className += ' log-line-alert';
                else if (line.includes('[critical]')) className += ' log-line-critical';
                else if (line.includes('[error]')) className += ' log-line-error';
                else if (line.includes('[warning]')) className += ' log-line-warning';
                else if (line.includes('[notice]')) className += ' log-line-notice';
                else if (line.includes('[info]')) className += ' log-line-info';
                else if (line.includes('[debug]')) className += ' log-line-debug';

                processedContent += `<div class="${className}">${line}</div>`;
            });

            logLines[0].innerHTML = processedContent;
        }

        // Log filters
        const filterButtons = document.querySelectorAll('.log-filter-btn');
        const activeFilters = new Set();

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const level = this.dataset.level;

                if (activeFilters.has(level)) {
                    activeFilters.delete(level);
                    this.classList.remove('active');
                } else {
                    activeFilters.add(level);
                    this.classList.add('active');
                }

                applyFilters();
            });
        });

        // Search functionality
        const searchInput = document.getElementById('logSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                applyFilters();
            });
        }

        function applyFilters() {
            const searchTerm = searchInput.value.toLowerCase();
            const logLines = document.querySelectorAll('.log-line');

            logLines.forEach(line => {
                // Remove previous highlighting
                line.innerHTML = line.innerHTML.replace(/<mark class="log-search-highlight">|<\/mark>/g, '');

                // Reset visibility
                line.classList.remove('log-line-hidden');

                // Apply level filters
                if (activeFilters.size > 0) {
                    let matchesLevel = false;

                    activeFilters.forEach(level => {
                        if (line.classList.contains(`log-line-${level}`)) {
                            matchesLevel = true;
                        }
                    });

                    if (!matchesLevel) {
                        line.classList.add('log-line-hidden');
                        return;
                    }
                }

                // Apply search filter
                if (searchTerm) {
                    const lineText = line.textContent.toLowerCase();

                    if (!lineText.includes(searchTerm)) {
                        line.classList.add('log-line-hidden');
                    } else {
                        // Highlight search term
                        const regex = new RegExp(searchTerm, 'gi');
                        line.innerHTML = line.innerHTML.replace(regex, match => `<mark class="log-search-highlight">${match}</mark>`);
                    }
                }
            });
        }
    });
</script>
@endpush
