<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Place;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\PlaceResource\Pages;

class PlaceResource extends Resource
{
    protected static ?string $model = Place::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'الأماكن';
    protected static ?string $pluralNavigationLabel = 'الأماكن';
    protected static ?string $pluralLabel = 'الأماكن';
    protected static ?string $pluralModelLabel = 'الأماكن';
    protected static ?string $modelLabel = 'المكان';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->label('الرابط المختصر')
                    ->helperText('الرابط المختصر يستخدم في رابط المكان')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('الوصف'),
                Forms\Components\Textarea::make('address')
                    ->label('العنوان')
                    ->required(),
                \App\Forms\Components\Map::make('map')
                    ->formatStateUsing(fn(?Model $record) => [
                        'lat' => $record?->latitude,
                        'lng' => $record?->longitude,
                    ])
                    ->label('الخريطة'),
                Forms\Components\FileUpload::make('photo')
                    ->label('الصورة')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '6:4'
                    ])
                    ->required(fn($operation) => $operation === 'create')
                    ->dehydrated(fn($state) => filled($state)),
                Forms\Components\Select::make('categories')
                    ->label('التصنيفات')
                    ->multiple()
                    ->relationship('categories', 'name')
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('الرقم التسلسلي')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('الرابط المختصر')
                    ->copyable()
                    ->copyableState(fn(string $state): string => route('place.show', $state))
                    ->searchable(),
                Tables\Columns\TextColumn::make('view_count')
                    ->label('عدد المشاهدات')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('avg')
                    ->label('التقييم العام')
                    ->sortable()
                    ->formatStateUsing(fn(float $state): string => round($state, 1))
                    ->suffix(' / 5')
                ,
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل')
                    ->color('info'),
                Tables\Actions\Action::make('open')
                    ->label('فتح')
                    ->url(fn(Place $record): string => route('place.show', $record->slug))
                    ->openUrlInNewTab()
                    ->icon('heroicon-m-arrow-top-right-on-square'),
                Tables\Actions\DeleteAction::make()
                    ->label('حذف')
                    ->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف'),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlaces::route('/'),
            'create' => Pages\CreatePlace::route('/create'),
            'edit' => Pages\EditPlace::route('/{record}/edit'),
        ];
    }
}
