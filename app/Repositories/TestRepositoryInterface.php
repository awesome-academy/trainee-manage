<?php

namespace App\Repositories;

interface TestRepositoryInterface
{
    /**
     * Get all collection of instance
     * @param  array  $relation
     * @return colection
     */
    public function all($relation = []);

    /**
     * Get all small sliced collection of instance
     * @param  array  $relation
     * @param  int $number
     * @return colection
     */
    public function paginate($relation = [], $number);

    /**
     * Create a new instance of the given model
     * @param  array $data
     * @return instance
     */
    public function store($data);

    /**
     * Get instance by id
     * @param  array  $relation
     * @param  int $id
     * @return instance
     */
    public function get($relation = [], $id);

    /**
     * Update instance by id
     * @param  array  $data
     * @param  int $id
     * @return instance
     */
    public function update($data, $id);

    /**
     * Delete instance by id
     * @param  int $id
     * @return int|boolean
     */
    public function delete($id);

    /**
     * update Content link test by trainee
     * @param  int $id
     * @return int|boolean
     */
    public function updateContent($data, $id);
    public function markAsRead($data);
}
