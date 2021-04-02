<?php

namespace Eleven59\BackpackImageTraits\Traits;

use Illuminate\Support\Str;

trait HasImagesInRepeatableFields
{
    public function uploadRepeatableImageData($post_value, $options = [])
    {
        $images = $post_value;
        foreach($images as &$json_info)
        {
            $json_info = (array)$json_info;
            foreach($json_info as $index => $data)
            {
                if(Str::startsWith($data, 'data:image'))
                {
                    $json_info[$index] = $this->uploadImageData($data, $options);
                }
            }
        }

        return json_encode($images);
    }
}
