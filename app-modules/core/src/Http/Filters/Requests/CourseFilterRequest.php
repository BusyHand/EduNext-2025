<?php

namespace Modules\Core\Http\Filters\Requests;

use App\Http\Requests\BasePageableRequests;
use Modules\Core\Http\Filters\Data\CourseFilterData;

class CourseFilterRequest extends BasePageableRequests
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'min:1', 'max:255'],
        ];
    }

    public function toCourseFilterData(): CourseFilterData
    {
        return new CourseFilterData($this->validated());
    }
}
