<?php

namespace App\Actions;

use App\Models\User;
use Filament\Actions\StaticAction;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use App\Models\Items\Inspections\ItemTemplate;
use App\Models\Items\Inspections\ItemInspection;
use App\Filament\Resources\ItemResource\RelationManagers\InspectionTemplatesRelationManager;

class QueueInspectionAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'Queue Inspection';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-queue-list');
        
        $this->form([
            Select::make('assigned_to_user_id')
                ->label('Assign Inspection To')
                ->options(User::permission('update_item::inspection')->pluck('name', 'id'))
                ->default(auth()->user()->id),
        ]);

        $this->modalHeading(false);

        $this->modalSubmitAction(fn (StaticAction $action) => $action->icon('heroicon-o-arrow-left-start-on-rectangle'));

        $this->modalSubmitActionLabel('Queue Inspection');

        $this->extraModalFooterActions(fn (Action $action): array => [
            $action
                ->makeModalSubmitAction('queueInspectionAndView', arguments: ['redirect' => true])
                ->label('Queue Inspection and View')
                ->color('info')
                ->icon('heroicon-m-pencil-square')
        ]);

        $this->action(
            function (ItemTemplate $record, array $arguments, array $data, $livewire): void {                        
                $inspection = new ItemInspection;
                if ($livewire instanceof InspectionTemplatesRelationManager) {
                    $inspection->item_id = $livewire->getOwnerRecord()->id;
                } else {
                    $inspection->item_id = $record->item_id;
                }
                $inspection->item_template_id = $record->id;
                $inspection->assigned_to_user_id = $data['assigned_to_user_id'];
                $inspection->save();

                if ($arguments['redirect'] ?? false) {
                    redirect()->route('filament.admin.resources.item-inspections.edit', ['record' => $inspection->id]);
                } else {
                    redirect(request()->header('Referer'));
                }
            } 
        );
    }
}
