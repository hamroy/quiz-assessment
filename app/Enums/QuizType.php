<?php

namespace App\Enums;

enum QuizType: string
{
    case General = 'general';
    case Stress = 'stress';
    case Anxiety = 'anxiety';
    case Mbti = 'mbti';
    case Disc = 'disc';
    case BigFive = 'big_five';

    public function label(): string
    {
        return match ($this) {
            self::General => 'General',
            self::Stress => 'Stress',
            self::Anxiety => 'Anxiety',
            self::Mbti => 'MBTI',
            self::Disc => 'DISC',
            self::BigFive => 'Big Five',
        };
    }
}
