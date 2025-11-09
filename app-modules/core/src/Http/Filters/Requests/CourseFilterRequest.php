<?php

namespace Modules\Core\Http\Filters\Requests;

use App\Http\Requests\BasePageableRequest;
use Czim\Filter\FilterData;


class CourseFilterRequest extends BasePageableRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'min:1', 'max:255'],
            'owner' => ['sometimes', 'required', 'int', 'min:1'],
            'createdAfter' => ['sometimes', 'required', 'date_format:Y-m-d', 'before_or_equal:createdBefore'],
            'createdBefore' => ['sometimes', 'required', 'date_format:Y-m-d', 'after_or_equal:createdAfter'],
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

    public function toFilterData(): FilterData
    {
        return new FilterData($this->validated(), $this->defaultValues);
    }
}
