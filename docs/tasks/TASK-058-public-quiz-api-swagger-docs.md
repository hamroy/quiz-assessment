# TASK-058: Public Quiz API & Swagger Documentation

## Objective

Expose a read-only public API for published quizzes and document it with Swagger UI.

## Requirements

- Register an `api` route group (`routes/api.php`) with `/api/v1` prefix.
- `GET /api/v1/quizzes` — paginated list of published quizzes, filterable by `type` and `per_page`.
- `GET /api/v1/quizzes/{slug}` — published quiz metadata plus question count.
- Public endpoints: no authentication, only `published` quizzes are exposed.
- API documentation generated from code via `dedoc/scramble` (OpenAPI 3.1).
- Swagger UI available at `/docs/api`, spec at `/docs/api.json`.
- Machine-readable error payloads for validation and not-found.

## Acceptance Criteria

- [x] `/api/v1/quizzes` returns paginated published quizzes only.
- [x] `type` query filter works and is documented (rendered as the `QuizType` enum in the spec).
- [x] `per_page` is bounded to 1–50 and rejected outside that range.
- [x] `/api/v1/quizzes/{slug}` returns a published quiz; 404 otherwise.
- [x] Correct answers (`is_correct`) are never exposed, in the API or the spec.
- [x] `/docs/api` renders Swagger UI; `/docs/api.json` returns the OpenAPI spec.
- [x] Docs are readable by admins outside `local`; forbidden for everyone else.
- [x] Tests pass (142).

## Technical Notes

- `bootstrap/app.php` adds `api:` routing; this installs the stateless `api` middleware group (no session/CSRF).
- Scramble documents routes matching `api_path` (`api`). Path stripping means `/api/v1/quizzes` appears as `/v1/quizzes` under server `/api`.
- A dedicated repository method (`QuizRepository::listPublishedFiltered`) avoids N+1 and keeps the paginator size configurable per request instead of the hardcoded `12` used by the Blade views.
- **Gotcha:** `Quiz::$type` and `Quiz::$status` are **not** enum-cast. Cast the value through `QuizType::tryFrom()` explicitly rather than relying on `->label()` alone (the model accessor already does the fallback).
- **Gotcha:** Scramble's `#[Response(type: ...)]` overrides collapse resource schemas to bare `object`/`string`. Let Scramble infer the type from the return signature instead and use the attribute only for descriptions/status codes.
- Scramble resolves the docs through `Route::enum()` (`Rule::enum(QuizType::class)`), so the spec documents `type` as the real enum without extra config.
- `Scramble::restricted` defaults to local-only. `AppServiceProvider` defines the `viewApiDocs` gate for admins so the docs also work on the deployed host.
- `php artisan scramble:export` hits the DB, so it needs a reachable connection even though the docs are code-generated.

## Files

- `bootstrap/app.php`
- `routes/api.php`
- `app/Http/Controllers/Api/QuizController.php`
- `app/Http/Resources/QuizResource.php`
- `app/Repositories/QuizRepository.php`
- `app/Repositories/Contracts/QuizRepositoryInterface.php`
- `app/Providers/AppServiceProvider.php`
- `config/scramble.php`
- `composer.json`, `composer.lock`
- `tests/Feature/Api/PublicQuizApiTest.php`

## Testing

- `php artisan test`
- `vendor/bin/pint --test` on the changed files

## Status

Done

## GitHub Issue

#20