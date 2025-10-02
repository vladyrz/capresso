<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InKindBenefitsRelationManager extends RelationManager
{
    protected static string $relationship = 'inKindBenefits';
    protected static ?string $recordTitleAttribute = 'description';
    protected static ?string $title = 'Salario en especie';
    protected static ?string $modelLabel = 'beneficio';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('description')->label('Descripción')->required()->maxLength(255),
                TextInput::make('amount')->label('Monto')->numeric()->minValue(0)->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')->label('Descripción')->searchable(),
                TextColumn::make('amount')->money('USD', true)->label('Monto'),
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
