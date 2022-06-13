<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\User;

use App\Http\Resources\Checks;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = Expense::where('user_id', User::me()->sub);

        $request->whenFilled('beginAt', function ($beginAt) use ($expenses) {
            $expenses->where('created_at', '>=', $beginAt);
        });
        $request->whenFilled('endAt', function ($endAt) use ($expenses) {
            $expenses->where('created_at', '<=', $endAt . ' 00:00:00');
        });

        return Checks::collection($expenses->get())
            ->additional([
                'meta' => [
                    'sum' => $expenses->sum('amount'),
                    'count' => $expenses->count(),
                ]
            ]);
    }

    public function store(Request $request)
    {
        $claimEncoded = explode('.', $request->bearerToken())[1];
        $claim = json_decode(base64_decode($claimEncoded));

        $expense = new Expense();
        $expense->user_id = $claim->sub;
        $expense->amount = $request->amount;
        $expense->description = $request->description;
        $expense->save();

        return response()->json([
            'data' => $expense
        ]);
    }
}
