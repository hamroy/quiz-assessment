<?php

namespace Tests\Feature\Admin;

use App\Enums\QuizType;
use App\Models\Quiz;
use App\Models\User;
use App\Services\Assessment\QuizAttemptService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class AssessmentResultReportTest extends TestCase
{
    use RefreshDatabase;

    private function submittedAttempt(User $user, Quiz $quiz, int $score = 80, ?string $submittedAt = null): int
    {
        $attempt = app(QuizAttemptService::class)->start($quiz, $user);

        $attempt->update([
            'status' => 'submitted',
            'score' => $score,
            'submitted_at' => $submittedAt ?? now(),
        ]);

        return $attempt->id;
    }

    private function publishedQuiz(string $title, string $type = 'general'): Quiz
    {
        $quiz = Quiz::factory()->create(['status' => 'published', 'title' => $title, 'type' => $type]);

        $question = $quiz->questions()->create([
            'question' => 'First question?',
            'type' => 'single_choice',
            'points' => 5,
            'order' => 1,
        ]);
        $question->answerOptions()->createMany([
            ['option_text' => 'Correct A', 'is_correct' => true, 'order' => 1],
            ['option_text' => 'Wrong A', 'is_correct' => false, 'order' => 2],
        ]);

        return $quiz;
    }

    private function exportRows(array $query = []): array
    {
        $response = $this->get(route('admin.results.export', $query));

        $response->assertOk();

        $path = tempnam(sys_get_temp_dir(), 'xlsx');
        file_put_contents($path, $response->streamedContent());

        $sheet = IOFactory::load($path)->getActiveSheet();
        $highestRow = $sheet->getHighestDataRow();
        $rows = $highestRow < 2 ? [] : $sheet->rangeToArray('A1:F'.$highestRow);
        unlink($path);

        return array_slice($rows, 1);
    }

    public function test_admin_list_shows_only_submitted_attempts(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $user = User::factory()->create(['name' => 'Budi Santoso']);
        $quiz = $this->publishedQuiz('Stress Level', 'stress');

        $this->submittedAttempt($user, $quiz, 72);

        // In-progress attempt must not appear.
        app(QuizAttemptService::class)->start($quiz, $user);

        Volt::test('pages.admin.result.index')
            ->assertOk()
            ->assertSee('Budi Santoso')
            ->assertSee($user->email)
            ->assertSee('Stress Level')
            ->assertSee(QuizType::Stress->label())
            ->assertSee('72%')
            ->assertViewHas('attempts', fn ($attempts) => $attempts->total() === 1);
    }

    public function test_search_filters_by_name_and_by_email(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $quiz = $this->publishedQuiz('Any Quiz');
        $budi = User::factory()->create(['name' => 'Budi Santoso', 'email' => 'budi@mail.com']);
        $sari = User::factory()->create(['name' => 'Sari Wijaya', 'email' => 'sari@mail.com']);

        $this->submittedAttempt($budi, $quiz);
        $this->submittedAttempt($sari, $quiz);

        Volt::test('pages.admin.result.index')
            ->set('search', 'Budi')
            ->assertSee('Budi Santoso')
            ->assertDontSee('Sari Wijaya');

        Volt::test('pages.admin.result.index')
            ->set('search', 'sari@mail')
            ->assertSee('Sari Wijaya')
            ->assertDontSee('Budi Santoso');
    }

    public function test_quiz_filter_narrows_results(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $user = User::factory()->create(['name' => 'Budi Santoso']);
        $stress = $this->publishedQuiz('Stress Level', 'stress');
        $disc = $this->publishedQuiz('DISC Profile', 'disc');

        $this->submittedAttempt($user, $stress, 70);
        $this->submittedAttempt($user, $disc, 95);

        // Both quizzes stay listed in the filter dropdown; only the rows are filtered.
        Volt::test('pages.admin.result.index')
            ->set('quizId', (string) $stress->id)
            ->assertSee('Stress Level')
            ->assertSee('70%')
            ->assertDontSee('95%')
            ->assertViewHas('attempts', fn ($attempts) => $attempts->total() === 1);
    }

    public function test_date_range_filter_excludes_attempts_outside_range(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $user = User::factory()->create(['name' => 'Budi Santoso']);
        $quiz = $this->publishedQuiz('Any Quiz');

        $this->submittedAttempt($user, $quiz, 80, '2026-09-01 10:00:00');
        $this->submittedAttempt($user, $quiz, 90, '2026-09-20 10:00:00');

        Volt::test('pages.admin.result.index')
            ->set('dateFrom', '2026-09-15')
            ->set('dateTo', '2026-09-30')
            ->assertSee('90%')
            ->assertDontSee('80%')
            ->assertViewHas('attempts', fn ($attempts) => $attempts->total() === 1);
    }

    public function test_changing_a_filter_resets_pagination(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $quiz = $this->publishedQuiz('Any Quiz');

        foreach (range(1, 16) as $i) {
            $this->submittedAttempt(User::factory()->create(['name' => 'Taker '.$i]), $quiz, $i);
        }

        Volt::test('pages.admin.result.index')
            ->call('gotoPage', 2)
            ->assertSet('paginators.page', 2)
            ->set('search', 'Taker 1')
            ->assertSet('paginators.page', 1);
    }

    public function test_reset_filters_clears_all_filters(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Volt::test('pages.admin.result.index')
            ->set('search', 'Budi')
            ->set('quizId', '1')
            ->set('dateFrom', '2026-09-01')
            ->set('dateTo', '2026-09-30')
            ->call('resetFilters')
            ->assertSet('search', '')
            ->assertSet('quizId', '')
            ->assertSet('dateFrom', '')
            ->assertSet('dateTo', '');
    }

    public function test_export_returns_xlsx_with_submitted_attempts(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $user = User::factory()->create(['name' => 'Budi Santoso', 'email' => 'budi@mail.com']);
        $quiz = $this->publishedQuiz('Stress Level', 'stress');

        $this->submittedAttempt($user, $quiz, 72);
        app(QuizAttemptService::class)->start($quiz, $user);

        $response = $this->get(route('admin.results.export'));

        $response->assertOk();
        $response->assertHeader(
            'content-type',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        $rows = $this->exportRows();

        $this->assertCount(1, $rows);
        $this->assertSame('Budi Santoso', $rows[0][0]);
        $this->assertSame('budi@mail.com', $rows[0][1]);
        $this->assertSame('Stress Level', $rows[0][2]);
        $this->assertSame(QuizType::Stress->label(), $rows[0][3]);
        $this->assertEquals(72, $rows[0][4]);
    }

    public function test_export_honours_active_filters(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $quiz = $this->publishedQuiz('Any Quiz');
        $budi = User::factory()->create(['name' => 'Budi Santoso', 'email' => 'budi@mail.com']);
        $sari = User::factory()->create(['name' => 'Sari Wijaya', 'email' => 'sari@mail.com']);

        $this->submittedAttempt($budi, $quiz, 80, '2026-09-01 10:00:00');
        $this->submittedAttempt($sari, $quiz, 90, '2026-09-20 10:00:00');

        $this->assertCount(2, $this->exportRows());

        $byName = $this->exportRows(['search' => 'Budi']);
        $this->assertCount(1, $byName);
        $this->assertSame('Budi Santoso', $byName[0][0]);

        $byDate = $this->exportRows(['from' => '2026-09-15', 'to' => '2026-09-30']);
        $this->assertCount(1, $byDate);
        $this->assertSame('Sari Wijaya', $byDate[0][0]);
    }

    public function test_export_includes_all_rows_not_just_current_page(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $quiz = $this->publishedQuiz('Any Quiz');

        foreach (range(1, 18) as $i) {
            $user = User::factory()->create(['name' => 'Taker '.$i]);
            $this->submittedAttempt($user, $quiz, $i);
        }

        // The page itself is capped at 15 rows; the export must not be.
        Volt::test('pages.admin.result.index')
            ->assertViewHas('attempts', fn ($attempts) => $attempts->total() === 18 && $attempts->count() === 15);

        $this->assertCount(18, $this->exportRows());
    }

    public function test_export_preserves_a_zero_score(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $user = User::factory()->create(['name' => 'Quiz Taker']);
        $quiz = $this->publishedQuiz('Any Quiz');

        $this->submittedAttempt($user, $quiz, 0);

        $rows = $this->exportRows();

        $this->assertCount(1, $rows);
        $this->assertSame(0, (int) $rows[0][4], 'A zero score must export as 0, not a blank cell.');
    }

    public function test_regular_user_cannot_access_the_report_or_export(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin/results')->assertForbidden();
        $this->actingAs($user)->get('/admin/results/export')->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/results')->assertRedirect('/login');
        $this->get('/admin/results/export')->assertRedirect('/login');
    }
}
