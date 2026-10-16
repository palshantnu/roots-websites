<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class VisitorCounterWidget extends Widget
{
    protected static string $view = 'filament.widgets.visitor-counter';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -3;
}
