<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Tables\Table;
use App\Traits\HasStandardTableActions;
use App\Filament\Resources\MediaResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Awcodes\Curator\Resources\MediaResource as CuratorMediaResource;

class MediaResource extends CuratorMediaResource implements HasShieldPermissions
{
    use HasStandardTableActions;
    
    public static function getNavigationGroup(): string|null 
    {
        return 'Resources';
    }

    public static function getModel(): string
    {
        return config('curator.model');
    }

    public static function getPages(): array
    {
        return [
            'index' => CuratorMediaResource\ListMedia::route('/'),
            'create' => CuratorMediaResource\CreateMedia::route('/create'),
            'edit' => CuratorMediaResource\EditMedia::route('/{record}/edit'),
            'edit-history' => Pages\EditHistory::route('/{record}/edit/history'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return config('filament-shield.permission_prefixes.resource');
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->actions([
                Tables\Actions\ActionGroup::make(array_merge(
                    Static::StandardTableActions(),
                    [
                        Tables\Actions\DeleteAction::make(),
                    ],
                )),
            ]);
    }

}
