<?php

namespace App\Support;

class QuestionCategory
{
    /** @return array<string, string> */
    private static function colorMap(): array
    {
        return [
            'Airway' => 'ems',
            'Cardiology' => 'medic',
            'Trauma' => 'rescue',
            'Medical' => 'medic',
            'Operations' => 'safety',
            'OB/Peds' => 'pharma',
            'Pharmacology' => 'pharma',
            'IV Therapy' => 'ems',
            'Adult Health' => 'medic',
            'Basic Care' => 'ems',
            'Health Promotion' => 'safety',
            'Maternal/Child' => 'pharma',
            'Psychosocial' => 'safety',
            'Risk Reduction' => 'safety',
            'Safe Care' => 'medic',
        ];
    }

    public static function color(string $category): string
    {
        return self::colorMap()[$category] ?? 'ems';
    }

    /** @return array{border: string, borderSelected: string, bg: string, bgSelected: string, text: string, badge: string, ring: string, bar: string, button: string, buttonHover: string} */
    public static function styles(string $category): array
    {
        return match (self::color($category)) {
            'medic' => [
                'border' => 'border-medic/30',
                'borderSelected' => 'border-medic',
                'bg' => 'bg-medic/10',
                'bgSelected' => 'bg-medic/15',
                'text' => 'text-medic-light',
                'badge' => 'bg-medic/20 text-medic-light',
                'ring' => 'ring-medic/40',
                'bar' => 'bg-medic',
                'button' => 'bg-medic',
                'buttonHover' => 'hover:bg-medic-dark',
                'peerSelected' => 'has-[:checked]:[&_.focus-exam-surface]:border-medic has-[:checked]:[&_.focus-exam-surface]:bg-medic/15 has-[:checked]:[&_.focus-exam-surface]:ring-2 has-[:checked]:[&_.focus-exam-surface]:ring-medic/40',
                'hasCheck' => 'has-[:checked]:[&_.focus-exam-check]:border-medic has-[:checked]:[&_.focus-exam-check]:bg-medic has-[:checked]:[&_.focus-exam-check-icon]:opacity-100 has-[:checked]:[&_.focus-exam-check-icon]:text-navy',
            ],
            'rescue' => [
                'border' => 'border-rescue/30',
                'borderSelected' => 'border-rescue',
                'bg' => 'bg-rescue/10',
                'bgSelected' => 'bg-rescue/15',
                'text' => 'text-red-200',
                'badge' => 'bg-rescue/20 text-red-200',
                'ring' => 'ring-rescue/40',
                'bar' => 'bg-rescue',
                'button' => 'bg-rescue',
                'buttonHover' => 'hover:bg-red-700',
                'peerSelected' => 'has-[:checked]:[&_.focus-exam-surface]:border-rescue has-[:checked]:[&_.focus-exam-surface]:bg-rescue/15 has-[:checked]:[&_.focus-exam-surface]:ring-2 has-[:checked]:[&_.focus-exam-surface]:ring-rescue/40',
                'hasCheck' => 'has-[:checked]:[&_.focus-exam-check]:border-rescue has-[:checked]:[&_.focus-exam-check]:bg-rescue has-[:checked]:[&_.focus-exam-check-icon]:opacity-100 has-[:checked]:[&_.focus-exam-check-icon]:text-white',
            ],
            'safety' => [
                'border' => 'border-safety/30',
                'borderSelected' => 'border-safety',
                'bg' => 'bg-safety/10',
                'bgSelected' => 'bg-safety/15',
                'text' => 'text-safety-light',
                'badge' => 'bg-safety/20 text-safety-light',
                'ring' => 'ring-safety/40',
                'bar' => 'bg-safety',
                'button' => 'bg-safety',
                'buttonHover' => 'hover:bg-safety-light',
                'peerSelected' => 'has-[:checked]:[&_.focus-exam-surface]:border-safety has-[:checked]:[&_.focus-exam-surface]:bg-safety/15 has-[:checked]:[&_.focus-exam-surface]:ring-2 has-[:checked]:[&_.focus-exam-surface]:ring-safety/40',
                'hasCheck' => 'has-[:checked]:[&_.focus-exam-check]:border-safety has-[:checked]:[&_.focus-exam-check]:bg-safety has-[:checked]:[&_.focus-exam-check-icon]:opacity-100 has-[:checked]:[&_.focus-exam-check-icon]:text-navy',
            ],
            'pharma' => [
                'border' => 'border-pharma/30',
                'borderSelected' => 'border-pharma',
                'bg' => 'bg-pharma/10',
                'bgSelected' => 'bg-pharma/15',
                'text' => 'text-pharma-light',
                'badge' => 'bg-pharma/20 text-pharma-light',
                'ring' => 'ring-pharma/40',
                'bar' => 'bg-pharma',
                'button' => 'bg-pharma',
                'buttonHover' => 'hover:bg-pharma/80',
                'peerSelected' => 'has-[:checked]:[&_.focus-exam-surface]:border-pharma has-[:checked]:[&_.focus-exam-surface]:bg-pharma/15 has-[:checked]:[&_.focus-exam-surface]:ring-2 has-[:checked]:[&_.focus-exam-surface]:ring-pharma/40',
                'hasCheck' => 'has-[:checked]:[&_.focus-exam-check]:border-pharma has-[:checked]:[&_.focus-exam-check]:bg-pharma has-[:checked]:[&_.focus-exam-check-icon]:opacity-100 has-[:checked]:[&_.focus-exam-check-icon]:text-navy',
            ],
            default => [
                'border' => 'border-ems/30',
                'borderSelected' => 'border-ems',
                'bg' => 'bg-ems/10',
                'bgSelected' => 'bg-ems/15',
                'text' => 'text-ems-light',
                'badge' => 'bg-ems/20 text-ems-light',
                'ring' => 'ring-ems/40',
                'bar' => 'bg-ems',
                'button' => 'bg-ems',
                'buttonHover' => 'hover:bg-ems/80',
                'peerSelected' => 'has-[:checked]:[&_.focus-exam-surface]:border-ems has-[:checked]:[&_.focus-exam-surface]:bg-ems/15 has-[:checked]:[&_.focus-exam-surface]:ring-2 has-[:checked]:[&_.focus-exam-surface]:ring-ems/40',
                'hasCheck' => 'has-[:checked]:[&_.focus-exam-check]:border-ems has-[:checked]:[&_.focus-exam-check]:bg-ems has-[:checked]:[&_.focus-exam-check-icon]:opacity-100 has-[:checked]:[&_.focus-exam-check-icon]:text-navy',
            ],
        };
    }
}
