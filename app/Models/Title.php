<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    protected $casts = [
        'data_conseguimento' => 'date',
        'attinente' => 'boolean',
    ];
    
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
