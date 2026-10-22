<?php

namespace App\Support;

/**
 * Every module of the Accounts panel and the actions a role can be granted on
 * it. Add a new module here and it shows up on the role form automatically.
 */
class AccountPermissions
{
    public const MODULES = [
        'proposals' => 'Proposals',
        'proposal_companies' => 'Proposal companies',
        'work_streams' => 'Work streams',
        'account_services' => 'Services',
        'expenses' => 'Expenses',
        'users' => 'Users',
        'roles' => 'Roles & permissions',
    ];

    public const ACTIONS = [
        'view' => 'View',
        'create' => 'Add',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ];

    /** Every action on every module — used for "full access". */
    public static function all(): array
    {
        return collect(self::MODULES)
            ->map(fn () => array_keys(self::ACTIONS))
            ->all();
    }
}
