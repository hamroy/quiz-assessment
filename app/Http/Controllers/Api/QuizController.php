<?php

namespace App\Http\Controllers\Api;

use App\Enums\QuizType;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuizResource;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Public read-only quiz catalogue, served under /api/v1.
 */
#[Group(name: 'Quizzes', description: 'Published quiz catalogue. No authentication required.')]
class QuizController extends Controller
{
    private const PER_PAGE = 12;

    /**
     * List published quizzes.
     *
     * Newest first. Only quizzes with `published` status are visible; the correct
     * answer for any question is never included.
     */
    #[Response(
        status: 200,
        description: 'Paginated collection of published quizzes. Besides `data`, the payload carries the standard `links` and `meta` objects from Laravel.',
    )]
    #[Response(
        status: 422,
        description: 'Validation failed for the supplied query parameters.',
    )]
    public function index(Request $request, QuizRepositoryInterface $quizzes): AnonymousResourceCollection
    {
        $validated = Validator::make($request->query(), [
            'type' => ['nullable', 'string', Rule::enum(QuizType::class)],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ])->validate();

        return QuizResource::collection(
            $quizzes->listPublishedFiltered(
                $validated['type'] ?? null,
                min(max((int) ($validated['per_page'] ?? self::PER_PAGE), 1), 50),
            )->withQueryString()
        );
    }

    /**
     * Get a single published quiz by slug.
     *
     * Includes metadata and the question count only — question text and answer
     * options are not exposed here.
     */
    #[Response(
        status: 200,
        description: 'The published quiz.',
    )]
    #[Response(
        status: 404,
        description: 'No published quiz exists with the given slug.',
    )]
    public function show(string $slug, QuizRepositoryInterface $quizzes): QuizResource
    {
        return new QuizResource(
            $quizzes->findPublishedBySlug($slug)->loadCount('questions')
        );
    }
}
