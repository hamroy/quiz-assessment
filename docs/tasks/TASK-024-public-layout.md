# TASK-024: Public Layout

## Objective

Create a public-facing layout shell with header navigation and footer, replacing the default Laravel welcome page at the root URL.

## Requirements

- Public layout with header (brand link to home, auth-aware nav: Dashboard when logged in, Log in when guest)
- Main content slot for page content
- Footer with copyright
- Root URL `/` serves through the public layout
- Mobile-first responsive design

## Acceptance Criteria

- [x] Public layout exists at `layouts/public.blade.php`
- [x] Header shows brand name and auth-aware links
- [x] Footer displays copyright with brand name
- [x] `/` route renders through public layout
- [x] Guest sees "Log in" link; authenticated user sees "Dashboard" link
- [x] Feature tests pass (2 PublicHomeTest tests)

## Technical Notes

- Layout mirrors the pattern of `layouts/app.blade.php` (admin) and `layouts/guest.blade.php` (auth)
- Public home page is a minimal Volt component with placeholder content; quiz listing lands in a later task
- Nav is static (no Livewire component needed) — uses `@auth`/`@else` directives

## Files

- `resources/views/layouts/public.blade.php` (new)
- `resources/views/livewire/pages/public/home.blade.php` (new)
- `routes/public.php` (new)
- `routes/web.php` (modified)
- `tests/Feature/Public/PublicHomeTest.php` (new)

## Testing

- `php artisan test --filter=PublicHomeTest`
- `php artisan test`

## Status

Done

## GitHub Issue

#1
