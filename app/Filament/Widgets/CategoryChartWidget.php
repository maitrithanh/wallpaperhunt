<?php

namespace App\Filament\Widgets;

use App\Models\Photos;
use Filament\Widgets\ChartWidget;

class CategoryChartWidget extends ChartWidget
{
    protected ?string $heading = 'Phân bố tác phẩm theo Danh mục';
    protected ?string $maxHeight = '350px';
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $categoriesData = Photos::query()
            ->selectRaw('category_id, COUNT(*) as count')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $labels = [];
        $data = [];

        foreach ($categoriesData as $item) {
            $labels[] = $item->category ? $item->category->name : 'Chưa phân loại';
            $data[] = $item->count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Số lượng hình nền',
                    'data' => $data,
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#2563eb',
                    'borderWidth' => 1,
                    'borderRadius' => 8,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
