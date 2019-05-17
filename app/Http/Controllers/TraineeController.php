<?php

namespace App\Http\Controllers;

use App\Repositories\ScheduleRepositoryInterface;
use App\Repositories\TraineeRepositoryInterface;
use Illuminate\Http\Request;

class TraineeController extends Controller
{

    protected $trainee, $schedule;

    public function __construct(TraineeRepositoryInterface $trainee, ScheduleRepositoryInterface $schedule)
    {
        $this->trainee = $trainee;
        $this->schedule = $schedule;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trainees = $this->trainee->all();

        return view('admin.trainees.index', compact('trainees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = $this->trainee->getLanguage();
        $offices = $this->trainee->getOffice();
        $staff_types = $this->trainee->getStaffType();
        $universities = $this->trainee->getUniversity();
        $genders = $this->trainee->getGender();
        $trainers = $this->trainee->getTrainer();

        return view('admin.trainees.create', compact('languages', 'offices', 'staff_types', 'universities', 'genders', 'trainers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $trainee = $this->trainee->store($request);
        if (!$trainee) {
            return back()->with('error', __('Something went wrong!'));
        }

        return redirect()->route('trainees.index')->with('success', __('Create successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $trainee = $this->trainee->get([], $id);

        return view('trainees.show', compact('trainee'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $languages = $this->trainee->getLanguage();
        $offices = $this->trainee->getOffice();
        $staff_types = $this->trainee->getStaffType();
        $universities = $this->trainee->getUniversity();
        $genders = $this->trainee->getGender();
        $trainers = $this->trainee->getTrainer();
        $trainee = $this->trainee->get([], $id);
        if (!$trainee) {
            return back()->with('error', __('Something went wrong!'));
        }

        return view('admin.trainees.edit', compact('languages', 'offices', 'staff_types', 'universities', 'genders', 'trainers', 'trainee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $trainee = $this->trainee->update($request->all(), $id);
        if (!$trainee) {
            return back()->with('error', __('Something went wrong!'));
        }

        return redirect()->route('trainees.index')->with('success', __('Create successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->trainee->delete($id);

        return redirect()->back()->with('success', __('Delete successfully!'));
    }

    /**
     * Show trainee test
     *
     * @return \Illuminate\Http\Response
     */
    public function showTest()
    {
        $tests = $this->trainee->showTest();

        return view('admin.trainees.show_test', compact('tests'));
    }

    public function getSchedule()
    {
        $course_id = $this->trainee->getCourse();
        if (!$course_id) {
            return redirect()->route('home')->with('status', __('You have not joined a course'));
        }
        $schedule_id = $this->schedule->getTraineeSchedule($course_id);
        $current_phase = $this->schedule->getCurrentPhase($course_id);
        $schedule = $this->schedule->get([], $schedule_id);
        $phases = $this->schedule->getPhase($schedule_id);
        $duration = $this->schedule->getTime($schedule_id);
        $trainees = $this->trainee->all();

        return view('trainee.trainee_schedule', compact('current_phase', 'schedule', 'phases', 'duration', 'trainees'));
    }
}
