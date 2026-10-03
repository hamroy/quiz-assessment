# TASK-030: Answer Persistence

## Objective

Persist the selected answer for a question, with server-determined is_correct and points.

## Requirements

- Upsert answer per (attempt, question)
- is_correct and points computed from the option + question
- Do not trust client for correctness/points

## Acceptance Criteria

- [x] Answer persisted/updated on re-selection
- [x] is_correct/points set server-side
- [x] Feature tests pass

## Technical Notes

Delivered as part of TASK-028: `QuizAnswerRepository::upsert()` + `QuizAttemptService::answer()`.

## Files

- `app/Repositories/QuizAnswerRepository.php`
- `app/Services/Assessment/QuizAttemptService.php`

## Testing

- `php artisan test --filter=QuizAttemptTest`

## Status

Done

## GitHub Issue

#7
