<?php

namespace App\Models;

use App\Events\ChirpCreated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Chirp extends Model
{
    use HasFactory;

    protected $fillable = ['message'];

    protected $dispatchesEvents = [
        'created' => ChirpCreated::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch(Builder $query, mixed $search): Builder
    {
        if(is_string($search)) {
            $search = Str::of($search)->trim()
                ->explode(' ')
                ->map(fn($word) => '+' . $word . '*')
                ->join(' ');

            return $query->whereRaw('MATCH(message) AGAINST(? IN BOOLEAN MODE)', [$search])
                ->orderByRaw('MATCH(message) AGAINST(? IN BOOLEAN MODE) DESC', [$search]);
        }

        return $query;
    }
}
