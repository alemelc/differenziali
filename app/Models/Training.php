<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    protected $casts = [
        'data_conseguimento' => 'date',
    ];
    
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
