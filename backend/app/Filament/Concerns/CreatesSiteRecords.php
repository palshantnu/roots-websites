<?php

namespace App\Filament\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * For the Create page of a site-scoped resource: the new record's site comes
 * from the resource on the server, never from submitted form data.
 */
trait CreatesSiteRecords
{
    protected function handleRecordCreation(array $data): Model
    {
        $record = new ($this->getModel())($data);
        $record->site()->associate(static::getResource()::getSite());
        $record->save();

        return $record;
    }
}
