<?php

return [
    'timezone' => 'America/New_York',
    'send_at' => '09:00',

    /** @var list<array{subject: string, preview: string}> */
    'subjects' => [
        [
            'subject' => 'Your study checklist is ready — let\'s move the needle',
            'preview' => 'Three skills, two quizzes, one mock. Today\'s plan is waiting.',
        ],
        [
            'subject' => 'Small steps today, big confidence on exam day',
            'preview' => 'Open your daily checklist and knock out the next right task.',
        ],
        [
            'subject' => 'Don\'t break the streak — today\'s prep plan inside',
            'preview' => 'Your welcome page refreshed with a new to-do list.',
        ],
        [
            'subject' => '20 focused minutes beats a vague study session',
            'preview' => 'See today\'s checklist plus a quick review from your misses.',
        ],
        [
            'subject' => 'Exam day gets closer — here\'s today\'s playbook',
            'preview' => 'Skills, quizzes, mock exam, and one fact to lock in.',
        ],
        [
            'subject' => 'You\'ve got this — start with today\'s checklist',
            'preview' => 'A clear daily regimen beats guessing what to study next.',
        ],
        [
            'subject' => 'Quick win waiting: your daily SalusPrep plan',
            'preview' => 'Fresh tasks, a skill spotlight, and a bite-sized review.',
        ],
    ],

    'fallback_fact' => 'Adaptive practice works best in short, consistent sessions. Today\'s checklist breaks prep into skills, quizzes, and a mock exam so you always know the next step.',
];
