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

class AguinaldosRelationManager extends RelationManager
{
    protected static string $relationship = 'aguinaldos';
    protected static ?string $recordTitleAttribute = 'year';
    protected static ?string $modelLabel = 'aguinaldo';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('year')->label('Año')->numeric()->minValue(2000)->maxValue(2100)->required(),
                TextInput::make('amount')->label('Monto')->numeric()->minValue(0)->default(0),
                DatePicker::make('paid_at')->label('Pagado el'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('year')->label('Año')->searchable()->sortable(),
                TextColumn::make('amount')->money('USD', true)->label('Monto'),
                TextColumn::make('paid_at')->label('Pagado el')->date(),
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
