<?php

namespace Modules\Core\Http\Filters\Requests;

use App\Http\Requests\BasePageableRequest;
use Modules\Core\Http\Filters\Data\CourseFilterData;


class CourseFilterRequest extends BasePageableRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'min:1', 'max:255'],
            'owner' => ['sometimes', 'required', 'int', 'min:1',],
            'createdAfter' => ['sometimes', 'required', 'date',],
            'createdBefore' => ['sometimes', 'required', 'date',],
        ];
    }

    protected function allowedSortFields(): array
    {
        return [
            'title',
            'created_at',
        ];
    }

    private array $defaultValues = [
        'title' => null,
    ];

    public function toCourseFilterData(): CourseFilterData
    {
        return new CourseFilterData($this->validated(), $this->defaultValues);
    }
}
