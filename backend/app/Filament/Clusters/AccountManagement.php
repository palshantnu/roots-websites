<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

/**
 * Company account management — a separate admin area for finances:
 * work streams, payments, employees/salaries, projects and reporting.
 */
class AccountManagement extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Account Management';

    protected static ?string $navigationGroup = 'Company';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'account-management';
}
