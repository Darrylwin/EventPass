<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $label = 'Événement';

    protected static ?string $pluralLabel = 'Événements';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')
                ->label('Titre')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->label('Description')
                ->required(),

            DateTimePicker::make('starts_at')
                ->label('Date de début')
                ->required(),

            TextInput::make('location')
                ->label('Lieu')
                ->required(),

            TextInput::make('capacity')
                ->label('Capacité')
                ->numeric()
                ->required(),

            TextInput::make('price')
                ->label('Tarif')
                ->numeric()
                ->prefix('FCFA')
                ->default(0),

            Select::make('status')
                ->label('Statut')
                ->options([
                    'brouillon' => 'Brouillon',
                    'publié' => 'Publié',
                    'annulé' => 'Annulé',
                    'terminé' => 'Terminé',
                ])
                ->required(),

            FileUpload::make('image_path')
                ->label('Image')
                ->image()
                ->disk('public')
                ->directory('events')
                ->imageResizeMode('cover')
                ->imageCropAspectRatio('16:9')
                ->imageResizeTargetWidth('1280')
                ->imageResizeTargetHeight('720')
                ->maxSize(2048)
                ->nullable()
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('public')
                    ->height(48)
                    ->width(80)
                    ->defaultImageUrl(null),

                TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('organizer.name')
                    ->label('Organisateur')
                    ->sortable(),

                TextColumn::make('starts_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('capacity')
                    ->label('Capacité'),

                TextColumn::make('registrations_count')
                    ->label('Inscrits')
                    ->counts('registrations'),

                BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'secondary' => 'brouillon',
                        'success' => 'publié',
                        'danger' => 'annulé',
                        'warning' => 'terminé',
                    ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'brouillon' => 'Brouillon',
                        'publié' => 'Publié',
                        'annulé' => 'Annulé',
                        'terminé' => 'Terminé',
                    ]),
            ])
            ->actions([
                Action::make('publier')
                    ->label('Publier')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(Event $record) => $record->status === 'brouillon')
                    ->action(fn(Event $record) => $record->update(['status' => 'publié'])),

                Action::make('annuler')
                    ->label('Annuler')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(Event $record) => $record->status === 'publié')
                    ->requiresConfirmation()
                    ->action(fn(Event $record) => $record->update(['status' => 'annulé'])),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
