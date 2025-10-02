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

class UniformsRelationManager extends RelationManager
{
    protected static string $relationship = 'uniforms';
    protected static ?string $recordTitleAttribute = 'delivered_at';
    protected static ?string $title = 'Uniformes';
    protected static ?string $modelLabel = 'uniforme';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('delivered_at')->label('Entregado el')->required(),
                TextInput::make('details')->label('Detalles')->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('delivered_at')->label('Entregado el')->date(),
                TextColumn::make('details')->label('Detalles')->limit(50),
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
