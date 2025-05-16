<?php

namespace App\Filament\Resources\PlaceResource\Pages;

use App\Filament\Resources\PlaceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePlace extends CreateRecord
{
    protected static string $resource = PlaceResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['latitude'] = $data['map']['lat'];
        $data['longitude'] = $data['map']['lng'];
        return $data;
    }
}
