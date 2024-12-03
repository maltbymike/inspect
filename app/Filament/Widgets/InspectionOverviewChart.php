<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use App\Models\Items\Inspections\ItemInspection;

class InspectionOverviewChart extends ChartWidget
{
    protected static ?string $heading = 'Inspections (By Status)';

    protected function getData(): array
    {
        $inspections = ItemInspection::all();

        $data = collect([
            [
                'label' => 'Pending',
                'inspections' => $inspections
                    ->where('started_at', null),
            ],
            [
                'label' => 'In Progress',
                'inspections' => $inspections
                    ->where('completed_at', null)
                    ->where('started_at', '!=', null),
            ],
            [
                'label' => 'Completed (Today)',
                'inspections' => $inspections
                    ->where('completed_at', '>=', Carbon::today()),
            ],
            [
                'label' => 'Completed (Last 7 Days)',
                'inspections' => $inspections
                    ->where('completed_at', '>=', Carbon::today()->subDays(7)),
            ],
        ]);

        return [
            'datasets' => [
                [
                    'label' => 'Inspections',
                    'data' => $data->pluck('inspections')->map->count(),
                ],
            ],
            'labels' => $data->pluck('label')->toArray()
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
