<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class AnswerOptionForm extends Form
{
    public string $option_text = '';

    public bool $is_correct = false;

    public int $order = 1;

    protected function rules(): array
    {
        return [
            'option_text' => ['required', 'string'],
            'is_correct' => ['boolean'],
            'order' => ['required', 'integer', 'min:1'],
        ];
    }
}
