<?php

namespace App\Clients;

use App\Services\DataItemService;
use Google\Service\Sheets\ClearValuesRequest;
use Google_Client;
use Google_Service_Sheets;
use Illuminate\Support\Facades\Log;

class GoogleSheetClient
{
    public Google_Service_Sheets $service;

    public function __construct()
    {
        $client = new Google_Client();
        $credentials = storage_path('app/sheets/credentials.json');

        if (!file_exists($credentials)) {
            throw new \Exception("Google Sheets credentials file not found at: " . $credentials);
        }

        $client->setAuthConfig($credentials);
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
        $this->service = new Google_Service_Sheets($client);
    }

    /**
     * Read all values from sheet (sheetId, range like 'Sheet1')
     */
    public function readAll(string $spreadsheetId, string $range)
    {
        $resp = $this->service->spreadsheets_values->get($spreadsheetId, $range);
        return $resp->getValues() ?? [];
    }

    /**
     * Write values (overwrite) starting at range (e.g. 'Sheet1!A1')
     */
    public function writeAll(string $spreadsheetId, string $range, array $values)
    {
        $body = new \Google_Service_Sheets_ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'RAW'];
        $this->service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
        return $this->service->spreadsheets_values
            ->update($spreadsheetId, $range, $body, $params);
    }

    public function flush(string $spreadsheetId, string $range)
    {
        return $this->service->spreadsheets_values->clear($spreadsheetId, $range, new ClearValuesRequest());
    }
    public function fetchData(string $sheetId, $count = null)
    {

        try {
            $response = $this->service->spreadsheets_values->get($sheetId, 'A:Z');
            $values = $response->getValues() ?? [];

            if ($count) {
                $values = array_slice($values, 0, $count + 1); // +1 for headers
            }

            return $values;
        } catch (\Exception $e) {
            Log::error('Failed to fetch Google Sheets data: ' . $e->getMessage());
            return [];
        }
    }
}
