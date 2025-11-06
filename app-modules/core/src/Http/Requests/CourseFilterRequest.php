<?php

namespace Modules\Core\Http\Requests;

use App\Http\Requests\BasePageableRequest;
use Modules\Core\Http\Data\CourseFilterData;


class CourseFilterRequest extends BasePageableRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'min:1', 'max:255'],
            'owner' => ['sometimes', 'required', 'int', 'min:1'],
            'createdAfter' => ['sometimes', 'required', 'date_format:Y-m-d', 'before:createdBefore'],
            'createdBefore' => ['sometimes', 'required', 'date_format:Y-m-d', 'after:createdAfter'],
        ];
    }

    private array $defaultValues = [
        'title' => null,
        'owner' => null,
        'createdAfter' => null,
        'createdBefore' => null,
    ];

    protected function allowedSortFields(): array
    {
        return [
            'title',
            'created_at',
        ];
    }

    public function toCourseFilterData(): CourseFilterData
    {
        return new CourseFilterData($this->validated(), $this->defaultValues);
    }
}
