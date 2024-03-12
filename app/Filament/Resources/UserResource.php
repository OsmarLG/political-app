<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Users Manegement';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int    $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Información Personal
                Forms\Components\Fieldset::make('Información Personal')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('app')
                            ->label('Apellido Paterno')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('apm')
                            ->label('Apellido Materno')
                            ->required()
                            ->maxLength(255),
                    ]),
                
                // Información de Inicio de Sesión
                Forms\Components\Fieldset::make('Información de Inicio de Sesión')
                    ->schema([
                        Forms\Components\TextInput::make('username')
                            ->label('Usuario')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Confirmar Contraseña')
                            ->password()
                            ->required()
                            ->maxLength(255)
                            ->confirmed('password'),
                    ]),
    
                // Rol
                Forms\Components\Fieldset::make('Rol')
                    ->schema([
                        Forms\Components\Select::make('rol_id')
                            ->relationship(name: 'rol', titleAttribute:'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                    ]),
            ]);
    }    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('app')
                    ->label('Apellido Paterno')
                    ->searchable(),
                Tables\Columns\TextColumn::make('apm')
                    ->label('Apellido Materno')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->label('Usuario')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rol.name')
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
