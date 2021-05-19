<?php

namespace App\Models\Repositories;

use App\Models\ImportJob;

Class ImportJobRepository extends BaseRepository
{
    public function __construct(ImportJob $importJob)
    {
        $this->model = $importJob;
    }    
}
