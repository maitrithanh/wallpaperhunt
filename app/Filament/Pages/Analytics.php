<?php

namespace App\Filament\Pages;

use App\Models\Photos;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Response;

class Analytics extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $title = 'Thống kê & Báo cáo';
    protected static ?string $navigationLabel = 'Thống kê & Báo cáo';
    protected static ?int $navigationSort = 50;

    protected string $view = 'filament.pages.analytics';

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = [
            'startDate' => now()->startOfMonth()->toDateString(),
            'endDate' => now()->toDateString(),
        ];
        
        $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Bộ lọc thời gian báo cáo')
                    ->schema([
                        DatePicker::make('startDate')
                            ->label('Từ ngày')
                            ->native(false)
                            ->default(now()->startOfMonth())
                            ->live(),
                        DatePicker::make('endDate')
                            ->label('Đến ngày')
                            ->native(false)
                            ->default(now())
                            ->live(),
                    ])->columns(2),
            ])
            ->statePath('data');
    }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Xuất file Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action('exportCsv'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
            \App\Filament\Widgets\UploadTrendChartWidget::class,
            \App\Filament\Widgets\CategoryChartWidget::class,
            \App\Filament\Widgets\TopWallpapersWidget::class,
        ];
    }

    public function getStatsData(): array
    {
        $startDate = $this->data['startDate'] ?? now()->startOfMonth()->toDateString();
        $endDate = $this->data['endDate'] ?? now()->toDateString();

        $query = Photos::query();
        
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $totalPhotos = $query->count();
        $totalViews = $query->sum('view_count');
        $totalLikes = $query->sum('like_count');
        
        // Aggregate by categories
        $categoriesData = Photos::query()
            ->selectRaw('category_id, COUNT(*) as count')
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $chartLabels = [];
        $chartValues = [];

        foreach ($categoriesData as $data) {
            $chartLabels[] = $data->category ? $data->category->name : 'Chưa phân loại';
            $chartValues[] = $data->count;
        }

        return [
            'totalPhotos' => $totalPhotos,
            'totalViews' => $totalViews,
            'totalLikes' => $totalLikes,
            'chartLabels' => $chartLabels,
            'chartValues' => $chartValues,
        ];
    }

    public function exportCsv()
    {
        $startDate = $this->data['startDate'] ?? now()->startOfMonth()->toDateString();
        $endDate = $this->data['endDate'] ?? now()->toDateString();

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=report_" . now()->format('Ymd_His') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($startDate, $endDate) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM

            fputcsv($file, ['Tên tác phẩm', 'Tác giả', 'Danh mục', 'Lượt xem', 'Lượt thích', 'Giá', 'Ngày đăng']);

            $query = Photos::query();

            if ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            }

            $photos = $query->with(['partner', 'category'])->get();

            foreach ($photos as $photo) {
                fputcsv($file, [
                    $photo->name,
                    $photo->partner ? $photo->partner->full_name : 'N/A',
                    $photo->category ? $photo->category->name : 'N/A',
                    $photo->view_count,
                    $photo->like_count,
                    number_format($photo->price ?? 0) . ' VNĐ',
                    $photo->created_at->toDateTimeString()
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
