<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    protected $table = 'generals';
    protected $fillable = ['user_id', 'position', 'agency'];

    // ความสัมพันธ์ BelongsTo กับ users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
