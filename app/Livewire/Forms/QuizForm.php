<?php

namespace App\Livewire\Forms;

use App\Enums\QuizStatus;
use Illuminate\Validation\Rule;
use Livewire\Form;

class QuizForm extends Form
{
    public string $title = '';

    public string $description = '';

    public string $status = 'draft';

    public ?int $quizId = null;

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('quizzes', 'title')->ignore($this->quizId)],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(QuizStatus::class)],
        ];
    }
}
