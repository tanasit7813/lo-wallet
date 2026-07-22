@extends('layouts.app-with-sidebar')

@section('page-content')
    <livewire:instructor.edit-course :id="$id" />
@endsection
