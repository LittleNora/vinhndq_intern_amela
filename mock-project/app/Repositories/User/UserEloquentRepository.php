<?php

namespace App\Repositories\User;

use App\Enums\Role;
use App\Models\User;
use App\Repositories\EloquentRepository;
use App\Services\Traits\TUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserEloquentRepository extends EloquentRepository implements UserRepositoryInterface
{
    use TUpload;

    public function getModel(): string
    {
        return User::class;
    }

    public function create(array $attributes, $with = [])
    {
        $model = $this->newObj();

        $model->fill($attributes);

        $result = generate_organization_username_and_email($attributes['name']);

        $model->username = $result['username'];

        $model->organization_email = $result['email'];

        $model->password = Hash::make(config('util.default_password'));

        $model->save();

        return $model->load($with);
    }

    public function update($id, array $attributes, $with = [])
    {
        $model = $this->getModelQuery()->findOrFail($id);

        $model->fill($attributes);

        if ($model->isDirty('email')) {
            $model->email_verified_at = null;

            $model->sendEmailVerificationNotification();

            auth('api')->logout();
        }

        $model->save();

        return $model->load($with);
    }

    public function updatePassword($id, $password)
    {
        $model = $this->getModelQuery()->find($id);

        $model->password = Hash::make($password);

        $model->save();

        return $model;
    }

    public function updateAvatar($id, $avatar, $with = [])
    {
        $model = $this->getModelQuery()->with($with)->find($id);

        $oldAvatar = $model->avatar;

        $model->avatar = $this->uploadFile($avatar);

        $model->save();

        !$oldAvatar ?: $this->deleteFile($oldAvatar);

        return $model;
    }

    public function list(Request $request, $with = [])
    {
        return $this->queryIndex($request)->with($with)->paginate($request->get('limit', 10));
    }

    public function queryIndex(Request $request)
    {
        $query = $this->getModelQuery();

        $orderBys = [
            'id',
            'name',
            'email',
            'username',
            'organization_email',
            'created_at',
            'updated_at',
        ];

        if ($request->has('order_by') && in_array($request->get('order_by'), $orderBys)) {
            $query->orderBy($request->get('order_by'), $request->get('order_type', 'desc'));
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->get('email') . '%');
        }

        if ($request->has('username')) {
            $query->where('username', 'like', '%' . $request->get('username') . '%');
        }

        if ($request->has('organization_email')) {
            $query->where('organization_email', 'like', '%' . $request->get('organization_email') . '%');
        }

        if ($request->has('division_id')) {
            $query->where('division_id', $request->get('division_id'));
        }

        return $query;
    }

    public function searchForUser(Request $request, $with = [])
    {
        return $this->queryIndex($request)->with($with)
            ->where('role', Role::STAFF->value)
            ->orderByDesc('id');
    }
}
