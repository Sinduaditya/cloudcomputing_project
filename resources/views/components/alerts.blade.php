<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\components\alerts.blade.php -->
@if(session('success'))
    <div class="alert alert-success" style="border: 3px solid #121212; border-radius: 8px; box-shadow: 5px 5px 0 rgba(0,0,0,0.3);" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" style="border: 3px solid #121212; border-radius: 8px; box-shadow: 5px 5px 0 rgba(0,0,0,0.3);" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning" style="border: 3px solid #121212; border-radius: 8px; box-shadow: 5px 5px 0 rgba(0,0,0,0.3);" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info" style="border: 3px solid #121212; border-radius: 8px; box-shadow: 5px 5px 0 rgba(0,0,0,0.3);" role="alert">
        <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger" style="border: 3px solid #121212; border-radius: 8px; box-shadow: 5px 5px 0 rgba(0,0,0,0.3);" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> Please check the form for errors
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
