<?php

namespace App\Livewire\Forms;

use App\Enums\QuestionType;
use Illuminate\Validation\Rule;
use Livewire\Form;

class QuestionForm extends Form
{
    public string $question = '';

    public string $type = QuestionType::SingleChoice->value;

    public int $points = 1;

    public int $order = 1;

    protected function rules(): array
    {
        return [
            'question' => ['required', 'string'],
            'type' => ['required', Rule::enum(QuestionType::class)],
            'points' => ['required', 'integer', 'min:1'],
            'order' => ['required', 'integer', 'min:1'],
        ];
    }
}
