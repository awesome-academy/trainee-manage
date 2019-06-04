<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use App\Models\Language;
use App\Models\Office;
use App\Models\Schedule;
use App\Models\StaffType;
use App\Models\Trainer;
use App\Models\University;
use App\Models\User;
use App\Repositories\TraineeRepositoryInterface;
use App\Repositories\Contracts\BaseRepository;
use App\Models\Trainee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;


class TraineeRepository extends BaseRepository implements TraineeRepositoryInterface
{
    protected $trainee, $user;
    /**
     * Create a new Repository instance.
     *
     * @param  TraineeRepositoryInterface
     * @return void
     */
    public function __construct(Trainee $trainee, User $user)
    {
        parent::__construct($trainee);
        $this->trainee = $trainee;
        $this->user = $user;
    }

    public function all($relation = [])
    {
        return $this->trainee::all();
    }

    public function getGender()
    {
        return Config::get('constants.gender');
    }

    public function getLanguage()
    {
        return $languages = Language::all();
    }

    public function getStaffType()
    {
        return $staff_types = StaffType::all();
    }

    public function getOffice()
    {
        return $offices = Office::all();
    }

    public function getUniversity()
    {
        return $universities = University::all();
    }

    public function getTrainer()
    {
        $trainer = Trainer::all();

        return $trainer;
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $user = $this->user->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make(Config::get('constants.constants.default_password')),
                'roles' => Config::get('constants.roles.trainee'),
            ]);
            $this->trainee->create([
                'dob' => $data['dob'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'language_id' => $data['language_id'],
                'user_id' => $user->id,
                'office_id' => $data['office_id'],
                'trainer_id' => $data['trainer_id'],
                'staff_type_id' => $data['staff_type_id'],
                'gender' => $data['gender'],
                'graduation_year' => $data['graduation_year'],
                'university_id' => $data['university_id'],
                'internship_start_time' => $data['internship_start_time'],
                'internship_end_time' => $data['internship_end_time'],
                'batch_id' => config('constants.constants.default_value'),
                'course_id' => config('constants.constants.default_value'),
            ]);
            DB::commit();

            return true;
        }  catch (\Illuminate\Database\QueryException $e) {
            return false;
        }
    }
    public function get($relation = [], $id)
    {
        $trainer = $this->trainee::findOrFail($id);

        return $trainer;
    }

    public function update($data, $id)
    {
        $trainee = $this->trainee::findOrFail($id);
        $trainee->update([
            'dob' => $data['dob'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'language_id' => $data['language_id'],
            'office_id' => $data['office_id'],
            'trainer_id' => $data['trainer_id'],
            'staff_type_id' => $data['staff_type_id'],
            'gender' => $data['gender'],
            'graduation_year' => $data['graduation_year'],
            'university_id' => $data['university_id'],
            'internship_start_time' => $data['internship_start_time'],
            'internship_end_time' => $data['internship_end_time'],
            'batch_id' => config('constants.constants.default_value'),
            'course_id' => config('constants.constants.default_value'),
        ]);

        $user = $trainee->user()->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        return true;
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $trainee = $this->trainee::findOrFail($id);
            $user_id = $trainee->user->id;
            $this->user->destroy($user_id);
            $this->trainee->destroy($id);
            DB::commit();
        } catch (Exception $e) {
            return redirect()->route('trainees.index');
        }
    }

    public function getTraineesForCourse()
    {
        if (isset(auth()->user()->trainer->id)) {
            return $this->model->where('course_id', 0)->where('trainer_id', auth()->user()->trainer->id)->with('user')->get();
        }

        return $this->model->where('course_id', 0)->with('user')->get();
    }

    public function addCourse($trainee_ids, $course_id)
    {
        DB::beginTransaction();
        try {
            $result = $this->model->whereIn('id', $trainee_ids)->update(['course_id' => $course_id]);
            if ($result) {
                $this->createTestForTrainees($trainee_ids);
            }
            DB::commit();
        } catch (Exception $e) {
            $result = false;
        }

        return $result;
    }

    public function removeTraineeFromCourse($id)
    {
        DB::beginTransaction();
        try {
            $result = $this->model->find($id)->update(['course_id' => 0]);
            if ($result) {
                $this->removeTestFromTrainees($id);
            }
            DB::commit();
        } catch (Exception $e) {
            $result = false;
        }

        return $result;
    }

    public function createTestForTrainees($trainee_ids)
    {
        $trainees = $this->model->whereIn('id', $trainee_ids)->get();
        $trainee_first = $trainees->first();
        $phase_ids = optional($trainee_first->course->schedule)->phases->where('test_or_not', 1)->pluck('name', 'id')->toArray();
        $data = [];
        foreach ($phase_ids as $phase_id => $phase_name) {
            $data[] = [
                'name' => $phase_name,
                'phase_id' => $phase_id
            ];
        }
        foreach ($trainees as $trainee) {
            $trainee->tests()->createMany($data);
        }

        return;
    }

    public function removeTestFromTrainees($id)
    {
        $trainee = $this->get([], $id);
        $trainee->tests()->delete();

        return;
    }

    public function showTest()
    {
        $trainee = auth()->user()->trainee;
        if ($trainee == null) {
            return null;
        }
        
        return $trainee->tests;
    }

    public function getCourse()
    {
        $current_user = Auth::user();
        $trainee = $this->model->where('user_id', $current_user->id)->firstOrFail();
        $course_id = optional($trainee->course)->id;
        if ($course_id) {
            return $course_id;
        } else {
            return false;
        }
    }

    public function timeLeft()
    {
        $trainees = $this->trainee->all();
        $almost_expired_trainees = collect();
        foreach ($trainees as $trainee) {
            $course_id = $trainee->course_id;
            if ($course_id > 0) {
                $course = Course::findOrFail($course_id);
                $number_of_days = Carbon::now()->diffInWeekDays($course->start_date);

                $total_days = 0;
                $schedule = Schedule::findOrFail($course->schedule_id);
                for ($i = 0; $i < $schedule->phases->count(); $i++) {
                    $total_days = $total_days + $schedule->phases[$i]->pivot->time_duration;
                }
                if (($total_days - $number_of_days) < 2) {
                    $almost_expired_trainees->push($trainee);
                }
            }
        }

        return $almost_expired_trainees;
    }

    public function myTrainee()
    {
        return $this->model->where('trainer_id', auth()->user()->trainer->id)->get();
    }

    public function filterByLanguage($language_id)
    {
        if (isset(auth()->user()->trainer->id)) {
            return $this->model->where('language_id', $language_id)->where('trainer_id', auth()->user()->trainer->id)->get();
        }

        return $this->model->where('language_id', $language_id)->get();
    }
}
