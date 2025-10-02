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

class TrainingsRelationManager extends RelationManager
{
    protected static string $relationship = 'trainings';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $title = 'Capacitaciones';
    protected static ?string $modelLabel = 'capacitación';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nombre')->required()->maxLength(255),
                TextInput::make('provider')->label('Proveedor')->maxLength(255),
                TextInput::make('hours')->label('Horas')->numeric()->minValue(0)->default(0),
                DatePicker::make('date')->label('Fecha'),
                Textarea::make('notes')->label('Notas')->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nombre')->searchable(),
                TextColumn::make('provider')->label('Proveedor'),
                TextColumn::make('hours')->label('Horas'),
                TextColumn::make('date')->label('Fecha')->date(),
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
