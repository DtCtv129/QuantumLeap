<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oeuvre extends Model
{
    use HasFactory;
    protected $fillable = [
        'titre'
    ];
    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
