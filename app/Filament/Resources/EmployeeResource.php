<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use App\Models\Department as DepartmentModel;
use App\Models\Company as CompanyModel;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Unique;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationGroup = 'Gestión del personal';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $modelLabel = 'empleado';
    protected static ?string $pluralModelLabel = 'empleados';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'cedula'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Organización')
                    ->schema([
                        Select::make('company_id')
                            ->label('Empresa')
                            ->relationship('company', 'name')
                            ->searchable()->preload()->required()
                            ->reactive(),
                        Select::make('department_id')
                            ->label('Departamento')
                            ->options(fn (callable $get) => DepartmentModel::query()
                                ->when($get('company_id'), fn ($q, $cid) => $q->where('company_id', $cid))
                                ->pluck('name', 'id')->toArray())
                            ->searchable()->preload(),
                        Select::make('manager_id')
                            ->label('Jefe inmediato')
                            ->options(fn (callable $get, ?Employee $record) =>
                                Employee::query()
                                    ->when($get('company_id'), fn ($q, $cid) => $q->where('company_id', $cid))
                                    ->when($record?->getKey(), fn ($q, $id) => $q->whereKeyNot($id))
                                    ->pluck('name', 'id')->toArray())
                            ->searchable()->preload()->nullable(),
                        TextInput::make('position')
                            ->label('Puesto')
                            ->maxLength(255),
                        Toggle::make('is_payroll')
                            ->label('Planilla')
                            ->default(true),
                        Toggle::make('is_outsourcing')
                            ->label('Outsourcing')
                            ->default(false),
                    ])->columns(2),

                Section::make('Datos personales')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required(),
                        TextInput::make('cedula')
                            ->label('Cédula')
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->nullable()
                            ->dehydrateStateUsing(fn ($state) => $state ? mb_strtolower(trim($state)) : null)
                            ->unique(
                                table: Employee::class,
                                column: 'email',
                                modifyRuleUsing: fn (Unique $rule, Get $get) =>
                                    $rule->where('company_id', $get('company_id')),
                                ignoreRecord: true
                            )
                            ->live(onBlur: true),
                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->maxLength(30),
                        DatePicker::make('birth_date')
                            ->label('Nacimiento'),
                        Select::make('blood_type')
                            ->label('Tipo de sangre')
                            ->options([
                                'A+' => 'A+',
                                'A-' => 'A-',
                                'B+' => 'B+',
                                'B-' => 'B-',
                                'AB+' => 'AB+',
                                'AB-' => 'AB-',
                                'O+' => 'O+',
                                'O-' => 'O-',
                            ]),
                        TextInput::make('country')
                            ->label('País')
                            ->maxLength(255),
                        TextInput::make('address')
                            ->label('Dirección')
                            ->maxLength(255),
                        Select::make('academic_degree')
                            ->label('Grado académico')
                            ->options([
                                'sin_escolaridad' => 'Sin escolaridad',
                                'primaria' => 'Primaria',
                                'secundaria' => 'Secundaria',
                                'bachillerato' => 'Bachillerato / Preparatoria',
                                'tecnico' => 'Técnico',
                                'tecnologo' => 'Técnologo',
                                'profesional' => 'Profesional / Licenciatura',
                                'especializacion' => 'Especialización',
                                'maestria' => 'Maestría',
                                'doctorado' => 'Doctorado',
                                'post_doctorado' => 'Post-doctorado',
                            ]),
                        DatePicker::make('hired_at')
                            ->label('Fecha de ingreso'),
                    ])->columns(3),

                Section::make('Documentos de viaje')
                    ->schema([
                        TextInput::make('passport_number')
                            ->label('Pasaporte')
                            ->maxLength(255),
                        DatePicker::make('passport_expires_at')
                            ->label('Vencimiento del pasaporte'),
                        DatePicker::make('us_visa_expires_at')
                            ->label('Vencimiento visa USA'),
                    ])->columns(3)->collapsed(),

                Section::make('Compensación')
                    ->schema([
                        TextInput::make('gross_salary_currency')
                            ->label('Moneda base (USD, EUR, etc.)')
                            ->maxLength(3),
                        TextInput::make('gross_salary_amount')
                            ->label('Monto base')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('social_chargers')
                            ->label('Cargos sociales')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('gross_salary_amount_local')
                            ->label('Monto moneda local')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('gross_salary_amount_usd')
                            ->label('Monto USD')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('gross_salary_base_currency')
                            ->label('Moneda base actual (USD, EUR, etc.)')
                            ->maxLength(3),
                    ])->columns(3)->collapsed(),

                Section::make('Licencias')
                    ->schema([
                        Toggle::make('has_car_license')
                            ->label('Licencia vehículo'),
                        Toggle::make('has_motorcycle_license')
                            ->label('Licencia moto'),
                    ])->columns(2)->collapsed(),

                Section::make('funciones')
                    ->schema([
                        Textarea::make('main_function')
                            ->label('Función principal')
                            ->rows(4),
                    ])->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('company.name')->label('Empresa')->sortable()->toggleable(),
                TextColumn::make('department.name')->label('Departamento')->sortable()->toggleable(),
                TextColumn::make('name')->label('Nombre')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->toggleable(),
                TextColumn::make('cedula')->searchable()->toggleable(),
                TextColumn::make('position')->label('Puesto')->toggleable(),
                TextColumn::make('manager.name')->label('Jefe')->toggleable(),
                IconColumn::make('is_payroll')->label('Planilla')->boolean()->alignCenter(),
                IconColumn::make('is_outsourcing')->label('Outsourcing')->boolean()->alignCenter(),
                TextColumn::make('hired_at')->label('Ingreso')->date()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->label('Creado el')->dateTime()->since()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Actualizado el')->dateTime()->since()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('company_id')
                    ->label('Empresa')
                    ->options(fn() => CompanyModel::query()->pluck('name', 'id')->toArray()),
                SelectFilter::make('department_id')
                    ->label('Departamento')
                    ->options(fn() => DepartmentModel::query()->pluck('name', 'id')->toArray()),
                TernaryFilter::make('is_payroll')->label('Planilla')->boolean(),
                TernaryFilter::make('is_outsourcing')->label('Outsourcing')->boolean(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\LicensesRelationManager::class,
            RelationManagers\ConditionsRelationManager::class,
            RelationManagers\InKindBenefitsRelationManager::class,
            RelationManagers\CommissionsRelationManager::class,
            RelationManagers\BonusesRelationManager::class,
            RelationManagers\HistoriesRelationManager::class,
            RelationManagers\AguinaldosRelationManager::class,
            RelationManagers\AssetsRelationManager::class,
            RelationManagers\UniformsRelationManager::class,
            RelationManagers\TrainingsRelationManager::class,
            RelationManagers\WarningsRelationManager::class,
            RelationManagers\ScholarshipsRelationManager::class,
            RelationManagers\LeavesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
