<?php

namespace App\Models;

use App\Enums\QuizType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'slug', 'description', 'type', 'status', 'published_at'])]
class Quiz extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return QuizType::tryFrom((string) $this->type)?->label()
            ?? ucfirst(str_replace('_', ' ', (string) $this->type));
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
