<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'histories';
    protected static ?string $recordTitleAttribute = 'event';
    protected static ?string $title = 'Historial';
    protected static ?string $modelLabel = 'historial';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('happened_at')->label('Fecha'),
                TextInput::make('event')->label('Evento')->required()->maxLength(255),
                Textarea::make('notes')->label('Notas')->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('happened_at')->label('Fecha')->date(),
                TextColumn::make('event')->label('Evento')->searchable(),
                TextColumn::make('notes')->label('Notas')->limit(50),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
