<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // import service class using construct
    public function __construct(private TransactionService $transactionService) {

    }

    public function store(TransactionRequest $request) {
        $data = $request->validated();

        $transaction = $this->transactionService->create(Auth::user()->id, $data);

        return response()->json([
            'message' => 'Transaction successfully created',
            'data' => $transaction->load('room')
        ]);
    }
//     public function store(Request $request)
// {
//     return response()->json(['message' => 'masuk store'], 200);
// }
}
