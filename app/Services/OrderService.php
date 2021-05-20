<?php

namespace App\Services;

use App\Models\Repositories\CustomerRepository;
use App\Models\Repositories\OrderItemsRepository;
use App\Models\Repositories\OrderRepository;
use App\Models\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $orderRepository;
    protected $orderItemsRepository;
    protected $productRepository;
    protected $customerRepository;

    /**
     * @param OrderRepository $orderRepository
     * @param ProductRepository $productRepository
     * 
     */
    public function __construct(OrderRepository $orderRepository,
        OrderItemsRepository $orderItemsRepository,
        ProductRepository $productRepository,
        CustomerRepository $customerRepository,
    )
    {
        $this->orderRepository = $orderRepository;
        $this->orderItemsRepository = $orderItemsRepository;
        $this->$productRepository = $productRepository;
        $this->$customerRepository = $customerRepository;
    }

    /**
     * check the user email format
     * check the correctnes of order ammount
     * etc
     *
     * @return Boolean
     */
    public function checkIfOrderAccurate($order, &$recordLog)
    {
        // check the order here
        if ($this->allPassed) {
            return true;
        }

        $recordLog['reason'] = 'reason why it failed';

        return false;
    }

    /**
     * Process the correct order
     *
     * @return Boolean
     */
    public function processOrder($orderRecord, &$recordLog)
    {
        try {
            DB::beginTransaction();
            $customerData = [
                'email' => $orderRecord['email'],
                'first_name' => $orderRecord['first_name'],
                'last_name' => $orderRecord['last_name']
            ];
            $customer = $this->customerRepository->firstOrCreate($customerData);

            $productData = [
                'name' => $orderRecord['product_name'],
                'price' => $orderRecord['price']
            ];

            $product = $this->productRepository->firstOrCreate($productData);

            $orderData = [
                'customer_id' => $customer->id,
                'order_date' => $orderRecord['order_date'],
                'order_status' => $orderRecord['status'],
                'shipped_date'=> $orderRecord['shipped_date']
            ];

            $order = $this->orderRepository->create($orderData);

            $orderItemData = [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $orderRecord['quantity'],
                'list_price' => $product->price
            ];

            $orderItem = $this->orderItemsRepository->create($orderItemData);
            DB::commit();    
        } catch (\Exception $e) {
            DB::rollBack();
            $recordLog['reason'] = $e->getMessage();
        }
    }

}
