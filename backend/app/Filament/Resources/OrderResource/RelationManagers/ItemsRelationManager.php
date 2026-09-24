<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('price')
                    ->formatStateUsing(fn (int $state) => '₹'.number_format($state)),
                Tables\Columns\TextColumn::make('quantity'),
            ])
            ->headerActions([])
            ->actions([]);
    }
}
