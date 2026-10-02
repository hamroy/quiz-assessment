# TASK-019: Create Question

## Objective

Admin can create a new question for a specific quiz via a form.

## Requirements

- Create question scoped to a quiz
- Fields: Question (textarea), Type (select), Points (number), Order (number, auto-filled)
- Order defaults to next available position
- Validation: question required, points >= 1, valid type enum
- Redirect to question list after save
- Only authenticated admin

## Acceptance Criteria

- [x] Create question route exists under quiz scope
- [x] Form displays with auto-filled next order
- [x] Question is created with correct quiz_id, type, points, order
- [x] Validation rejects missing question, points < 1, invalid type
- [x] Redirects to question list after save
- [x] Unauthenticated users are redirected to login
- [x] Feature tests pass (8 QuestionCreate tests)
- [x] "Add Question" link added to question index page

## Technical Notes

- Livewire 3 + Volt component
- `QuestionType` enum with `SingleChoice` case
- Service layer handles `create()` and `nextOrder()`
- Repository handles `create()` and `maxOrder()`

## Files

- `app/Enums/QuestionType.php` (new)
- `app/Repositories/Contracts/QuestionRepositoryInterface.php`
- `app/Repositories/QuestionRepository.php`
- `app/Services/Question/QuestionService.php`
- `routes/admin.php`
- `resources/views/livewire/pages/admin/question/create.blade.php` (new)
- `resources/views/livewire/pages/admin/question/index.blade.php`
- `tests/Feature/Admin/QuestionCreateTest.php` (new)

## Testing

- `php artisan test --filter=QuestionCreateTest`
- `php artisan test --filter=QuestionListTest` (regression)

## Status

Done

## GitHub Issue

Pending creation (GitHub CLI needs authentication)
