<?php

namespace App\Http\Requests\Accounting\Item;

use App\Http\Requests\Helper\AttachmentsUploaderHelper;
use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpClient\HttpClient;

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
        $attachment = $this->upload_attachment($item, $this->file('attachment'), 'items');
//        $attachment->update([
//            'details' => self::getThumbnailBitmap($attachment->actual_path)
//        ]);
        return $attachment->fresh();

    }

    public static function getThumbnailBitmap($path, $size = 'w64h64')
    {
        if (!in_array($size, ['w32h32', 'w64h64', 'w128h128', 'w256h256', 'w480h320', 'w640h480', 'w960h640', 'w1024h768', 'w2048h1536'])) {
            $size = "w32h32";
        }


        $client = HttpClient::create();

        $response = $client->request('POST', 'https://content.dropboxapi.com/2/files/get_thumbnail_batch', [
            'headers' => [
                'Authorization' => "Token " . config('filesystems.disks.dropbox.token'),
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                    "entries" => [
                        "path" => $path,
                        "format" => "jpeg",
                        "size" => $size,
                        "mode" => "strict"
                    ]]
            ),

        ]);


//        if ($response->getStatusCode() == 200)
        return $response->getInfo();


        return null;

    }

}
