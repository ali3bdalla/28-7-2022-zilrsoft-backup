<?php


namespace App\Http\Requests\Helper;


use App\Attachment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

trait AttachmentsUploaderHelper
{
    private $destination_upload_folder_path;

    public function upload_attachment($parent, $file, $path = 'products'): Attachment
    {
        $this->create_destination_dir($path, $parent);
        $result = $this->upload_one_file($file);
//        return $result;
        return $this->create_db_row($parent, $result);
    }

    /**
     * @param $path
     * @param $parent
     *
     * @return string
     */
    private function create_destination_dir($path, $parent = null)
    {
        $dt = Carbon::now();
//        $dropbox_base_folder = config('filesystems.disks.dropbox.base_folder', '');
//        $dropbox_base_folder
//        if ($parent != null) {
//
//            $this->destination_upload_folder_path = $dropbox_base_folder . '/' . $path . '/' . $dt->toDateString()
//                . '_' . $parent->id;
//        } else {
        $this->destination_upload_folder_path =  $path . '/' . $dt->toDateString();
//        }

        Storage::makeDirectory($this->destination_upload_folder_path);

        return $this->destination_upload_folder_path;
    }

    /**
     * @param $file
     *
     * @return array
     */
    public function upload_one_file($file)
    {

        $path = $file->store($this->destination_upload_folder_path);
        $size = $file->getClientSize();
        $url = Storage::url($path);

        return [
            'path' => $path,
            'size' => $size,
            'extension' => $file->guessClientExtension(),
            'url' => $url,
        ];
    }

    public function create_db_row($parent, $result)
    {

        return $parent->attachments()->create([
            'url' => $result['url'],
            'actual_path' => $result['path'],
            'size' => $result['size'],
            'type' => $result['extension']
        ]);
    }

    /**
     * @param $parent
     */
    private function delete_attachments($parent)
    {
        $attachments = $parent->attachments->whereIn('id', collect($this->attachments)->pluck('id')->toArray());
        Storage::delete($attachments->pluck('actual_path')->toArray());
        foreach ($attachments as $attachment) {
            $attachment->delete();
        }
    }

    /**
     * @param $parent
     * @param string $path
     * @return array
     */
    private function upload_attachments($parent, $path = 'products')
    {

        $this->create_destination_dir($path, $parent);

        $response = [];
        foreach ($this->attachments as $file) {

            $result = $this->upload_one_file($file);
            $response [] = $this->create_db_row($parent, [
                'url' => $result['url'],
                'actual_path' => $result['path'],
                'size' => $result['size'],
                'type' => $result['extension']
            ]);

        }

        return $response;
    }

}
