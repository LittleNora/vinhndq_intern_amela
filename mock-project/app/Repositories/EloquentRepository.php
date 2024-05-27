<?php

namespace App\Repositories;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

abstract class EloquentRepository implements RepositoryInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * EloquentRepository constructor.
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * get model
     * @return string
     */
    abstract public function getModel();

    abstract public function queryIndex(Request $request);

    /**
     * Set model
     */
    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function newObj()
    {
        return new (call_user_func([$this, 'getModel']))();
    }

    /**
     * Get All
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll($with = [])
    {
        return $this->getModelQuery()->with($with)->get();
    }

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id, $with = [])
    {
        return is_closure($id) ? call_user_func($id) : $this->getModelQuery()->with($with)->find($id);
    }

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes, $with = [])
    {
        return $this->getModelQuery()->create($attributes)->load($with);
    }

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return bool|mixed
     */
    public function update($id, array $attributes, $with = [])
    {
        $result = $this->find($id, $with);

        if ($result) {
            $result->update($attributes);

            return $result;
        }

        return false;
    }

    /**
     * Delete
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $result = is_closure($id) ? call_user_func($id) : $this->find($id);

        if ($result) {
            $result->delete();

            return true;
        }

        return false;
    }

    /**
     * Find by field
     * @param $field
     * @param array $value
     * @return mixed
     */
    public function findBy($field, array|null $value = [], $with = [])
    {
        $query = $this->getModelQuery();

        is_array($field) ? $query->with($value)->where($field) : $query->with($with)->where($field, $value);

        return $query->first();
    }

    /**
     * Find by field
     * @param $field
     * @param array $value
     * @return mixed
     */
    public function createMany($data)
    {
        return $this->getModelQuery()->insert($data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function trashedList(Request $request)
    {
        if (!is_using_soft_deletes($this->getModel())) {
            return false;
        }

        return $this->queryIndex($request)->onlyTrashed()->paginate($request->get('limit', 10));
    }

    /**
     * @param $id
     * @param $with
     * @return mixed
     */
    public function restore($id, $with = [])
    {
        if (!is_using_soft_deletes($this->getModel())) {
            return false;
        }

        if (!$division = $this->getModelQuery()->onlyTrashed()->find($id)) {
            return false;
        }

        $division->restore();

        return $division->load($with);
    }

    /**
     * Get model query
//     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getModelQuery()
    {
        return $this->getModel()::query();
    }

}
