<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insider extends Model
{
    protected $table = 'insiders';
    protected $fillable = ['user_id', 'insider_role'];

    // ความสัมพันธ์ BelongsTo กับ users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
