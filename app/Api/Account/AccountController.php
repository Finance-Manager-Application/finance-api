<?php

namespace App\Api\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;

class AccountController extends Controller
{

    public function index(Request $request)
    {
        $user_id = $request->input('user_id');
        $accounts = Account::whereHas('user', function ($query) use ($user_id) {
            $query->where('id', $user_id);
        })
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $accounts
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'current_balance' => 'required|numeric',
            'icon' => 'nullable|string|max:255'
        ]);

        $latestAccount = Account::where('user_id', $validated['user_id'])->orderBy('account_id', 'desc')->first();
        $nextAccountId = $latestAccount ? $latestAccount->account_id + 1 : 1;

        $user = User::find($validated['user_id']);
        $account = $user->accounts()->create([
            'name' => $validated['name'],
            'current_balance' => $validated['current_balance'],
            'icon' => $validated['icon'],
            'account_id' => $nextAccountId
        ]);

        return response()->json(['message' => 'Account created successfully', 'account' => $account], 201);
    }

    public function show($user_id, $account_id)
    {
        $account = Account::where('account_id', $account_id)
            ->whereHas('user', function ($query) use ($user_id) {
                $query->where('id', $user_id);
            })
            ->first();

        if (!$account) {
            return response()->json([
                'status' => 'error',
                'message' => 'Account not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $account,
        ]);
    }

    public function update(Request $request, $user_id, $account_id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'current_balance' => 'required|numeric',
            'icon' => 'nullable|string|max:255'
        ]);

        $account = Account::where('account_id', $account_id)
            ->whereHas('user', function ($query) use ($user_id) {
                $query->where('id', $user_id);
            })
            ->first();

        if (!$account) {
            return response()->json([
                'status' => 'error',
                'message' => 'Account not found',
            ], 404);
        }

        $account->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Account updated successfully',
            'data' => $account,
        ]);
    }
}
