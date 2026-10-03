# TASK-032: Submit Assessment

## Objective

Submit an in-progress attempt atomically: evaluate, calculate score, mark submitted, show result.

## Requirements

- Submit action on last question
- DB transaction: evaluate -> score -> update attempt
- Set status submitted, submitted_at, score
- Prevent resubmission
- Redirect to result page

## Acceptance Criteria

- [x] Submit marks attempt submitted with score
- [x] Atomic transaction
- [x] Submitted attempt cannot be resubmitted (DomainException)
- [x] Result page shows score
- [x] In-progress attempt cannot view result (404)
- [x] Feature tests pass

## Technical Notes

`QuizAttemptService::submit()` wraps scoring + update in `DB::transaction`. `pages/public/result` requires `submitted` status. `QuizAttemptRepository::update()` added.

## Files

- `app/Services/Assessment/QuizAttemptService.php`
- `app/Repositories/QuizAttemptRepository.php` + contract
- `routes/public.php`
- `resources/views/livewire/pages/public/take-quiz.blade.php`
- `resources/views/livewire/pages/public/result.blade.php` (new)
- `tests/Feature/Public/QuizSubmitTest.php` (new)

## Testing

- `php artisan test --filter=QuizSubmitTest`
- `php artisan test`

## Status

Done

## GitHub Issue

#9
