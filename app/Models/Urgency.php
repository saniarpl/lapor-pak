<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Urgency extends Model
{
    protected $table = 'urgencies';

    protected $fillable = ['name', 'level'];

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}