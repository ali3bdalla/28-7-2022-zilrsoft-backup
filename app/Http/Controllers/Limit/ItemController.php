<?php

namespace App\Http\Controllers\Limit;

use App\Attachment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\Attachment\DeleteAttachmentRequest;
use App\Http\Requests\Accounting\Item\UploadAttachmentRequest;
use App\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::paginate(100);
        return view('limit.items.index', [
            'items' => $items
        ]);
    }

    public function edit(Item $item)
    {
        return view('limit.items.edit', [
            'item' => $item->load('attachments')
        ]);
    }

    public function update(UploadAttachmentRequest $request, Item $item)
    {
        return $request->upload($item);
    }

    public function delete_attachment(DeleteAttachmentRequest $request,  Item $item,Attachment $attachment)
    {
        return $request->delete($attachment);
    }
    //
}
