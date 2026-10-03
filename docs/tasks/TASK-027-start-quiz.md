# TASK-027: Start Quiz

## Objective

User starts a published quiz, creating an in-progress attempt and landing on the quiz-taking page.

## Requirements

- Start action on quiz detail page
- Create `QuizAttempt` with status `in_progress`, `started_at` now, `user_id` nullable
- Redirect to quiz-taking route
- Guard: quiz must be published and have questions
- No login required (guest attempts allowed)

## Acceptance Criteria

- [x] Starting creates an in-progress attempt
- [x] Redirects to taking page
- [x] Draft/archived quiz cannot be started (DomainException)
- [x] Feature tests pass

## Technical Notes

- `QuizAttemptStatus` enum (`in_progress`, `submitted`)
- `QuizAttemptRepository` + `QuizAttemptService::start()`
- `QuizAttemptService::answer()` also added (shared with TASK-028)
- `user_id` nullable for guest attempts

## Files

- `app/Enums/QuizAttemptStatus.php` (new)
- `app/Repositories/Contracts/QuizAttemptRepositoryInterface.php` (new)
- `app/Repositories/QuizAttemptRepository.php` (new)
- `app/Repositories/Contracts/QuizAnswerRepositoryInterface.php` (new)
- `app/Repositories/QuizAnswerRepository.php` (new)
- `app/Services/Assessment/QuizAttemptService.php` (new)
- `app/Providers/AppServiceProvider.php`
- `routes/public.php`
- `resources/views/livewire/pages/public/quiz-detail.blade.php`
- `tests/Feature/Public/QuizAttemptTest.php` (new)

## Testing

- `php artisan test --filter=QuizAttemptTest`
- `php artisan test`

## Status

Done

## GitHub Issue

#4
