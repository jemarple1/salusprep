<x-mail.layout>
    @php
        $firstName = trim(explode(' ', $user->name)[0] ?? '');
    @endphp

    <p style="margin: 0 0 8px; font-size: 12px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #4ade80;">
        Day {{ $dayNumber }} · {{ $planDateLabel }}
    </p>

    <h1 style="margin: 0 0 12px; font-size: 24px; line-height: 1.3; color: #ffffff;">
        @if ($firstName !== '')
            Good morning, {{ $firstName }} — today's {{ $sectionLabel }} checklist
        @else
            Good morning — today's {{ $sectionLabel }} checklist
        @endif
    </h1>

    <p style="margin: 0 0 20px; font-size: 15px; line-height: 1.6; color: #cbd5e1;">
        {{ $preview }}
        @if ($examCountdownDays !== null && $examCountdownDays > 0)
            <strong style="color: #ffffff;">{{ $examCountdownDays }} day{{ $examCountdownDays === 1 ? '' : 's' }} until your exam.</strong>
        @endif
    </p>

    <div style="margin: 0 0 24px; padding: 20px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.08); background-color: rgba(15,23,42,0.55);">
        <p style="margin: 0 0 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: #94a3b8;">
            Today's study checklist
        </p>
        <ul style="margin: 0; padding: 0; list-style: none;">
            @foreach ($items as $item)
                <li style="margin: 0 0 10px; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); background-color: rgba(15,23,42,0.65);">
                    <p style="margin: 0 0 4px; font-size: 11px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: #64748b;">
                        {{ match ($item['type']) {
                            'skill' => 'Skill',
                            'quiz' => 'Quiz',
                            'mock' => 'Mock exam',
                            default => 'Task',
                        } }}
                    </p>
                    <p style="margin: 0 0 4px; font-size: 15px; font-weight: 700; color: #ffffff;">{{ $item['label'] }}</p>
                    <p style="margin: 0; font-size: 13px; line-height: 1.5; color: #94a3b8;">{{ $item['description'] }}</p>
                </li>
            @endforeach
        </ul>
    </div>

    @if ($featuredSkill)
        <div style="margin: 0 0 24px; padding: 20px; border-radius: 14px; border: 1px solid rgba(245,158,11,0.25); background-color: rgba(245,158,11,0.08);">
            <p style="margin: 0 0 8px; font-size: 13px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: #fbbf24;">
                Today's skill spotlight
            </p>
            <p style="margin: 0 0 6px; font-size: 17px; font-weight: 700; color: #ffffff;">{{ $featuredSkill['title'] }}</p>
            <p style="margin: 0 0 12px; font-size: 14px; line-height: 1.55; color: #cbd5e1;">
                @if ($featuredSkill['uncovered'])
                    You haven't started this exercise yet — a great place to grow today.
                @else
                    Keep building reps in a skill that still has room to grow.
                @endif
                {{ $featuredSkill['description'] }}
            </p>
            <a href="{{ $featuredSkill['url'] }}" style="display: inline-block; color: #fbbf24; font-size: 14px; font-weight: 700; text-decoration: none;">
                Open skill exercise →
            </a>
        </div>
    @endif

    <div style="margin: 0 0 24px; padding: 20px; border-radius: 14px; border: 1px solid rgba(0,107,182,0.25); background-color: rgba(0,107,182,0.1);">
        <p style="margin: 0 0 8px; font-size: 13px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: #3399cc;">
            {{ $reviewFact['title'] }}
        </p>
        @if ($reviewFact['hasMiss'] && ($reviewFact['category'] ?? null))
            <p style="margin: 0 0 8px; font-size: 12px; font-weight: 700; color: #94a3b8;">{{ $reviewFact['category'] }}</p>
            <p style="margin: 0 0 10px; font-size: 14px; line-height: 1.55; color: #e2e8f0; font-style: italic;">"{{ $reviewFact['stem'] }}"</p>
            @if ($reviewFact['yourAnswer'] && $reviewFact['correctAnswer'])
                <p style="margin: 0 0 10px; font-size: 13px; color: #94a3b8;">
                    You chose <strong style="color: #fca5a5;">{{ $reviewFact['yourAnswer'] }}</strong>.
                    Correct: <strong style="color: #4ade80;">{{ $reviewFact['correctAnswer'] }}</strong>.
                </p>
            @endif
        @endif
        <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #cbd5e1;">{{ $reviewFact['body'] }}</p>
    </div>

    <a href="{{ $welcomeUrl }}" style="display: inline-block; background-color: #16a34a; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 14px; padding: 12px 20px; border-radius: 12px;">
        Open today's checklist
    </a>

    <p style="margin: 24px 0 0; font-size: 12px; line-height: 1.6; color: #64748b;">
        Daily study reminders stop on your exam date.
        <a href="{{ $unsubscribeUrl }}" style="color: #94a3b8; text-decoration: underline;">Unsubscribe from daily study emails</a>
    </p>
</x-mail.layout>
