@extends('admin.layouts.master')
@section('title', __('Schedules'))
@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="heading-left">
                                <h4 class="card-title">{{ __('All schedules') }}</h4>
                            </div>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a href="{{ route('schedules.create') }}" class="btn btn-outline-info btn-glow">{{ __('Create') }}</a></li>
                                </ul>
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
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Language') }}</th>
                                            <th>{{ __('Type') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($schedules as $schedule)
                                            <tr>
                                                <th scope="row"><a href="{{ route('schedules.edit', $schedule->id) }}"> {{ $schedule->id }} </a></th>
                                                <td>{{ $schedule->name }}</td>
                                                <td>{{ $schedule->language->name }}</td>
                                                <td>{{ $schedule->staff_type->name }}</td>
                                                <td>
                                                    <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn btn-light round mr-1">{{ __('Edit') }}</a>
                                                    <button type="button" class="btn btn-danger round mr-1" data-toggle="modal" data-target="#delete-{{ $schedule->id }}">{{ __('Delete') }}</button>
                                                    @include('admin.components.modal', ['route' => route('schedules.destroy', $schedule->id), 'id' => $schedule->id])
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
