<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessBulkImport;
use App\Models\Order;
use App\Models\Repositories\ImportJobRepository;
use Illuminate\Http\Request;
use App\Services\FileManagementService;

class OrderController extends Controller
{
    private $fileManagementService;
    private $importJobRepository;

    public function __construct(FileManagementService $fileManagementService, ImportJobRepository $importJobRepository)
    {
        $this->fileManagementService = $fileManagementService;
        $this->importJobRepository = $importJobRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadOrderCsvFile(Request $request)
    {
        $maxSize = config('main.csv_max_size');

        $validatedData = $request->validate([
            'file' => 'required|mimes:csv,txt|max:' . $maxSize
        ]);

        $fileData = [
            'name' => $request->file('file')->getClientOriginalName(),
            'path' => 'uploads/'
        ];

        $storagePath = $this->fileManagementService->upload($request->file('file'), $fileData);

        if ($storagePath) {
            $importJobData = [
                'status' => 'dispatched',
                'csv_path' => $storagePath,
            ];
            $importJob =  $this->importJobRepository->create($importJobData);
            dispatch(new ProcessBulkImport($importJob));
        }

        return response()->json($importJob, 200);
    }
}
