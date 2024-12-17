<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Faker\Provider\ar_EG\Text;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Users Management'
    ;

    public static function form(Form $form): Form
    {
        $formFields = [
            Fieldset::make('Data User')
                ->schema([
                    Forms\Components\TextInput::make('username')
                        ->label(__('Username'))
                        ->required(),
                    Forms\Components\TextInput::make('email')
                        ->label(__('Email'))
                        ->email()
                        ->required(),
                    Forms\Components\TextInput::make('password')
                        ->label(__('Password'))
                        ->password()
                        ->dehydrateStateUsing(fn (?string $state): string => $state ? Hash::make($state) : '')
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->visible(fn (string $operation): bool => $operation === 'create'),
                    Forms\Components\TextInput::make('new_password')
                        ->label(__('New Password'))
                        ->password()
                        ->dehydrateStateUsing(fn (?string $state): string => $state ? Hash::make($state) : '')
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->visible(fn (string $operation): bool => $operation === 'edit')
                        ->confirmed(),
                    Forms\Components\TextInput::make('new_password_confirmation')
                        ->label(__('Confirm New Password'))
                        ->password()
                        ->visible(fn (string $operation): bool => $operation === 'edit')
                        ->dehydrated(false),
                ]),
        ];

        if (auth()->user()->hasRole('Super_Admin')) {
            $formFields[] = Select::make('roles')
                ->label(__('Roles'))
                ->relationship('roles', 'name')
                ->preload();
        }

        return $form
            ->schema($formFields);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')
                    ->label(__('Username'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('Email'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label(__('Roles'))
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (auth()->user()->hasRole('Admin')) {
                    return $query->whereHas('roles', function ($q) {
                        $q->whereIn('name', ['Admin','Guru']);
                    });
                    if (auth()->user()->hasRole('SU_ADMIN')) {
                        return $query->whereHas('roles', function ($q) {
                            $q->whereIn('name', ['Admin','SU_ADMIN']);
                        });
                    }
                }
                
                return $query;
            })
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                ->hidden(function ($record) {
                    return auth()->user()->hasRole('Admin') && 
                           $record->hasRole('Admin');
                }),
                Tables\Actions\DeleteAction::make()
                    ->hidden(function ($record) {
                        return auth()->user()->hasRole('Admin') && 
                               $record->hasRole('Admin');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->hidden(fn () => auth()->user()->hasRole('Admin')),
                ]),
            ])
            ->headerActions([
                
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

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('Super_Admin');
    }

    public static function getNavigationItem(): array
    {
        return [
            'label' => __('Users'),
            'icon' => 'heroicon-o-user',
            'url' => static::getUrl('index'),
        ];
    }

  

}
