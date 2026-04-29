<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Photos;
use App\Models\Partner;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Tổng số hình nền', Photos::count())
                ->description('Kho lưu trữ ảnh')
                ->descriptionIcon('heroicon-m-photo')
                ->color('success'),
            Stat::make('Tổng lượt xem', number_format(Photos::sum('view_count')))
                ->description('Mức độ tiếp cận')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),
            Stat::make('Tổng lượt thích', number_format(Photos::sum('like_count')))
                ->description('Mức độ yêu thích')
                ->descriptionIcon('heroicon-m-heart')
                ->color('danger'),
            Stat::make('Nghệ sĩ tham gia', Partner::count())
                ->description('Cộng tác viên')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
            Stat::make('Người dùng đăng ký', Customer::count())
                ->description('Thành viên cộng đồng')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
