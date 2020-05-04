<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\HttpClient\HttpClient;

class Attachment extends Model
{

    use SoftDeletes;

//    protected $appends = [
//      'thumbnail'
//    ];
    protected $guarded = [];

    public function attachable()
    {
        return $this->morphTo();
    }

    public function getSizeAttribute($value)
    {
        return $this->formatSizeUnits($value);
    }

    private function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function isImage()
    {
        if (in_array($this->type, [
            'png',
            'jpg',
            'jpeg',
            'gif',
        ]))
            return true;


        return false;
    }


    public function getThumbnailAttribute($width = 200, $height = 200, $format = 'jpg')
    {
        $client = HttpClient::create();
        $response = $client->request("POST", "https://content.dropboxapi.com/2/files/get_thumbnail_batch", [
            'headers' => [
                "Authorization" => "Bearer " . config('filesystems.disks.dropbox.token')
            ],
            'body' => [
                "size" => "w$width" . "h" . $height,
                "format" => $format,
                'path' => $this->actual_path
            ]
        ]);

        return $response->getContent();

    }
    //
}
