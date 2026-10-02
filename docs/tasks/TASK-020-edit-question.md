# TASK-020: Edit Question

## Objective

Admin can edit an existing question's text, type, points, and order.

## Requirements

- Edit form pre-filled with existing question values
- Same validation as create (question required, points >= 1, valid type enum)
- Redirect to question list after save
- Only authenticated admin

## Acceptance Criteria

- [x] Edit question route exists scoped to quiz
- [x] Form displays with existing question values
- [x] Question updates are persisted to DB
- [x] Validation rejects missing question, points < 1, invalid type
- [x] Redirects to question list after save
- [x] Unauthenticated users redirected to login
- [x] Feature tests pass (6 QuestionEdit tests)
- [x] Edit link added to question index actions

## Technical Notes

- Livewire 3 + Volt component
- Mirrors Quiz Edit pattern: mount receives `int $quiz, int $question`, resolves via `QuestionService::findById()`
- Service `update()` delegates to repository `update()`

## Files

- `app/Repositories/Contracts/QuestionRepositoryInterface.php`
- `app/Repositories/QuestionRepository.php`
- `app/Services/Question/QuestionService.php`
- `routes/admin.php`
- `resources/views/livewire/pages/admin/question/edit.blade.php` (new)
- `resources/views/livewire/pages/admin/question/index.blade.php`
- `tests/Feature/Admin/QuestionEditTest.php` (new)

## Testing

- `php artisan test --filter=QuestionEditTest`
- `php artisan test`

## Status

Done

## GitHub Issue

Pending creation (GitHub CLI needs authentication)
