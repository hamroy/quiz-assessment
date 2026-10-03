# TASK-031: Score Calculation

## Objective

Calculate a 0-100 percentage score from an attempt's persisted answers.

## Requirements

- Sum obtained points vs total possible points
- Percentage score, rounded to int
- ScoringService (no business logic in Blade/Livewire)

## Acceptance Criteria

- [x] Score = obtained/total * 100
- [x] Zero-division safe (no questions -> 0)
- [x] Feature tests pass

## Technical Notes

`ScoringService::score(QuizAttempt)`; used by `QuizAttemptService::submit()`.

## Files

- `app/Services/Assessment/ScoringService.php` (new)
- `tests/Feature/Public/QuizSubmitTest.php`

## Testing

- `php artisan test --filter=QuizSubmitTest`

## Status

Done

## GitHub Issue

#8
