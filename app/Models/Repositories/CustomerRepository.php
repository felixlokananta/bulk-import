<?php

namespace App\Models\Repositories;

use App\Models\Customer;

Class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $customer)
    {
        $this->model = $customer;
    }    
}
