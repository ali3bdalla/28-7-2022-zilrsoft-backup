<?php

namespace App\Http\Requests\Accounting\Item;

use App\Http\Requests\Helper\AttachmentsUploaderHelper;
use App\Item;
use Illuminate\Foundation\Http\FormRequest;

class UploadAttachmentRequest extends FormRequest
{
    use AttachmentsUploaderHelper;


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'attachment' => "required|image"
        ];

    }


    public function upload(Item $item)
    {
        return $this->upload_attachment($item, $this->file('attachment'), 'items');
    }

}
