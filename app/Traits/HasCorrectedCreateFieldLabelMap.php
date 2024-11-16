<?php

namespace App\Traits;

use Filament\Forms\Form;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Field;

/**
 * Summary of HasCorrectedCreateFieldLabelMap
 * 
 * Resources with CheckboxList in the form caused an error
 * Typed property Filament\Forms\Components\Component::$container must not be accessed before initialization
 * 
 * More information @ https://github.com/pxlrbt/filament-activity-log/issues/21
 */

trait HasCorrectedCreateFieldLabelMap
{
    protected function createFieldLabelMap(): Collection
    {
        $form = static::getResource()::form(new Form($this));
        $record = $this->getRecord();

        $form->statePath('data');
        $form->fill($record->attributesToArray());
        $form->model($record);

        $components = collect($form->getComponents());
        $extracted = collect();

        while (($component = $components->shift()) !== null) {
            if ($component instanceof Field || $component instanceof MorphToSelect) {
                $extracted->push($component);

                continue;
            }

            $children = $component->getChildComponents();

            if (count($children) > 0) {
                $components = $components->merge($children);

                continue;
            }

            $extracted->push($component);
        }

        return $extracted
            ->filter(fn ($field) => $field instanceof Field)
            ->mapWithKeys(fn (Field $field) => [
                $field->getName() => $field->getLabel(),
            ]);
   }
}