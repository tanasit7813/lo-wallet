@extends('layouts.app-with-sidebar')

@section('page-content')
    <livewire:student.view-certificate :id="$id" />
@endsection
