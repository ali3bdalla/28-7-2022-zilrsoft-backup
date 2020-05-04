<?php

namespace App\Http\Controllers\Accounting;

use App\Attachment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Helper\AttachmentsUploaderHelper;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    use AttachmentsUploaderHelper;

    public function delete(Attachment $attachment, Request $request)
    {

    }
    //
}
