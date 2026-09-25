<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SampleType: string implements HasLabel
{
    case ResearchPaper = 'research_paper';
    case Thesis = 'thesis';
    case Synopsis = 'synopsis';

    public function getLabel(): string
    {
        return match ($this) {
            self::ResearchPaper => 'Research Paper',
            self::Thesis => 'Thesis',
            self::Synopsis => 'Synopsis',
        };
    }

    public function pluralLabel(): string
    {
        return match ($this) {
            self::ResearchPaper => 'Research Papers',
            self::Thesis => 'Thesis',
            self::Synopsis => 'Synopsis',
        };
    }
}
