<?php

namespace Modules\Core\Http\Filters\Requests;

use App\Http\Requests\BasePageableRequest;
use Czim\Filter\FilterData;
use Illuminate\Validation\Rule;
use Modules\Core\Enums\TaskStatuses;


class TaskFilterRequest extends BasePageableRequest
{
    public function rules(): array
    {
        return [
            'user' => ['sometimes', 'required', 'int', 'min:1'],
            'lesson' => ['sometimes', 'required', 'int', 'min:1'],
            'course' => ['sometimes', 'required', 'int', 'min:1'],
            'status' => ['sometimes', 'required', 'string', 'max:255', Rule::in([
                TaskStatuses::GENERATING,
                TaskStatuses::COMPLETED,
                TaskStatuses::REJECTED,
                TaskStatuses::PENDING_SOLUTION,
                TaskStatuses::UNDER_REVIEW,
            ])],
            'createdAfter' => ['sometimes', 'required', 'date_format:Y-m-d', 'before_or_equal:createdBefore'],
            'createdBefore' => ['sometimes', 'required', 'date_format:Y-m-d', 'after_or_equal:createdAfter'],
        ];
    }

    private array $defaultValues = [
        'user' => null,
        'lesson' => null,
        'course' => null,
        'status' => null,
        'createdAfter' => null,
        'createdBefore' => null,
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
