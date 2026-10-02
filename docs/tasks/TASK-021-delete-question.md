# TASK-021: Delete Question

## Objective

Admin can delete a question from the question list.

## Requirements

- Delete button with confirmation in question list actions
- Question is removed from DB
- Other questions unaffected
- Only authenticated admin

## Acceptance Criteria

- [x] Delete button in question list actions column
- [x] Confirmation prompt before delete
- [x] Question is deleted from DB
- [x] Other questions remain intact
- [x] Feature tests pass (2 QuestionDelete tests)

## Technical Notes

- Inline delete in index Volt component (same pattern as quiz index)
- Service `delete()` delegates to repository `delete()`

## Files

- `app/Repositories/Contracts/QuestionRepositoryInterface.php`
- `app/Repositories/QuestionRepository.php`
- `app/Services/Question/QuestionService.php`
- `resources/views/livewire/pages/admin/question/index.blade.php`
- `tests/Feature/Admin/QuestionDeleteTest.php` (new)

## Testing

- `php artisan test --filter=QuestionDeleteTest`
- `php artisan test`

## Status

Done

## GitHub Issue

Pending creation (GitHub CLI needs authentication)
