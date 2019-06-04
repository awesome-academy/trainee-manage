@extends('admin.layouts.master')
@section('title', __('All Trainees'))
@section('content')
    <div class="content-wrapper">
        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('All trainees') }}</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                    <li><a data-action="close"><i class="ft-x"></i></a></li>
                                </ul>
                            </div>
                            <div class="row">
                                <div class="form-group col-4 ml-auto">
                                    <select id="select_language" class="form-control border-info" data-url="{!! http_build_query(Request::except('language')) !!}">
                                        <option value="0">{{ __('All') }}</option>
                                        @foreach ($languages as $language)
                                            <option value="{{ $language->id }}" {{ request()->query('language') == $language->id ? 'selected' : '' }}>{{ $language->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="btn btn-outline-info btn-glow float-right mt-2">
                                <a href="{{ route('trainees.create') }}"> {{ __('Create') }}</a>
                            </div>
                            <div class="btn btn-outline-info btn-glow float-right mt-2 mr-1">
                                <a href="{{ route('trainees.index', ['check' => 'on']) }}"> {{ __('Check') }}</a>
                            </div>
                        </div>
                        @include('admin.components.alert')
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Language') }}</th>
                                            <th>{{ __('Date of Birth') }}</th>
                                            <th>{{ __('Phone') }}</th>
                                            <th>{{ __('Address') }}</th>
                                            <th>{{ __('Office') }}</th>
                                            <th>{{ __('Trainer') }}</th>
                                            <th>{{ __('Staff Type') }}</th>
                                            <th>{{ __('Gender') }}</th>
                                            <th>{{ __('Graduation Year') }}</th>
                                            <th>{{ __('Batch') }}</th>
                                            <th>{{ __('University') }}</th>
                                            <th>{{ __('Start Time') }}</th>
                                            <th>{{ __('End Time') }}</th>
                                            <th>{{ __('Course') }}</th>
                                            <th>{{ __('Action') }}</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($trainees as $trainee)
                                            <tr>
                                                <th scope="row"> <a href="{{ route('schedules.trainee_schedule', [$trainee->id, $trainee->course_id]) }}">{{ $trainee->id }}</a></th>
                                                <td>{{ optional($trainee->user)->email }}</td>
                                                <td>{{ optional($trainee->user)->name }}</td>
                                                <td>{{ optional($trainee->language)->name }}</td>
                                                <td>{{ $trainee->dob }}</td>
                                                <td>{{ $trainee->phone }}</td>
                                                <td>{{ $trainee->address }}</td>
                                                <td>{{ optional($trainee->office)->name }}</td>
                                                <th>{{ isset($trainee->trainer->user->name) ? $trainee->trainer->user->name : '' }}</th>
                                                <th>{{ optional($trainee->staff_type)->name }}</th>
                                                <th>{{ config('constants.gender.' . $trainee->gender) }}</th>
                                                <th>{{ $trainee->graduation_year }}</th>
                                                <th>{{ $trainee->batch }}</th>
                                                <th>{{ optional($trainee->university)->name }}</th>
                                                <th>{{ date('Y-m-d',strtotime($trainee->internship_start_time)) }}</th>
                                                <th>{{ date('Y-m-d',strtotime($trainee->internship_end_time)) }}</th>
                                                <th>{{ isset($trainee->course->course_name) ? $trainee->course->course_name : '' }}</th>
                                                <td>
                                                    <a href="{{ route('trainees.edit', $trainee->id) }}" class="btn btn-light round mr-1">{{ __('Edit') }}</a>
                                                    <button type="button" class="btn btn-danger round mr-1" data-toggle="modal" data-target="#delete-{{ $trainee->id }}">{{ __('Delete') }}</button>
                                                    @include('admin.components.modal', ['route' => route('trainees.destroy', $trainee->id), 'id' => $trainee->id ])
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ mix('js/filter.js') }}"></script>
@endpush
