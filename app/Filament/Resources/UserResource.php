<?php

namespace App\Filament\Resources;

use App\Traits\HasStandardTableActions;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use App\Filament\Resources\UserResource\Pages;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class UserResource extends Resource implements HasShieldPermissions
{
    use HasStandardTableActions;

    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    public static function getNavigationLabel(): string
    {
        return trans('filament-users::user.resource.label');
    }

    public static function getPermissionPrefixes(): array
    {
        return config('filament-shield.permission_prefixes.resource_with_soft_deletes');
    }

    public static function getPluralLabel(): string
    {
        return trans('filament-users::user.resource.label');
    }

    public static function getLabel(): string
    {
        return trans('filament-users::user.resource.single');
    }

    public static function getNavigationGroup(): ?string
    {
        return config('filament-users.group');
    }

    public function getTitle(): string
    {
        return trans('filament-users::user.resource.title.resource');
    }

    public static function form(Form $form): Form
    {
        $rows = [
            Section::make('User Information')
                ->columnSpan(1)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->label(trans('filament-users::user.resource.name')),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->label(trans('filament-users::user.resource.email')),
                ]),
            Section::make('Change Password')
                ->columnSpan(1)
                ->schema([
                    TextInput::make('password')
                        ->label(trans('filament-users::user.resource.password'))
                        ->hint('Leave empty to keep current password')
                        ->password()
                        ->maxLength(255)
                        ->dehydrateStateUsing(static function ($state, $record) use ($form) {
                            return !empty($state)
                                ? Hash::make($state)
                                : $record->password;
                        }),
                    TextInput::make('password_confirmation')
                        ->label('Confirm Password')
                        ->password()
                        ->maxLength(255)
                        ->same('password'),
                ]),
        ];


        if (config('filament-users.shield') && class_exists(\BezhanSalleh\FilamentShield\FilamentShield::class)) {
            $rows[] = Section::make('Permissions')
                ->schema([
                    CheckboxList::make('roles')
                        ->relationship('roles', 'name'),
                ]);
        }

        $form->schema($rows);

        return $form;
    }

    public static function table(Table $table): Table
    {
        if(class_exists( STS\FilamentImpersonate\Tables\Actions\Impersonate::class) && config('filament-users.impersonate')){
            $table->actions([Impersonate::make('impersonate')]);
        }
        $table
            ->recordAction(ViewAction::class)
            ->recordClasses(fn (User $record) => $record->trashed() ? 'bg-danger-100' : '')
            ->recordUrl(null)
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label(trans('filament-users::user.resource.id')),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label(trans('filament-users::user.resource.name')),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->label(trans('filament-users::user.resource.email')),
                IconColumn::make('email_verified_at')
                    ->boolean()
                    ->sortable()
                    ->searchable()
                    ->label(trans('filament-users::user.resource.email_verified_at')),
                TextColumn::make('created_at')
                    ->label(trans('filament-users::user.resource.created_at'))
                    ->dateTime('M j, Y')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(trans('filament-users::user.resource.updated_at'))
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('verified')
                    ->label(trans('filament-users::user.resource.verified'))
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('email_verified_at')),
                Tables\Filters\Filter::make('unverified')
                    ->label(trans('filament-users::user.resource.unverified'))
                    ->query(fn(Builder $query): Builder => $query->whereNull('email_verified_at')),
                Tables\Filters\TrashedFilter::make()
                    ->label('Inactive Users')
                    ->placeholder('Without Inactive Users')
                    ->trueLabel('With Inactive Users')
                    ->falseLabel('Only Inactive Users'),
            ])
            ->actions([
                ViewAction::make(),
                ActionGroup::make(
                    Static::StandardTableActions(hasSoftDeleteActions: true)
                ),
            ]);
        return $table;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'edit-history' => Pages\EditHistory::route('/{record}/edit/history'),
        ];
    }
}
