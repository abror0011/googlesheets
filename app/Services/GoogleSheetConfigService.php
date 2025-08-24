<?php

namespace App\Services;

use App\Models\GoogleSheetConfig;

class GoogleSheetConfigService
{
    private GoogleSheetConfig $model;

    public function __construct(GoogleSheetConfig $model){
        $this->model = $model;
    }
    public function getUrl(string $settingKey)
    {
        $config = $this->model->query()->where('key', $settingKey)->first();
        return $config ? $config->value : null;
    }
    public function updateOrCreate(array $values = [])
    {
        return $this->model->query()->updateOrCreate(['key' => $values['key']], ['value' => $values['value']]);
    }
}
