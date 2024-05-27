<?php

namespace App\Repositories;

interface RepositoryInterface
{

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function newObj();

    /**
     * Get all
     * @return mixed
     */
    public function getAll($with = []);

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id, $with = []);

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes, $with = []);

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, array $attributes, $with = []);

    /**
     * Delete
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Find by field
     * @param $field
     * @param $value
     * @return mixed
     */
    public function findBy($field, array|null $value, $with = []);

    /**
     * Get model query
     * @return mixed
     */
    public function getModelQuery();
}
