<?php

namespace App\Livewire\Officer;

use App\Models\Director;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SignatureManagement extends Component
{
    use WithPagination;

    public $name, $position, $startdate, $directorId;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'startdate' => 'required|date',
    ];

    public function mount()
    {
        // ตรวจสอบ role
        if (Auth::user()->role !== 'officer') {
            abort(403, 'Unauthorized');
        }
    }

    public function create()
    {
        $this->validate();

        Director::create([
            'name' => $this->name,
            'position' => $this->position,
            'startdate' => $this->startdate,
        ]);

        $this->resetForm();
        session()->flash('message', 'เพิ่มข้อมูลลายเซ็นสำเร็จ');
    }

    public function edit($id)
    {
        $director = Director::findOrFail($id);
        $this->directorId = $id;
        $this->name = $director->name;
        $this->position = $director->position;
        $this->startdate = $director->startdate;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        Director::find($this->directorId)->update([
            'name' => $this->name,
            'position' => $this->position,
            'startdate' => $this->startdate,
        ]);

        $this->resetForm();
        session()->flash('message', 'แก้ไขข้อมูลลายเซ็นสำเร็จ');
    }

    public function delete($id)
    {
        Director::find($id)->delete();
        session()->flash('message', 'ลบข้อมูลลายเซ็นสำเร็จ');
    }

    public function resetForm()
    {
        $this->name = '';
        $this->position = '';
        $this->startdate = '';
        $this->isEditing = false;
        $this->directorId = null;
    }

    public function render()
    {
        $directors = Director::paginate(10); // ดึงข้อมูลจากตาราง directors
        return view('livewire.officer.signature-management', [
            'directors' => $directors,
        ]);
    }
}
