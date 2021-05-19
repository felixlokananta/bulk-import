<?php

namespace App\Jobs;

use App\Models\Repositories\ImportJobRecordRepository;
use App\Models\Repositories\ImportJobRepository;
use App\Models\Repositories\OrderRepository;
use App\Services\FileManagementService;
use App\Services\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;

class ProcessBulkImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $job;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($job)
    {
        $this->job = $job;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        ImportJobRepository $importJobRepository,
        ImportJobRecordRepository $importJobRecordRepository,
        OrderService $orderService

    )
    {
        $reader = Reader::createFromPath($this->csvPath);
        $header = $reader->getHeader();
        $orders = $reader->getRecords($header);

        foreach ($orders as $key => $order) {
            $recordLog = [
                'job_id' => $this->job->id,
                'record' => $order
            ];
            if (!$orderService->checkIfOrderAccurate($order, $recordLog)) {
                $recordLog['status'] = 'failed';
                $importJobRecordRepository->create($recordLog);
            } else {
                $orderService->processOrder($order, $recordLog);
            }
        }

        
    }
}
