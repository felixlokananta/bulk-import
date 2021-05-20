<?php

namespace App\Jobs;

use App\Events\BulkImportOrderProcessed;
use App\Models\Repositories\ImportJobRecordRepository;
use App\Models\Repositories\ImportJobRepository;
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
        $reader->setHeaderOffset(0);
        $orders = $reader->getRecords();
        $numOfFailed = 0;
        $numOfImported = 0;

        foreach ($orders as $key => $order) {
            $recordLog = [
                'job_id' => $this->job->id,
                'record' => $order
            ];
            if ($orderService->checkIfOrderAccurate($order, $recordLog)) {
                $orderService->processOrder($order, $recordLog);
            }

            if (is_null($recordLog['reason'])) {
                $recordLog['status'] = 'imported';
                $numOfImported++;
            } else {
                $recordLog['status'] = 'failed';
                $numOfFailed++;
            }

            $importJobRecordRepository->create($recordLog);

            $progress = [
                'order_processed' => $key,
                'order_count' => count($reader),
                'imported_order' => $numOfImported,
                'failed_order' => $numOfFailed
            ];
            // create a new event for broadcast right here. 
            event(new BulkImportOrderProcessed($progress, $this->job));
        }

        // update the dispatched job.
        $importJobRepository->update($this->job->id,['status' => 'finished']);
    }
}
