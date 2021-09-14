<?php

namespace App\Repository\Eloquent;

use App\Dto\InvoiceDto;
use App\Dto\OrderDto;
use App\Enums\InvoiceTypeEnum;
use App\Models\Manager;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use App\Models\User;
use App\Repository\InvoiceRepositoryContract;
use App\Repository\OrderRepositoryContract;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderRepositoryContract
{
    private InvoiceRepositoryContract $invoiceRepositoryContract;

    public function __construct(Order $model, InvoiceRepositoryContract $invoiceRepositoryContract)
    {
        parent::__construct($model);
        $this->invoiceRepositoryContract = $invoiceRepositoryContract;
    }


    public function createOrder(User $client, array $items, ?ShippingMethod $shippingMethod = null, ?ShippingAddress $shippingAddress = null, ?string $paymentMethodId = null): ?Order
    {
        return DB::transaction(function () use ($client, $items, $shippingMethod, $shippingAddress, $paymentMethodId) {
            $invoiceDto = new InvoiceDto(Manager::find(1), $client, InvoiceTypeEnum::sale(), $items, true, true);
            $draftInvoice = $this->invoiceRepositoryContract->createInvoice($invoiceDto);
            $orderDto = new OrderDto($draftInvoice, $shippingMethod, $shippingAddress, $paymentMethodId);
            return Order::factory()->setDto($orderDto)->create();
        });
    }
}
