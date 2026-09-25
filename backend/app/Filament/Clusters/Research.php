<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

/**
 * Admin area for the roots_research website. Every resource in this cluster
 * only reads and writes Research content.
 */
class Research extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Research';

    protected static ?string $navigationGroup = 'Websites';

    protected static ?int $navigationSort = 20;

    protected static ?string $slug = 'research';
}
