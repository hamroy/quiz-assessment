# TASK-022: Create Answer Option

## Objective

Admin can add an answer option to a single-choice question, marking one option as correct.

## Requirements

- Create answer option scoped to a question
- Fields: Option Text, Is Correct (checkbox), Order (number, auto-filled)
- Order defaults to next available position
- Validation: option text required, order >= 1
- Redirect to question edit page after save
- Only authenticated admin

## Acceptance Criteria

- [x] Create answer option route exists under question scope
- [x] Form displays with auto-filled next order
- [x] Answer option is created with correct question_id, option_text, is_correct, order
- [x] is_correct defaults to false when omitted
- [x] Validation rejects missing option text
- [x] Redirects to question edit page after save
- [x] Unauthenticated users are redirected to login
- [x] Feature tests pass (6 AnswerOptionCreate tests)
- [x] "Add Answer Option" link added to question edit page

## Technical Notes

- Livewire 3 + Volt component
- `AnswerOption` model fillable: `question_id`, `option_text`, `is_correct`, `order`
- Service layer handles `create()` and `nextOrder()`
- Repository handles `create()` and `maxOrder()`

## Files

- `app/Repositories/Contracts/AnswerOptionRepositoryInterface.php` (new)
- `app/Repositories/AnswerOptionRepository.php` (new)
- `app/Services/AnswerOption/AnswerOptionService.php` (new)
- `app/Providers/AppServiceProvider.php`
- `routes/admin.php`
- `resources/views/livewire/pages/admin/answer-option/create.blade.php` (new)
- `resources/views/livewire/pages/admin/question/edit.blade.php`
- `tests/Feature/Admin/AnswerOptionCreateTest.php` (new)

## Testing

- `php artisan test --filter=AnswerOptionCreateTest`
- `php artisan test`

## Status

Done

## GitHub Issue

Pending creation (GitHub CLI needs authentication)
