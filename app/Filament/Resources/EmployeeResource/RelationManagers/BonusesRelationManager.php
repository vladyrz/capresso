<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BonusesRelationManager extends RelationManager
{
    protected static string $relationship = 'bonuses';
    protected static ?string $recordTitleAttribute = 'concept';
    protected static ?string $title = 'Bonificaciones';
    protected static ?string $modelLabel = 'bonificación';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('concept')->label('Concepto')->required()->maxLength(255),
                TextInput::make('amount')->label('Monto')->numeric()->required()->minValue(0)->default(0),
                DatePicker::make('granted_at')->label('Fecha'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('concept')->label('Concepto')->searchable(),
                TextColumn::make('amount')->money('USD', true)->label('Monto'),
                TextColumn::make('granted_at')->label('Fecha')->date(),
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
