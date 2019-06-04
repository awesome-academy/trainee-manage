<?php

namespace App\Repositories;

interface TraineeRepositoryInterface
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

    public function getOffice();
    public function getLanguage();
    public function getStaffType();
    public function getGender();
    public function getUniversity();
    public function getTrainer();

    /**
     * Get new trainee who don't have course
     * @return collection
     */
    public function getTraineesForCourse();

    /**
     * Add course_id to trainee
     * @param  int $course_id, array $trainee_ids
     * @return int|boolean
     */
    public function addCourse($trainee_ids, $course_id);

    /**
     * Remove trainee without course
     * @param  int $id
     * @return int|boolean
     */
    public function removeTraineeFromCourse($id);

    /**
     * Show the tests of user is trainee
     * @return $tests
     */
    public function showTest();
    public function getCourse();
    public function timeLeft();
    public function myTrainee();
    public function filterByLanguage($language_id);
}
