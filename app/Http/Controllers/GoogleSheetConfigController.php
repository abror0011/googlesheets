<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoogleSheetConfigRequest;
use App\Services\GoogleSheetConfigService;
use Illuminate\Http\Request;

class GoogleSheetConfigController extends Controller
{
    private GoogleSheetConfigService $service;
    public function __construct(GoogleSheetConfigService $service){
        $this->service = $service;
    }
    public function put(GoogleSheetConfigRequest $request)
    {
        $this->service->updateOrCreate($request->validated());
        return redirect()->route('data-items.index')
            ->with('success', 'Google Sheets configuration updated successfully.');
    }
}
