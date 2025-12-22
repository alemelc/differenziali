<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    protected $casts = [
        'submission_date' => 'datetime',
        'temp_pieno' => 'boolean',
        'tempo_parziale' => 'boolean',
        'declaration_two_years' => 'boolean',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function disciplinaryProceedings()
    {
        return $this->hasMany(DisciplinaryProceeding::class);
    }
    
    public function seniorityRecords()
    {
        return $this->hasMany(SeniorityRecord::class);
    }
    
    public function titles()
    {
        return $this->hasMany(Title::class);
    }
    
    public function trainings()
    {
        return $this->hasMany(Training::class);
    }
}
