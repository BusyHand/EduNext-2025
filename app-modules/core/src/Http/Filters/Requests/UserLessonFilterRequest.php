<?php

namespace Modules\Core\Http\Filters\Requests;

use App\Http\Requests\BasePageableRequest;
use Czim\Filter\FilterData;


class UserLessonFilterRequest extends BasePageableRequest
{
    public function rules(): array
    {
        return [
            'user' => ['sometimes', 'required', 'int', 'min:1'],
            'course' => ['sometimes', 'required', 'int', 'min:1'],
            'lesson' => ['sometimes', 'required', 'int', 'min:1'],
            'isCompleted' => ['sometimes', 'required', 'in:true,false',],
        ];
    }

    private array $defaultValues = [
        'user' => null,
        'course' => null,
        'lesson' => null,
        'isCompleted' => null,
    ];

    protected function allowedSortFields(): array
    {
        return [
            'progress',
            'is_completed',
            'created_at',
        ];
    }

    public function toFilterData(): FilterData
    {
        return new FilterData($this->validated(), $this->defaultValues);
    }
}
