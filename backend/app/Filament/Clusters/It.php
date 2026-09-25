<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

/**
 * Admin area for the roots_it website. Every resource in this cluster only
 * reads and writes IT content.
 */
class It extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $navigationLabel = 'IT';

    protected static ?string $navigationGroup = 'Websites';

    protected static ?int $navigationSort = 30;

    protected static ?string $slug = 'it';
}
