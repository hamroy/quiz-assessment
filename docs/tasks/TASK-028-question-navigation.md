# TASK-028: Question Navigation

## Objective

Quiz-taking page: navigate questions one at a time (prev/next), select an answer option, persist the answer with server-determined correctness/points.

## Requirements

- Route `quizzes/{slug}/attempts/{attempt}`
- Show one question at a time with progress (Question X of Y)
- Prev/Next navigation
- Select single answer option per question
- Persist answer (server determines is_correct and points from the option)
- Do not leak correct answers to the client

## Acceptance Criteria

- [x] Taking page renders in-progress attempt only (submitted → 404)
- [x] Progress indicator shown (Question X of Y + bar)
- [x] Navigation works (prev/next)
- [x] Answer selection persists and re-selects on navigation
- [x] Correctness computed server-side (not client)
- [x] Feature tests pass (5 QuizAttemptTest tests)

## Technical Notes

- `QuizAnswerRepository::upsert()` + `QuizAttemptService::answer()`
- Options rendered server-side; Livewire state holds only `selected` option ids
- `is_correct`/`points` computed from the option + question, never from client
- Submit button is a placeholder until submission task lands

## Files

- `resources/views/livewire/pages/public/take-quiz.blade.php` (new)
- `app/Services/Assessment/QuizAttemptService.php`
- `app/Repositories/QuizAnswerRepository.php` (new)
- `routes/public.php`
- `tests/Feature/Public/QuizAttemptTest.php` (new)

## Testing

- `php artisan test --filter=QuizAttemptTest`
- `php artisan test`

## Status

Done

## GitHub Issue

#5
