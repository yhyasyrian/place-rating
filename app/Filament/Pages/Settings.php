<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string $view = 'filament.pages.settings';
    protected static ?string $title = 'الاعدادات';
    public array $settings = [
        'normal_settings' => [
            'type' => Textarea::class,
            'title' => 'إعدادات الموقع',
            'settings' => [
                'description' => [
                    'title' => 'الوصف',
                    'value' => 'description',
                ],
                'title_home' => [
                    'title' => 'عنوان الصفحة الرئيسية',
                    'value' => 'title_home',
                ],
                'description_home' => [
                    'title' => 'وصف الصفحة الرئيسية',
                    'value' => 'description_home',
                ],
                'contact_message' => [
                    'title' => 'رسالة الاتصال',
                    'value' => 'contact_message',
                ],
                'top_message' => [
                    'title' => 'رسالة الأعلى',
                    'value' => 'top_message',
                ],
                'site_keywords' => [
                    'title' => 'الكلمات المفتاحية',
                    'value' => 'site_keywords',
                ],
            ]
        ],
        'social_settings' => [
            'type' => TextInput::class,
            'title' => 'وسائل الاتصال',
            'settings' => [
                'facebook' => [
                    'title' => 'الفيسبوك',
                    'value' => 'facebook',
                ],
                'twitter' => [
                    'title' => 'التويتر',
                    'value' => 'twitter',
                ],
                'instagram' => [
                    'title' => 'الانستجرام',
                    'value' => 'instagram',
                ],
                'linkedin' => [
                    'title' => 'اللينكدان',
                    'value' => 'linkedin',
                ],
                'youtube' => [
                    'title' => 'اليوتيوب',
                    'value' => 'youtube',
                ],
                'whatsapp' => [
                    'title' => 'الواتساب',
                    'value' => 'whatsapp',
                ],
            ]
        ],
    ];
    public ?array $data = [];
    public function mount()
    {
        $settings = Setting::getAll();
        foreach ($this->settings as $title => $items) {
            foreach ($items['settings'] as $key => $item) {
                $this->settings[$title]['settings'][$key]['value'] = $settings[$key] ?? '';
                $this->data[$key] = $settings[$key];
            }
        }
        $this->form->fill();
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema(array_map(function ($item) {
                return Section::make($item['title'])
                    ->schema(
                        array_map(function ($command, $setting) use ($item) {
                            /**
                             * @var TextInput|Textarea|MarkdownEditor
                             */
                            $class = $item['type'];
                            return $class::make($command)
                                ->required()
                                ->label($setting['title'])
                                ->default($setting['value']);
                        }, array_keys($item['settings']), array_values($item['settings']))
                    )
                    ->columns(2)
                ;
            }, $this->settings))
            ->statePath(path: 'data');
    }
    public function save()
    {
        $settings = Setting::getAll();
        $forget = false;
        foreach ($this->data as $key => $value) {
            Setting::query()
                ->when($settings[$key] !== $value, function ($query) use ($key, $value, &$forget) {
                    $query->where('key', $key)
                        ->update(['value' => $value]);
                    $forget = true;
                });
        }
        if ($forget) {
            Cache::forget('settings');
        }
        Notification::make()
            ->title('تم الحفظ')
            ->success()
            ->send();
    }
}