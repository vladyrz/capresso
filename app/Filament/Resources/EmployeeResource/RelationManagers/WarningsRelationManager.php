<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WarningsRelationManager extends RelationManager
{
    protected static string $relationship = 'warnings';
    protected static ?string $recordTitleAttribute = 'severity';
    protected static ?string $title = 'Apercebimientos';
    protected static ?string $modelLabel = 'apercibimiento';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date')->label('Fecha')->required(),
                TextInput::make('reason')->label('Motivo')->required()->maxLength(255),
                Select::make('severity')->label('Gravedad')->options([
                    'leve' => 'Leve',
                    'moderada' => 'Moderada',
                    'grave' => 'Grave',
                ])->required(),
                Textarea::make('notes')->label('Notas')->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')->label('Fecha')->date(),
                TextColumn::make('reason')->label('Motivo')->searchable(),
                TextColumn::make('severity')->label('Gravedad')->badge()->colors([
                    'gray' => 'leve',
                    'warning' => 'moderada',
                    'danger' => 'grave',
                ]),
                TextColumn::make('notes')->label('Notas')->limit(40),
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
