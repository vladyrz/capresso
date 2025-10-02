<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeavesRelationManager extends RelationManager
{
    protected static string $relationship = 'leaves';
    protected static ?string $recordTitleAttribute = 'type';
    protected static ?string $title = 'Incapacidades';
    protected static ?string $modelLabel = 'incapacidad';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')->label('Tipo')->options([
                    'vacaciones' => 'Vacaciones',
                    'personal' => 'Personal',
                    'familiar' => 'Familiar',
                    'otro' => 'Otro',
                ])->required(),
                DatePicker::make('start_date')->label('Inicio')->required(),
                DatePicker::make('end_date')->label('Fin'),
                TextInput::make('reason')->label('Motivo')->maxLength(255),
                FileUpload::make('certificate')->label('Certificado')->downloadable()->directory('incapacidades/' . now()->format('Y/m/d'))->maxFiles(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')->label('Tipo')->badge(),
                TextColumn::make('start_date')->label('Inicio')->date(),
                TextColumn::make('end_date')->label('Fin')->date(),
                TextColumn::make('reason')->label('Motivo')->limit(50),
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
