<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Report extends Model
{
    public const STATUS_PENDING  = 'Menunggu Verifikasi';
    public const STATUS_ACCEPTED = 'Diterima';
    public const STATUS_PROGRESS = 'Sedang Diperbaiki';
    public const STATUS_DONE     = 'Selesai';
    public const STATUS_REJECTED = 'Ditolak';

    /** Transisi status yang diizinkan. Selesai dan Ditolak bersifat final. */
    public const TRANSITIONS = [
        self::STATUS_PENDING  => [self::STATUS_ACCEPTED, self::STATUS_REJECTED],
        self::STATUS_ACCEPTED => [self::STATUS_PROGRESS],
        self::STATUS_PROGRESS => [self::STATUS_DONE],
    ];

    protected $fillable = [
        'ticket_number',
        'user_id',
        'category_id',
        'urgency_id',
        'worker_id',
        'location',
        'description',
        'photo',
        'status',
        'rejection_reason',
        'repair_note',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_ACCEPTED,
            self::STATUS_PROGRESS,
            self::STATUS_DONE,
            self::STATUS_REJECTED,
        ];
    }

    public static function generateTicketNumber(): string
    {
        do {
            $ticket = 'LK-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        } while (static::where('ticket_number', $ticket)->exists());

        return $ticket;
    }

    public function allowedTransitions(): array
    {
        return self::TRANSITIONS[$this->status] ?? [];
    }

    public function statusClasses(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING  => 'bg-yellow-100 text-yellow-800',
            self::STATUS_ACCEPTED => 'bg-blue-100 text-blue-800',
            self::STATUS_PROGRESS => 'bg-indigo-100 text-indigo-800',
            self::STATUS_DONE     => 'bg-green-100 text-green-800',
            self::STATUS_REJECTED => 'bg-red-100 text-red-800',
            default               => 'bg-gray-100 text-gray-800',
        };
    }

    public function photoUrl(): ?string
    {
        return $this->photo ? Storage::disk('public')->url($this->photo) : null;
    }

    /** Tautan wa.me berisi pesan penugasan, atau null jika petugas belum dipilih. */
    public function whatsappUrl(): ?string
    {
        if (! $this->worker) {
            return null;
        }

        $text = "Halo {$this->worker->name}, ada tugas perbaikan fasilitas sekolah.\n\n"
            . "Tiket: {$this->ticket_number}\n"
            . "Kategori: {$this->category->name}\n"
            . "Urgensi: {$this->urgency->name}\n"
            . "Lokasi: {$this->location}\n"
            . "Deskripsi: {$this->description}\n\n"
            . 'Mohon segera ditindaklanjuti. Terima kasih.';

        return 'https://wa.me/' . $this->worker->normalizedNumber() . '?text=' . rawurlencode($text);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function urgency(): BelongsTo
    {
        return $this->belongsTo(Urgency::class);
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ReportHistory::class)->orderBy('created_at')->orderBy('id');
    }
}