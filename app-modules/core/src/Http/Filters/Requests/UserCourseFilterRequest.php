<?php

namespace Modules\Core\Http\Filters\Requests;

use App\Http\Requests\BasePageableRequest;
use Czim\Filter\FilterData;


class UserCourseFilterRequest extends BasePageableRequest
{
    public function rules(): array
    {
        return [
            'user' => ['sometimes', 'required', 'int', 'min:1'],
            'course' => ['sometimes', 'required', 'int', 'min:1'],
        ];
    }

    private array $defaultValues = [
        'user' => null,
        'course' => null,
    ];

    protected function allowedSortFields(): array
    {
        return [
            'created_at',
        ];
    }

    public function toFilterData(): FilterData
    {
        return new FilterData($this->validated(), $this->defaultValues);
    }
}
