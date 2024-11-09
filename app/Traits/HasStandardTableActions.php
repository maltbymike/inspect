<?php

namespace App\Traits;

use Filament\Tables;

trait HasStandardTableActions
{
    public static function StandardTableActions(bool $hasEditActions = true, bool $hasSoftDeleteActions = false): array
    {
        return array_merge(
            $hasEditActions ? Static::getEditActions() : [],
            $hasSoftDeleteActions ? Static::getSoftDeleteActions() : [],
        );
    }

    public static function getEditActions()
    {
        return [
            Tables\Actions\EditAction::make(),
            Tables\Actions\Action::make('edit-history')
                ->icon('heroicon-m-document-magnifying-glass')
                ->url(fn ($record) => Static::getUrl('edit-history', ['record' => $record])),
        ];
    }

    public static function getSoftDeleteActions()
    {
        return [
            Tables\Actions\DeleteAction::make()
                ->label('Make Inactive'),
            Tables\Actions\RestoreAction::make()
                ->label('Make Active')
                ->color('success'),
        ];
    }
}
