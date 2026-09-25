<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublishEnquiryResource\Pages;
use App\Models\PublishEnquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class PublishEnquiryResource extends Resource
{
    protected static ?string $model = PublishEnquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'Publications · Enquiries';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('full_name')->disabled(),
                Forms\Components\TextInput::make('email')->disabled(),
                Forms\Components\TextInput::make('phone')->disabled(),
                Forms\Components\TextInput::make('location')->disabled(),
                Forms\Components\TextInput::make('book_title')->disabled(),
                Forms\Components\TextInput::make('genre')->disabled(),
                Forms\Components\TextInput::make('language')->disabled(),
                Forms\Components\TextInput::make('word_count')->disabled(),
                Forms\Components\TagsInput::make('formats')->disabled(),
                Forms\Components\Placeholder::make('manuscript')
                    ->content(fn (?PublishEnquiry $record) => $record?->manuscript_path
                        ? new HtmlString('<a class="text-primary-600 underline" href="'.route('publish-enquiries.manuscript', $record).'" target="_blank">Download manuscript</a>')
                        : 'No manuscript uploaded'),
                Forms\Components\Select::make('status')
                    ->options([
                        'new' => 'New',
                        'reviewing' => 'Reviewing',
                        'contacted' => 'Contacted',
                        'closed' => 'Closed',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('book_title')
                    ->label('Book'),
                Tables\Columns\TextColumn::make('genre')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'new' => 'info',
                        'reviewing' => 'warning',
                        'contacted' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'reviewing' => 'Reviewing',
                        'contacted' => 'Contacted',
                        'closed' => 'Closed',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'new')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPublishEnquiries::route('/'),
            'edit' => Pages\EditPublishEnquiry::route('/{record}/edit'),
        ];
    }
}
