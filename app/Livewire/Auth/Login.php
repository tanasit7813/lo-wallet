<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';

    public function login()
    {
        $validated = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($validated)) {
            session()->regenerate();
            session()->flash('login_success', true);
            return redirect()->route('dashboard');
        }

        session()->flash('error', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง กรุณาลองใหม่');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
