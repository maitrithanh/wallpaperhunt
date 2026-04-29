<?php

namespace App\Filament\Widgets;

use App\Models\Photos;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UploadTrendChartWidget extends ChartWidget
{
    protected ?string $heading = 'Xu hướng tải lên Wallpaper';
    protected ?string $maxHeight = '300px';
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $data = Photos::query()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->limit(12)
            ->get();

        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $labels[] = $item->month;
            $values[] = $item->count;
        }

        if (empty($labels)) {
            $labels = [now()->format('Y-m')];
            $values = [Photos::count()];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Số lượng tải lên',
                    'data' => $values,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
