<?php

namespace App\Livewire\Insider;

use Livewire\Component;
use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;

class MyCertificates extends Component
{
    public $certificates = [];

    public function mount()
    {
        if (Auth::user()->role !== 'insider') {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $this->certificates = Certificate::with('course')
            ->where('user_id', Auth::id())
            ->get();
    }

    public function render()
    {
        return view('livewire.insider.my-certificates');
    }
}
