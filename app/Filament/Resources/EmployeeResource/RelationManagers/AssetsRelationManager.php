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

class AssetsRelationManager extends RelationManager
{
    protected static string $relationship = 'assets';
    protected static ?string $recordTitleAttribute = 'type';
    protected static ?string $title = 'Activos';
    protected static ?string $modelLabel = 'activo';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')->label('Tipo')->options([
                    'telefono_movil' => 'Telefono móvil',
                    'tablet' => 'Tablet',
                    'laptop' => 'Laptop',
                    'pc' => 'PC',
                ])->required(),
                TextInput::make('brand')->label('Marca')->maxLength(255),
                TextInput::make('model')->label('Modelo')->maxLength(255),
                TextInput::make('serial')->label('Serie')->maxLength(255),
                DatePicker::make('assigned_at')->label('Asignado el'),
                DatePicker::make('returned_at')->label('Devuelto el'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')->label('Tipo')->badge(),
                TextColumn::make('brand')->label('Marca'),
                TextColumn::make('model')->label('Modelo'),
                TextColumn::make('serial')->label('Serie'),
                TextColumn::make('assigned_at')->label('Asignado el')->date(),
                TextColumn::make('returned_at')->label('Devuelto el')->date(),
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
