<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LicensesRelationManager extends RelationManager
{
    protected static string $relationship = 'licenses';
    protected static ?string $recordTitleAttribute = 'type';
    protected static ?string $title = 'Licencias';
    protected static ?string $modelLabel = 'licencia';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'vehículo' => 'Vehículo',
                        'moto' => 'Moto',
                    ])->required(),
                TextInput::make('category')->label('Categoría'),
                TextInput::make('number')->label('Número'),
                DatePicker::make('expires_at')->label('Vencimiento'),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')->label('Tipo')->searchable()->badge(),
                TextColumn::make('category')->label('Categoría'),
                TextColumn::make('number')->label('Número'),
                TextColumn::make('expires_at')->label('Vencimiento')->date(),
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
