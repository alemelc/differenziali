<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplinaryProceeding extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    protected $casts = [
        'data' => 'date',
    ];
    
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
