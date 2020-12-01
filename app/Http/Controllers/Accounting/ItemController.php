<?php

namespace App\Http\Controllers\Accounting;

use App\Components\Loader\Item\Transactions\ItemTransactionsLoader;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\Item\ActivateItemsRequest;
use App\Http\Requests\Accounting\Item\CreateItemRequest;
use App\Http\Requests\Accounting\Item\DatatableRequest;
use App\Http\Requests\Accounting\Item\UpdateItemRequest;
use App\Http\Requests\Accounting\Item\UploadAttachmentRequest;
use App\Http\Resources\InvoiceItem\InvoiceItemActivityCollection;
use App\Models\Category;
use App\Models\InvoiceItems;
use App\Models\Item;
use App\Models\ItemSerials;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Request;

class ItemController extends Controller
{

// 
    /**
     * ItemController constructor.
     */
    public function __construct()
    {
        $this->middleware(['permission:create item|edit item|view item|delete item|view item transactions']);
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $this->middleware("permission:view item");

        $categories = Category::all();
        $creators = Manager::all();

        return view('accounting.items.index', compact('categories', 'creators'));
    }

    /**
     * @param DatatableRequest $request
     *
     * @return LengthAwarePaginator
     */
    public function datatable(DatatableRequest $request)
    {
        return $request->data();
    }


    /**
     * @return Factory|View
     */
    public function create()
    {
        $this->middleware("permission:create item");
        $isClone = false;
        $chats = Category::mainOnly()->get();
        $categories = [];
        foreach ($chats as $category) {
            $category['children'] = Category::getAllParentNestedChildren($category);
            $categories[] = $category;
        }
        $vendors = User::where('is_vendor', true)->get();

        return view('accounting.items.create', compact('categories', 'isClone', 'vendors'));

    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function validate_barcode(Request $request)
    {
        $this->middleware("permission:create item");
        $request->validate([
            'barcode' => 'required|string|min:4|organization_unique:App\Models\Item,barcode'
        ]);

        $result = Item::where('barcode', $request->barcode)->get();

        return $result;
    }

    /**
     * @param CreateItemRequest $request
     *
     * @return mixed
     */
    public function store(CreateItemRequest $request)
    {
        return $request->save();
    }

    /**
     * @param Item $item
     *
     * @return Factory|View
     */
    public function edit(Item $item)
    {

//        return UploadAttachmentRequest::getThumbnailBitmap('/zilrsoft/items/2020-05-06/1281/uSwmWoEPDRxVwGBTB8m5YC1g2XVf4OvSJaId9ljC.png');
        $item = $item->load('attachments');
//        return $item;
        $this->middleware("permission:edit item");
        $isClone = 0;
        $chats = Category::mainOnly()->get();
        $categories = [];
        foreach ($chats as $category) {
            $category['children'] = Category::getAllParentNestedChildren($category);
            $categories[] = $category;
        }
        $vendors = User::where('is_vendor', true)->get();
        return view('accounting.items.edit', compact('categories', 'isClone', 'vendors', 'item'));


    }

    public function destroy(Item $item)
    {

        $old = InvoiceItems::where('item_id', $item->id)->count();
        if ($old === 0) {
            $item->filters()->delete();
            $item->forceDelete();

        } else {
            $item->delete();
        }
    }

    /**
     * @param Item $item
     *
     * @return Factory|View
     */
    public function clone(Item $item)
    {
        $this->middleware("permission:edit item");
        $isClone = 1;
        $chats = Category::mainOnly()->get();
        $categories = [];
        foreach ($chats as $category) {
            $category['children'] = Category::getAllParentNestedChildren($category);
            $categories[] = $category;
        }
        $vendors = User::where('is_vendor', true)->get();
        return view('accounting.items.edit', compact('categories', 'isClone', 'vendors', 'item'));


    }

    /**
     * @param UpdateItemRequest $request
     * @param Item $item
     *
     * @return mixed
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        return $request->save($item);
        //
    }

    /**
     * @param Item $item
     *
     * @return Factory|View
     */
    public function transactions(Item $item)
    {

        $item->total_stock_amount = $item->cost * $item->available_qty;
        return view('accounting.items.transactions', compact('item'));

    }

    /**
     * @param Item $item
     * @param Request $request
     *
     * @return array|Collection
     */
    public function transactions_datatable(Item $item, Request $request)
    {
        return new InvoiceItemActivityCollection($item->history()->paginate(10000));
        $transaction = new ItemTransactionsLoader($item, $request);
        return $transaction->run();
//
//			if ($request->has('startDate') && $request->filled('startDate') && $request->has('endDate') &&
//				$request->filled('endDate')){
//				return $item->stockMovement(['startDate' => $request->startDate,'endDate' => $request->endDate]);
//			}
//
//
//			return collect($item->stockMovement());

    }

    /**
     * @param ActivateItemsRequest $request
     */
    public function activate_items(ActivateItemsRequest $request)
    {
        return $request->activate();
    }

    /**
     * @param Item $item
     *
     * @return Factory|View
     */
    public function view_serials(Item $item)
    {

        $serials = $item->serials()->paginate(20);
        return view('accounting.items.view_serials', compact('item', 'serials'));
    }

    /**
     * @return Factory|View
     */
    public function serial_activities()
    {
        return view('accounting.items.serial.index');
    }

    /**
     * @param Request $request
     *
     * @return Factory|View
     */
    public function show_serial_activities(Request $request)
    {
        $request->validate([
            'serial' => 'required|organization_exists:App\Models\ItemSerials,serial'
        ]);

        $serial = ItemSerials::where('serial', $request->serial)->first();
        $histories = $serial->histories()->orderBy('id','asc')->get();
        return view('accounting.items.serial.show', compact('serial','histories'));
    }

    public function barcode()
    {
        return view('accounting.items.barcode.index');
    }

    public function show_item_barcode(Request $request)
    {

        $request->validate([
            'barcode' => 'required|organization_exists:App\Models\Item,barcode'
        ]);

        $items = Item::where('barcode', $request->barcode)->get();
//			return $items;
        return view('accounting.items.barcode.show', compact('items'));
    }


    public function upload_attachments(UploadAttachmentRequest $request, Item $item)
    {
        return $request->upload($item);
    }


}
