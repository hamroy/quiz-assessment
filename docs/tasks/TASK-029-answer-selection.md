# TASK-029: Answer Selection

## Objective

User selects a single answer option per question during an attempt.

## Requirements

- One answer option selectable per question
- Selection reflected in UI
- Server determines correctness (never client)

## Acceptance Criteria

- [x] Single answer selectable per question
- [x] Selection UI (radio) works
- [x] Correctness computed server-side

## Technical Notes

Delivered as part of TASK-028 (`selectOption` + radio UI in `take-quiz.blade.php`). Options rendered server-side; client only sends `(question_id, answer_option_id)`.

## Files

- `resources/views/livewire/pages/public/take-quiz.blade.php`

## Testing

- `php artisan test --filter=QuizAttemptTest`

## Status

Done

## GitHub Issue

#6
