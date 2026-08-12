<?php

namespace App\Requests\Image;

use App\Requests\BaseApiRequest;

/**
 * @class CreateImageRequest
 *
 * @package App\Requests\Image
 */
class CreateImageRequest extends BaseApiRequest
{
    /**
     * rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'image' => ['required', 'file', 'image', 'mimes:jpeg,jpg,png', 'max:5120']
        ];
    }
}
