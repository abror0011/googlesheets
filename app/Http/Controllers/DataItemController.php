<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataRequest;
use App\Services\DataItemService;
use App\StatusEnum;
use Illuminate\Http\Request;

class DataItemController extends Controller
{
    private DataItemService $service;
    public function __construct(DataItemService $service){
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('data-items.index',[
            'dataItems' => $this->service->getAll(),
            'config' => $this->service->getConfigUrl()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statusOptions = StatusEnum::options();
        return view('data-items.create', compact('statusOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DataRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('data-items.index')
            ->with('success', 'Data item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dataItem = $this->service->getOne($id);
        return view('data-items.show', compact('dataItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dataItem = $this->service->getOne($id);
        $statusOptions = StatusEnum::options();
        return view('data-items.edit', compact('dataItem', 'statusOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DataRequest $request, string $id)
    {
        $this->service->update($id, $request->validated());
        return redirect()->route('data-items.index')
            ->with('success', 'Data item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('data-items.index')
            ->with('success', 'Data item deleted successfully.');
    }
    public function generateData()
    {
        $this->service->generateData();
        return redirect()->route('data-items.index')
            ->with('success', '1000 data items generated successfully.');
    }
    public function clearData()
    {
        $this->service->clearData();
        return redirect()->route('data-items.index')
            ->with('success', 'All data items cleared successfully.');
    }
}
