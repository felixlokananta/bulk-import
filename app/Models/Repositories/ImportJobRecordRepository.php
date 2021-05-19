<?php

namespace App\Models\Repositories;

use App\Models\ImportJobRecord;

Class ImportJobRecordRepository extends BaseRepository
{
    public function __construct(ImportJobRecord $importJobRecord)
    {
        $this->model = $importJobRecord;
    }    
}
