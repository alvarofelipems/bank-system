<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\User;

use App\Http\Resources\Checks;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $incomes = new Income();

        $incomes = $request->whenHas('userId', function ($userId) use ($incomes) {
            // collect(User::me()->realm_access->roles)->contains('admin')
            if ($userId == '*') {
                return $incomes;
            }
            return $incomes->where('user_id', $userId);
        }, function () use ($incomes) {
            return $incomes->where('user_id', User::me()->sub);
        });

        $request->whenFilled('beginAt', function ($beginAt) use ($incomes) {
            $incomes->where('created_at', '>=', $beginAt);
        });
        $request->whenFilled('endAt', function ($endAt) use ($incomes) {
            $incomes->where('created_at', '<=', $endAt . ' 23:59:59');
        });

        $request->whenFilled('status', function ($status) use ($incomes) {
            $incomes->where('status', $status);
        });

        return Checks::collection($incomes->latest('created_at')->get())
            ->additional([
                'meta' => [
                    'sum' => $incomes->where('status', 'approved')->sum('amount'),
                    'count' => $incomes->count(),
                ]
            ]);;
    }

    public function store(Request $request)
    {
        $user = User::me();

        $income = new Income();
        $income->user_id = $user->sub;
        $income->amount = $request->amount;
        $income->description = $request->description;
        $income->save();

        return response()->json([
            'data' => $income
        ]);
    }

    public function update(Request $request, $income)
    {
        $income = Income::findOrFail($income);
        $income->status = $request->status;
        $income->save();

        return response()->json([
            'data' => $income
        ]);
    }
}
