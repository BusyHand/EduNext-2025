<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BasePageableRequests extends FormRequest
{


    public function getPaginateData(): PageableData
    {
        return new PageableData(
            sorts: null,
            page: null,
            size: null,
        );
    }
}
