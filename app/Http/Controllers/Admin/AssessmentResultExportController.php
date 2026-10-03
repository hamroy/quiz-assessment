<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Assessment\AssessmentResultReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssessmentResultExportController extends Controller
{
    private const HEADERS = ['Name', 'Email', 'Quiz', 'Type', 'Score', 'Submitted At'];

    private const LAST_COLUMN = 'F';

    public function __invoke(Request $request, AssessmentResultReportService $report): StreamedResponse
    {
        $attempts = $report->all($this->validatedFilters($request));

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet()->setTitle('Assessment Results');

        foreach (self::HEADERS as $index => $header) {
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($index + 1).'1', $header);
        }

        $row = 2;
        foreach ($attempts as $attempt) {
            // setCellValue is used deliberately: fromArray() silently drops a literal 0 score.
            $values = [
                $attempt->user->name,
                $attempt->user->email,
                $attempt->quiz->title,
                $attempt->quiz->type_label,
                (int) $attempt->score,
                $attempt->submitted_at?->format('Y-m-d H:i'),
            ];

            foreach ($values as $index => $value) {
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($index + 1).$row, $value);
            }

            $row++;
        }

        $headerStyle = $sheet->getStyle('A1:'.self::LAST_COLUMN.'1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE8EEF9');

        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:'.self::LAST_COLUMN.'1');
        $sheet->calculateColumnWidths();

        return response()->streamDownload(
            fn () => (new Xlsx($spreadsheet))->save('php://output'),
            'assessment-results-'.now()->format('Y-m-d-His').'.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        );
    }

    /**
     * @return array{quiz_id:int|null,search:string|null,from:string|null,to:string|null}
     */
    private function validatedFilters(Request $request): array
    {
        $data = Validator::make($request->query(), [
            'quiz_id' => ['nullable', 'integer', 'exists:quizzes,id'],
            'search' => ['nullable', 'string', 'max:120'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
        ])->validate();

        $search = trim((string) ($data['search'] ?? ''));

        return [
            'quiz_id' => isset($data['quiz_id']) && $data['quiz_id'] !== ''
                ? (int) $data['quiz_id']
                : null,
            'search' => $search === '' ? null : $search,
            'from' => $data['from'] ?? null,
            'to' => $data['to'] ?? null,
        ];
    }
}
