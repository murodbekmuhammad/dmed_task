<?php

namespace App\Requests\Image;

use App\Requests\BaseApiRequest;

class CreateImageRequest extends BaseApiRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'image' => ['required', 'file', 'image', 'mimes:jpeg,jpg,png', 'max:5120']
        ];
    }
}
