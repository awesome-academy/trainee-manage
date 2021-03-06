@extends('admin.layouts.master')
@section('title', __('Phases'))
@section('content')
<div class="content-wrapper">
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('Create phase') }}</h4>
                    </div>
                    @include('admin.components.alert')
                    <div class="card-body">
                        @include('admin.phases.form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
