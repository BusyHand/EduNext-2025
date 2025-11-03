<?php

namespace Modules\Auth\Http\Mappers;

use Modules\Auth\Http\Data\UserCredentialData;
use Modules\Auth\Models\UserCredential;
use Modules\Core\Http\Data\CourseData;
use Modules\Core\Models\Course;

class UserCredentialMapper
{
    public function toModel(UserCredentialData $data): UserCredential
    {
        return new UserCredential([
            'user_id' => $data->user_id,
            'password' => $data->password,
            'created_by' => $data->created_by,
            'updated_by' => $data->updated_by,
            'deleted_by' => $data->deleted_by,
        ]);
    }
}
