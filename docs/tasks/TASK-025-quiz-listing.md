# TASK-025: Quiz Listing

## Objective

Public home page lists published assessments as cards with title, question count, and a Start Assessment button.

## Requirements

- List only published quizzes (hide draft/archived)
- Show title and question count per card
- Start Assessment button on each card
- Mobile-first responsive grid, empty state, pagination

## Acceptance Criteria

- [x] Only published quizzes appear
- [x] Draft and archived quizzes are hidden
- [x] Question count is shown per card
- [x] Empty state shown when no published quizzes
- [x] No N+1 queries
- [x] Feature tests pass (4 QuizListingTest tests)

## Technical Notes

- Follows QuizService/QuizRepository pattern
- `QuizRepository::listPublished()` filters by `QuizStatus::Published`
- `withCount('questions')` avoids N+1
- Start button is a placeholder (`#`) until quiz detail task lands

## Files

- `app/Repositories/Contracts/QuizRepositoryInterface.php`
- `app/Repositories/QuizRepository.php`
- `app/Services/Quiz/QuizService.php`
- `resources/views/livewire/pages/public/home.blade.php`
- `tests/Feature/Public/QuizListingTest.php` (new)

## Testing

- `php artisan test --filter=QuizListingTest`
- `php artisan test`

## Status

Done

## GitHub Issue

#2
