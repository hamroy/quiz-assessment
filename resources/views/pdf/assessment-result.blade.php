<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; margin: 0; padding: 24px; }
        .header { text-align: center; border-bottom: 2px solid #1f5ef5; padding-bottom: 16px; margin-bottom: 24px; }
        h1 { font-size: 22px; margin: 0 0 4px; }
        .subtitle { color: #6b7280; margin: 0; }
        .score-wrap { text-align: center; margin: 24px 0; }
        .score { font-size: 40px; font-weight: bold; color: #1f5ef5; }
        .score-label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; }
        .stats { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .stats td { width: 33%; text-align: center; padding: 12px; border-radius: 8px; font-size: 13px; }
        .stat-value { font-size: 22px; font-weight: bold; }
        .correct { background: #ecfdf5; color: #065f46; }
        .wrong { background: #fef2f2; color: #991b1b; }
        .unanswered { background: #f3f4f6; color: #374151; }
        h2 { font-size: 16px; margin: 24px 0 12px; }
        .review { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; margin-bottom: 10px; }
        .review h3 { font-size: 13px; margin: 0 0 8px; }
        .review dl { margin: 0; font-size: 12px; }
        .review dt { display: inline; font-weight: bold; color: #4b5563; }
        .review dd { display: inline; margin: 0 0 4px; }
        .badge { float: right; font-weight: bold; font-size: 12px; }
        .badge.correct { color: #065f46; }
        .badge.wrong { color: #991b1b; }
        .badge.unanswered { color: #6b7280; }
        .footer { margin-top: 24px; text-align: center; font-size: 11px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Assessment Result') }}</h1>
        <p class="subtitle">{{ $attempt->quiz->title }}</p>
    </div>

    <div class="score-wrap">
        <div class="score">{{ $attempt->score }}%</div>
        <div class="score-label">{{ __('Your Score') }}</div>
    </div>

    <table class="stats">
        <tr>
            <td class="correct"><div class="stat-value">{{ $result->correct }}</div>{{ __('Correct') }}</td>
            <td class="wrong"><div class="stat-value">{{ $result->wrong }}</div>{{ __('Wrong') }}</td>
            <td class="unanswered"><div class="stat-value">{{ $result->unanswered }}</div>{{ __('Unanswered') }}</td>
        </tr>
    </table>

    <h2>{{ __('Question Review') }}</h2>
    @foreach ($result->items as $index => $item)
        <div class="review">
            <h3>
                {{ $index + 1 }}. {{ $item->question }}
                <span class="badge {{ $item->answered ? ($item->isCorrect ? 'correct' : 'wrong') : 'unanswered' }}">
                    @if (! $item->answered)
                        {{ __('Unanswered') }}
                    @elseif ($item->isCorrect)
                        {{ __('Correct') }}
                    @else
                        {{ __('Incorrect') }}
                    @endif
                </span>
            </h3>
            <dl>
                <div><dt>{{ __('Your answer:') }}</dt> <dd>{{ $item->selectedText ?? __('Not answered') }}</dd></div>
                <div><dt>{{ __('Correct answer:') }}</dt> <dd>{{ $item->correctText ?? __('Not available') }}</dd></div>
                <div><dt>{{ __('Points:') }}</dt> <dd>{{ $item->points }} / {{ $item->maxPoints }}</dd></div>
            </dl>
        </div>
    @endforeach

    <div class="footer">
        {{ config('app.name', 'Laravel') }} &mdash; {{ $attempt->submitted_at?->format('Y-m-d H:i') }}
    </div>
</body>
</html>
