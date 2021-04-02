<?php

namespace Eleven59\BackpackImageTraits\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic;

trait HasImageFields
{
    protected function uploadImageData($post_value, $options = [])
    {
        $disk = isset($options['disk']) ? $options['disk'] : 'public';
        $delete_path = isset($options['delete_path']) ? $options['delete_path'] : null;

        if(is_null($post_value))
        {
            if(!empty($delete_path))
            {
                Storage::disk($disk)->delete($delete_path);
            }
            return null;
        }

        $destination_path = isset($options['directory']) ? $options['directory'] : $this->table;
        $quality = isset($options['quality']) ? $options['quality'] : 65;
        $format = isset($options['format']) ? $options['format'] : 'jpg';
        $callback = isset($options['callback']) && is_callable($options['callback']) ? $options['callback'] : '';
        $transformations = isset($options['transformations']) && is_callable($options['transformations']) ? $options['transformations'] : '';

        if (Str::startsWith($post_value, 'data:image'))
        {
            $image = ImageManagerStatic::make($post_value)->encode($format, $quality);
            $filename = md5($post_value.time()) . '.' . $format;

            if(!empty($transformations)) {
                $transformations($image);
            }

            $storage_path = Storage::disk($disk)->path($destination_path.'/'.$filename);
            $image->save($storage_path, $quality, $format);

            if(!empty($callback)) {
                return $callback($filename);
            }

            return Storage::disk($disk)->url($destination_path.'/'.$filename);
        }

        return $post_value;
    }
}
