<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Worker extends Model
{
    protected $fillable = ['name', 'whatsapp_number'];

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /** Nomor WhatsApp dalam format internasional tanpa tanda + (untuk wa.me). */
    public function normalizedNumber(): string
    {
        $number = preg_replace('/\D+/', '', $this->whatsapp_number);

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        return $number;
    }
}