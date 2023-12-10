<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadTrait
{
    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $filename = null, $size = null, $disk = 'public')
    {
        $name = !is_null($filename) ? $filename : Str::random(25);

        $file = $uploadedFile->storeAs($folder, $name . '.' . $uploadedFile->getClientOriginalExtension(), $disk);
        return $file;
    }

    /**
     * deleteOne
     *
     * @param  mixed $url
     * @return void
     */
    public function deleteOne($url)
    {
        if (Storage::exists('public/' . $url)) {
            Storage::delete('public/' . $url);
        }
    }
    /**
     * deleteMany
     *
     * @param  mixed $url
     * @return void
     */
    public function deleteMany($url)
    {
        $urls = $url->map(function ($value) {
            return 'public/' . $value;
        });
        Storage::delete($urls);
    }
}
