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

class ScholarshipsRelationManager extends RelationManager
{
    protected static string $relationship = 'scholarships';
    protected static ?string $recordTitleAttribute = 'program';
    protected static ?string $title = 'Becas';
    protected static ?string $modelLabel = 'beca';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('program')->label('Programa')->required()->maxLength(255),
                DatePicker::make('start_date')->label('Inicio'),
                DatePicker::make('end_date')->label('Fin'),
                TextInput::make('amount')->label('Monto')->numeric()->minValue(0)->default(0),
                Textarea::make('notes')->label('Notas')->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('program')->label('Programa')->searchable(),
                TextColumn::make('start_date')->label('Inicio')->date(),
                TextColumn::make('end_date')->label('Fin')->date(),
                TextColumn::make('amount')->money('USD', true)->label('Monto'),
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
