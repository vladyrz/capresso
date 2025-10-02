<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Gestión de usuarios';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'usuario';
    protected static ?string $pluralModelLabel = 'usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información de usuario')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->hiddenOn([
                                'edit',
                                'view',
                            ])
                            ->revealable()
                            ->maxLength(255)
                            ->required(),
                        DateTimePicker::make('email_verified_at')
                            ->label('Correo verificado'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->label('Nombre')->searchable(),
                TextColumn::make('email')->label('Correo electrónico')->searchable(),
                TextColumn::make('email_verified_at')->label('Correo verificado')->dateTime()->sortable()->toggleable(),
                TextColumn::make('created_at')->label('Creado el')->dateTime()->since()->toggleable(),
                TextColumn::make('updated_at')->label('Actualizado el')->dateTime()->since()->toggleable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
