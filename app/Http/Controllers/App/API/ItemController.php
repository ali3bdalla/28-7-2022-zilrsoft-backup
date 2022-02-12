<?php

namespace App\Http\Controllers\App\API;

use Algolia\AlgoliaSearch\SearchIndex;
use App\Http\Controllers\Controller;
use App\Http\Requests\Item\UploadItemImagesRequest;
use App\Http\Requests\Items\FetchItemsRequest;
use App\Http\Requests\Items\QueryItemsRequest;
use App\Http\Requests\Items\ValidateSerialRequest;
use App\Http\Resources\InvoiceItem\InvoiceItemCollection;
use App\Models\Attachment;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ItemController extends Controller
{

    public function search(Request $request): LengthAwarePaginator
    {
        return Item::search("a", function (SearchIndex $algolia, string $query, array $options) {
            return $algolia->search($query, $options);
        })->paginate(20);
    }

    public function index(FetchItemsRequest $request)
    {
        return $request->getData();
    }

    public function transactions(Item $item, Request $request): InvoiceItemCollection
    {
        $query = $item->pipeline();
        if (
            $request->has('start_at') && $request->filled('start_at') && $request->has('end_at') &&
            $request->filled('end_at')
        ) {
            $_startDate = Carbon::parse($request->input("start_at"));
            $_endDate = Carbon::parse($request->input("end_at"));

            if ($_endDate === $_startDate) {
                $query = $query->whereDate('created_at', $_startDate);
            } else {
                $query = $query->whereBetween(
                    'created_at',
                    [
                        $_startDate,
                        $_endDate,
                    ]
                );
            }
        }


        if ($request->has('perPage') && $request->filled('perPage') && intval($request->input("perPage")) >= 1) {
            $items = $query->with('user', 'creator')->paginate((float)($request->input('perPage')));
        } else {
            $items = $query->with('user', 'creator')->paginate(50);
        }


        return new InvoiceItemCollection($items);
    }


    public function ValidateSalesSerial(ValidateSerialRequest $request)
    {
        return $request->sale();
    }

    public function ValidateReturnSalesSerial(ValidateSerialRequest $request)
    {
        return $request->returnSale();
    }


    /**
     * @throws ValidationException
     */
    public function ValidatePurchasesSerial(ValidateSerialRequest $request)
    {
        return $request->purchase();
    }


    public function ValidateReturnPurchasesSerial(ValidateSerialRequest $request)
    {
        return $request->returnPurchase();
    }

    public function querySearch(QueryItemsRequest $request)
    {
        return $request->results();
    }


    /**
     * @param Request $request
     *
     * @return void
     */
    public function validateUniqueBarcode(Request $request)
    {
        $request->validate(
            [
                'barcode' => 'required|string|min:4|exists:items,barcode'
            ]
        );
    }


    public function uploadImages(Item $item, UploadItemImagesRequest $uploadItemImageRequest): array
    {
        $images = [];
        foreach ($uploadItemImageRequest->file('images') as $requestImage)
            $images[] = $uploadItemImageRequest->createImage_ReturnImageInstance($requestImage, $item);
        return $images;
    }

    public function setMaster(Item $item, Attachment $image)
    {
        $image->attachable->attachments()->update([
            'is_main' => false
        ]);
        $image->update([
            'is_main' => true
        ]);
    }

    public function deleteImage(Item $item, Attachment $image)
    {
        Storage::delete($image->actual_path);
        $image->forceDelete();
    }


    public function updateDescription(Request $request, Item $item): RedirectResponse
    {
        $request->validate(
            [
                'description' => 'required|string|min:20',
                'ar_description' => 'required|string|min:20',
            ]
        );


        $item->update($request->only('description', 'ar_description'));
        return back();
    }

    public function addImage(UploadItemImagesRequest $request): array
    {
        $request->validate([
            'item_id' => 'nullable|integer|exists:items,id',
        ]);

        $item = null;
        if ($request->has('item_id') && $request->filled('item_id')) {
            $item = Item::findOrFail($request->input('item_id'));
        }
        $images = [];
        foreach ($request->file('images') as $requestImage)
            $images[] = $request->createImage_ReturnImageInstance($requestImage, $item);
        return $images;
    }
}
