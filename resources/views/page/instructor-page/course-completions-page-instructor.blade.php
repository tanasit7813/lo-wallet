@extends('layouts.app-with-sidebar')

@section('page-content')
    <livewire:instructor.course-completions :id="$id" />
@endsection
