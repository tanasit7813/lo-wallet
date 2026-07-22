@extends('layouts.app-with-sidebar')

@section('page-content')
    <livewire:course-detail :id="$id" />
@endsection
