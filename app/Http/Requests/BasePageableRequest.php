<?php

namespace App\Http\Requests;

use App\Http\Data\PageableData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

abstract class BasePageableRequest extends FormRequest
{
    abstract protected function allowedSortFields(): array;

    public function rules(): array
    {
        return [
            'page' => ['integer', 'min:1'],
            'size' => ['integer', 'min:1', 'max:100'],
            'sort' => ['nullable', 'string'],
        ];
    }

    public function toPageableData(): PageableData
    {
        return new PageableData(
            sorts: $this->getSort(),
            page: $this->getPage(),
            size: $this->getSize(),
        );
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $sorts = $this->getSort();
            $allowedSortFields = array_merge($this->allowedSortFields(), ['asc', 'desc']);
            foreach ($sorts as $sort) {
                if (!in_array($sort['field'], $allowedSortFields, true)) {
                    $validator->errors()->add(
                        'sort',
                        "Поле сортировки `{$sort['field']}` не разрешено"
                    );
                }
            }
        });
    }

    private function getPage(): int
    {
        return (int)$this->input('page', 1);
    }

    private function getSize(): int
    {
        return (int)$this->input('size', 15);
    }

    private function getSort(): array
    {
        $sortParam = $this->input('sort');
        if (!$sortParam) {
            return [];
        }

        $parts = explode(',', $sortParam);
        $sorts = [];

        for ($i = 0; $i < count($parts); $i += 1) {
            $field = $parts[$i];
            $direction = $i + 1 < count($parts) ? $parts[$i + 1] : 'asc';
            $direction = $direction == 'asc' || $direction == 'desc' ? $direction : 'asc';
            if ($i + 1 < count($parts) && ($parts[$i + 1] == 'asc' || $parts[$i + 1] == 'desc')) {
                $i++;
            }

            $sorts[] = [
                'field' => $field,
                'direction' => $direction,
            ];
        }
        return $sorts;
    }
}
