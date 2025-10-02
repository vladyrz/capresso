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

class CommissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'commissions';
    protected static ?string $recordTitleAttribute = 'concept';
    protected static ?string $title = 'Comisiones';
    protected static ?string $modelLabel = 'comisión';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('concept')->label('Concepto')->required()->maxLength(255),
                TextInput::make('amount')->label('Monto')->numeric()->required()->minValue(0)->default(0),
                DatePicker::make('earned_at')->label('Fecha'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('concept')->label('Concepto')->searchable(),
                TextColumn::make('amount')->money('USD', true)->label('Monto'),
                TextColumn::make('earned_at')->label('Fecha')->date(),
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
