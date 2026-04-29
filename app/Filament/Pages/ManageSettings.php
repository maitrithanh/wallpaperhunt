<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $title = 'Cấu hình Website';
    protected static ?string $navigationLabel = 'Cấu hình Website';
    protected static ?int $navigationSort = 100;

    protected string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = [
            'site_logo' => Setting::get('site_logo'),
            'site_favicon' => Setting::get('site_favicon'),
            'primary_color' => Setting::get('primary_color', '#2563eb'),
            'accent_color' => Setting::get('accent_color', '#06b6d4'),
            'social_facebook' => Setting::get('social_facebook'),
            'social_instagram' => Setting::get('social_instagram'),
            'social_youtube' => Setting::get('social_youtube'),
            'social_tiktok' => Setting::get('social_tiktok'),
            'social_twitter' => Setting::get('social_twitter'),
        ];
        
        $this->form->fill($this->data);
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Section::make('Giao diện & Thương hiệu')
                    ->schema([
                        FileUpload::make('site_logo')
                            ->label('Logo Website')
                            ->disk('public')
                            ->directory('settings')
                            ->image()
                            ->maxSize(2048),
                        FileUpload::make('site_favicon')
                            ->label('Favicon')
                            ->disk('public')
                            ->directory('settings')
                            ->image()
                            ->maxSize(1024),
                        ColorPicker::make('primary_color')
                            ->label('Màu chủ đạo (Primary)'),
                        ColorPicker::make('accent_color')
                            ->label('Màu điểm nhấn (Accent)'),
                    ])->columns(2),
                
                Section::make('Mạng xã hội (Footer)')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('social_facebook')
                            ->label('Facebook URL')
                            ->url(),
                        \Filament\Forms\Components\TextInput::make('social_instagram')
                            ->label('Instagram URL')
                            ->url(),
                        \Filament\Forms\Components\TextInput::make('social_youtube')
                            ->label('YouTube URL')
                            ->url(),
                        \Filament\Forms\Components\TextInput::make('social_tiktok')
                            ->label('TikTok URL')
                            ->url(),
                        \Filament\Forms\Components\TextInput::make('social_twitter')
                            ->label('Twitter / X URL')
                            ->url(),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach ($state as $key => $value) {
            Setting::set($key, $value);
        }

        \Filament\Notifications\Notification::make()
            ->title('Đã lưu cấu hình website!')
            ->success()
            ->send();
    }
}
