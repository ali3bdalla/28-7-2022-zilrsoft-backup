<?php

namespace App\Http\Controllers\App\API;

use App\Dto\VoucherDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vouchers\FetchVouchersRequest;
use App\Http\Requests\Vouchers\StoreVoucherRequest;
use App\Models\Voucher;
use App\Repository\VoucherRepositoryContract;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{

    private VoucherRepositoryContract $voucherRepositoryContract;

    public function __construct(VoucherRepositoryContract $voucherRepositoryContract)
    {
        $this->voucherRepositoryContract = $voucherRepositoryContract;
    }

    public function index(FetchVouchersRequest $request)
    {
        return $request->getData();
    }

    /**
     */
    public function store(StoreVoucherRequest $request): Voucher
    {
        $userAccount = $request->getUserAccount();
        $walletAccount = $request->getOrganizationAccount();
        $user = $request->getUser();
        $amount = $request->getAmount();
        $type = $request->getType();
        $description = $request->getDescription();
        $voucherDto = new VoucherDto(
            $walletAccount,
            $userAccount,
            Auth::user(),
            $user,
            $amount,
            $type,
            $description
        );
        return
            $this
                ->voucherRepositoryContract
                ->createVoucher($voucherDto);
    }

    public function refund(Voucher $voucher)
    {
        if (!$voucher->isRefundable()) abort(403);
        $refundVoucher = $this
            ->voucherRepositoryContract
            ->refundVoucher($voucher);
        return redirect(route('vouchers.show', $refundVoucher->id));
    }
}
