<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WalletAuthController extends Controller
{
    public function getNonce()
    {
        $nonce = Str::random(32);
        session(['wallet_nonce' => $nonce]);

        return response()->json(['nonce' => $nonce]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'signature' => 'required|string',
            'nonce' => 'required|string',
        ]);

        if ($request->input('nonce') !== session('wallet_nonce')) {
            return back()->withErrors(['wallet' => 'Nonce invalide.']);
        }

        $address = strtolower($request->input('address'));
        $user = User::where('wallet_address', $address)->first();

        if (! $user) {
            $user = User::create([
                'name' => 'Wallet ' . substr($address, 0, 8),
                'email' => $address . '@wallet.local',
                'password' => bcrypt(Str::random(16)),
                'wallet_address' => $address,
                'role' => 'manager',
            ]);
        }

        Auth::login($user);
        session()->forget('wallet_nonce');

        return response()->json(['redirect' => route('dashboard')]);
    }
}
