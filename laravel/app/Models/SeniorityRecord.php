<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeniorityRecord extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    protected $casts = [
        'dal' => 'date',
        'al' => 'date',
        'tempo_pieno' => 'boolean',
        'tempo_parziale' => 'boolean',
    ];
    
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
