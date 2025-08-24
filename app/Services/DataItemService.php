<?php

namespace App\Services;

use App\Clients\GoogleSheetClient;
use App\Http\Requests\DataRequest;
use App\Models\DataItem;
use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Log;

class DataItemService
{
    private DataItem $model;
    private GoogleSheetConfigService $service;
    const SETTING_KEY = 'sheet_url';

    public function __construct(DataItem $model, GoogleSheetConfigService $service){
        $this->model = $model;
        $this->service = $service;
    }

    public function getOne(int $id)
    {
        return $this->model->query()->where('id', $id)->first();
    }
    public function getAll()
    {
        return $this->model->query()->orderBy('id','asc')->paginate(15);
    }
    public function create(array $value)
    {
        return $this->model->query()->create($value);
    }
    public function update(int $id, array $value)
    {
        return $this->model->findOrFail($id)->update($value);
    }
    public function delete(int $id)
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function getConfigUrl()
    {
        return $this->service->getUrl(self::SETTING_KEY);
    }
    public function getDocumentId(): string|null
    {
        if (preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $this->getConfigUrl(), $matches))
            return $matches[1];

        return null;
    }

    public function generateData(int $count = 1000)
    {
        DataItem::query()->truncate();
        $data = $this->model->factory($count)->state(
            new Sequence(
                ['status' => StatusEnum::PROHIBITED->value],
                ['status' => StatusEnum::ALLOWED->value]
            ))->make() ->map(fn ($m) => array_merge($m->getAttributes(), [
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]))->all();

        return $this->model->query()->insert($data);
    }
    public function clearData()
    {
        $clear = $this->model->query()->truncate();
        $this->sync();
        return $clear;
    }
    public function sync()
    {
        $this->writeToSheet();
    }
    public function writeToSheet(): bool
    {
        try {
            $sheet = new GoogleSheetClient();
            $spreadsheetId = $this->getDocumentId();

            // Log debugging information
            Log::info('Google Sheets sync started', [
                'spreadsheet_id' => $spreadsheetId,
                'config_url' => $this->getConfigUrl()
            ]);

            if (!$spreadsheetId) {
                Log::error('Google Sheets sync failed: No spreadsheet ID found', [
                    'config_url' => $this->getConfigUrl()
                ]);
                return false;
            }

            $existing = $this->readFromSheet();
            $commentMap = [];

            foreach (array_slice($existing, 1) as $row) {
                $key = strtolower(trim($row[0] ?? ''));
                if ($key) {
                    $commentMap[$key] = $row[4] ?? '';
                }
            }

            $values = []; $headers = ['ID', 'Title', 'Description', 'Status', 'Comment'];
            $values[] = $headers;
            foreach (DataItem::query()->allowed()->orderBy('id', 'asc')->get() as $value) {
                $key = strtolower(trim($value->id ?? ''));

                $values[] = [
                    $value->id,
                    $value->title,
                    $value->description ?? '',
                    $value->status_label,
                    $commentMap[$key] ?? ''
                ];
            }

            Log::info('Google Sheets sync data prepared', [
                'values_count' => count($values),
            ]);

            $sheetRange = '!A1:Z10000';
            $sheet->flush($spreadsheetId, $sheetRange);
            $sheet->writeAll($spreadsheetId, $sheetRange, $values);

            Log::info('Google Sheets sync completed successfully');
            return true;
        }
        catch (\Exception $e) {
            Log::error('Google Sheets sync failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
    private function readFromSheet()
    {
        $id = $this->getDocumentId();
        if (!is_null($id)) {
            $googleDocClient = new GoogleSheetClient();
            return $googleDocClient->readAll($id, '!A1:Z10000');
        }

        return [];
    }
}
