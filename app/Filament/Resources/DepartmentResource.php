<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Models\Department;
use App\Models\Company as CompanyModel;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Unique;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;
    protected static ?string $navigationGroup = 'Organización';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $modelLabel = 'departamento';
    protected static ?string $pluralModelLabel = 'departamentos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('company_id')
                    ->label('Empresa')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->unique(
                        table: Department::class,
                        column: 'name',
                        modifyRuleUsing: fn (Unique $rule, Get $get) =>
                            $rule->where('company_id', $get('company_id')),
                        ignoreRecord: true,
                    ),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('company.name')
                    ->label('Empresa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employees_count')
                    ->label('Empleados')
                    ->counts('employees'),
                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->since()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime()
                    ->since()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('company_id')
                    ->label('Empresa')
                    ->options(fn() => CompanyModel::query()->pluck('name', 'id')->toArray())
                    ->indicator('Empresa'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
