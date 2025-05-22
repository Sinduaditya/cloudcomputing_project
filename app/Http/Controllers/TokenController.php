<?php
// app/Http/Controllers/TokenController.php
namespace App\Http\Controllers;

use App\Models\TokenTransaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function index()
    {
        $transactions = auth()->user()->tokenTransactions()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tokens.history', compact('transactions'));
    }
}
