<?php

namespace App\Http\Requests\Ums;

use Illuminate\Foundation\Http\FormRequest;

class UmsMemberReceivedAddressRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'           => 'required',
            'phoneNumber'   => 'required',
            //            'post_code'      => 'required',
            'province'       => 'required',
            'city'           => 'required',
            'region'         => 'required',
            'detailAddress' => 'required',
        ];
    }
}
