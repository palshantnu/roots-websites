<?php

namespace App\Filament\Concerns;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Read-only inbox for a site's contact form. The resource using this must
 * also be scoped to one site (ScopedToSite), which keeps each site's
 * enquiries separate.
 */
trait ManagesContactMessages
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->disabled(),
                Forms\Components\TextInput::make('email')->disabled(),
                Forms\Components\TextInput::make('phone')->disabled(),
                Forms\Components\TextInput::make('company')->disabled()
                    ->visible(fn (?string $state): bool => filled($state)),
                Forms\Components\TextInput::make('city')->disabled()
                    ->visible(fn (?string $state): bool => filled($state)),
                Forms\Components\TextInput::make('subject')->disabled()
                    ->visible(fn (?string $state): bool => filled($state)),
                Forms\Components\TextInput::make('service')->disabled()
                    ->visible(fn (?string $state): bool => filled($state)),
                Forms\Components\TextInput::make('budget')->disabled()
                    ->visible(fn (?string $state): bool => filled($state)),
                Forms\Components\Textarea::make('message')->disabled()->rows(6)->columnSpanFull()
                    ->visible(fn (?string $state): bool => filled($state)),
                Forms\Components\Toggle::make('read'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('read')->boolean(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make(static::contactTopicColumn())
                    ->label(str(static::contactTopicColumn())->headline())
                    ->limit(40),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('read'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Open'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markRead')
                        ->label('Mark as read')
                        ->icon('heroicon-o-check')
                        ->action(fn ($records) => $records->each->update(['read' => true]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * The column summarising what each enquiry is about in the list.
     */
    protected static function contactTopicColumn(): string
    {
        return 'subject';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->where('read', false)->count();

        return $count > 0 ? (string) $count : null;
    }
}
