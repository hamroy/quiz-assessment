# TASK-026: Quiz Detail

## Objective

Public quiz detail page showing a published assessment's title, description, and question count, with a Start Assessment button.

## Requirements

- Route `/quizzes/{slug}`
- Only published quizzes viewable (draft/archived return 404)
- Show title, description, question count
- Start Assessment button (attempt flow lands in a later task)
- Link from home quiz cards

## Acceptance Criteria

- [x] Published quiz detail is viewable by slug
- [x] Draft quiz returns 404
- [x] Archived quiz returns 404
- [x] Unknown slug returns 404
- [x] Title, description, question count shown
- [x] Feature tests pass (4 QuizDetailTest tests)

## Technical Notes

- Follows QuizService/QuizRepository pattern
- `QuizRepository::findPublishedBySlug()` filters by `QuizStatus::Published` and throws `ModelNotFoundException` (→ 404)
- Start button is a placeholder (`#`) until the attempt flow lands

## Files

- `app/Repositories/Contracts/QuizRepositoryInterface.php`
- `app/Repositories/QuizRepository.php`
- `app/Services/Quiz/QuizService.php`
- `routes/public.php`
- `resources/views/livewire/pages/public/quiz-detail.blade.php` (new)
- `resources/views/livewire/pages/public/home.blade.php`
- `tests/Feature/Public/QuizDetailTest.php` (new)

## Testing

- `php artisan test --filter=QuizDetailTest`
- `php artisan test`

## Status

Done

## GitHub Issue

#3
