<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;



class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'التقييم';
    protected static ?string $pluralNavigationLabel = 'التقييمات';
    protected static ?string $modelLabel = 'تقييم';
    protected static ?string $pluralModelLabel = 'تقييمات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('review')
                    ->label('التقييم')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('service_rating')
                    ->label('تقييم الخدمة')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('quality_rating')
                    ->label('تقييم الجودة')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('cleanliness_rating')
                    ->label('تقييم النظافة')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('price_rating')
                    ->label('تقييم السعر')
                    ->numeric()
                    ->default(null),
                Forms\Components\Select::make('place_id')
                    ->label('المكان')
                    ->relationship('place', 'name')
                    ->searchable()
                    ->native(false)
                    ->preload()
                    ->required()
                    ->optionsLimit(50)
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('المستخدم')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->native(false)
                    ->preload()
                    ->required()
                    ->optionsLimit(50),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('place.name')
                    ->label('المكان'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('المستخدم'),
                Tables\Columns\TextColumn::make('service_rating')
                    ->label('تقييم الخدمة')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quality_rating')
                    ->label('تقييم الجودة')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cleanliness_rating')
                    ->label('تقييم النظافة')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_rating')
                    ->label('تقييم السعر')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('place_id')
                    ->label('المكان')
                    ->relationship('place', 'name')
                    ->searchable()
                    ->preload()
                    ->optionsLimit(50),
                SelectFilter::make('user_id')
                    ->label('المستخدم')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->optionsLimit(50),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageReviews::route('/'),
        ];
    }

}
