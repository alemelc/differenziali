<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Le credenziali non sono corrette.',
        ]);
    }
    
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'matricola' => 'required|unique:users',
            // Anagrafica fields
            'area_appartenenza' => 'required',
            'profilo_attuale' => 'required',
            'nato_a' => 'required',
            'data_nascita' => 'required|date',
            'residente_a' => 'required',
            'via' => 'required',
            'cap' => 'required',
            'prov' => 'required',
            'codice_fiscale' => 'required',
            'telefono' => 'required'
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        
        $user = User::create($validated);
        
        Auth::login($user);
        
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
