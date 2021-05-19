<?php

namespace App\Models\Repositories;

use App\Models\OrderItems;

Class OrderItemsRepository extends BaseRepository
{
    public function __construct(OrderItems $orderItems)
    {
        $this->model = $orderItems;
    }    
}
