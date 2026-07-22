<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        session()->flash('logout_success', true);
        return $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.logout');
    }
}
