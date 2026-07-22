@extends('layouts.app-with-sidebar')

@section('page-content')
    <livewire:instructor.course-detail :id="$id" />
@endsection
