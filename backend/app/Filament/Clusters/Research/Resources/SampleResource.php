<?php

namespace App\Filament\Clusters\Research\Resources;

use App\Enums\SampleType;
use App\Filament\Clusters\Research\ResearchResource;
use App\Filament\Clusters\Research\Resources\SampleResource\Pages;
use App\Models\Sample;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class SampleResource extends ResearchResource
{
    protected static ?string $model = Sample::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Samples';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Sample')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Category')
                            ->options(SampleType::class)
                            ->default(fn () => request()->query('type'))
                            ->required(),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Display order')
                            ->helperText('Lower numbers are shown first.')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, Forms\Set $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug((string) $state));
                                }
                            })
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->alphaDash()
                            ->unique(ignoreRecord: true, modifyRuleUsing: static::uniqueWithinSite())
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('short_description')
                            ->label('Short description')
                            ->helperText('Shown on the sample card.')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Files')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Document')
                            ->helperText('PDF, DOC or DOCX, up to '.(Sample::MAX_DOCUMENT_KILOBYTES / 1024).' MB. Upload a new file to replace the current one.')
                            ->disk(Sample::DOCUMENT_DISK)
                            ->directory('samples/documents')
                            ->visibility('private')
                            ->acceptedFileTypes(array_values(Sample::DOCUMENT_MIME_TYPES))
                            ->maxSize(Sample::MAX_DOCUMENT_KILOBYTES)
                            ->storeFileNamesIn('file_name')
                            ->downloadable()
                            ->required(),
                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Cover image')
                            ->helperText('Optional. JPG, PNG or WEBP, up to '.(Sample::MAX_THUMBNAIL_KILOBYTES / 1024).' MB.')
                            ->image()
                            ->disk(Sample::THUMBNAIL_DISK)
                            ->directory('samples/thumbnails')
                            ->acceptedFileTypes(Sample::THUMBNAIL_MIME_TYPES)
                            ->maxSize(Sample::MAX_THUMBNAIL_KILOBYTES),
                    ]),
                Forms\Components\Section::make('Visibility')
                    ->schema([
                        static::publishedToggle(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('')
                    ->disk(Sample::THUMBNAIL_DISK),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Sample $record): ?string => Str::limit((string) $record->short_description, 60)),
                Tables\Columns\TextColumn::make('type')
                    ->label('Category')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('file_name')
                    ->label('Document')
                    ->description(fn (Sample $record): ?string => $record->file_size ? Number::fileSize($record->file_size) : null)
                    ->limit(30)
                    ->toggleable(),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Published'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Category')
                    ->options(SampleType::class),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('publish')
                        ->icon('heroicon-o-eye')
                        ->action(fn (Collection $records) => $records->each->update(['is_published' => true]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('unpublish')
                        ->icon('heroicon-o-eye-slash')
                        ->action(fn (Collection $records) => $records->each->update(['is_published' => false]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSamples::route('/'),
            'create' => Pages\CreateSample::route('/create'),
            'edit' => Pages\EditSample::route('/{record}/edit'),
        ];
    }
}
