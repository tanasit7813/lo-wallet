@extends('layouts.app')

@section('title')
    @yield('title', '')
@endsection

@section('content')
    @if (request()->path() === 'register')
        @include('page.auth-page.register-page')
    @elseif (request()->path() === 'login')
        @include('page.auth-page.login-page')
    @elseif (request()->path() === 'dashboard')
        @include('page.auth-page.dashboard')

    @elseif (request()->path() === 'courses')
        @include('page.auth-page.course-index-page')
    @elseif (preg_match('/^courses\/\d+$/', request()->path()))
        @include('page.auth-page.course-detail-page')


    @elseif (request()->path() === 'student/my-courses')
        @include('page.student-page.my-courses-page')
    @elseif (preg_match('/^student\/certificate\/\d+$/', request()->path()))
        @include('page.student-page.view-certificate-page-student')
    @elseif (request()->path() === 'student/my-lowallet')
        @include('page.student-page.l-o-wallet-page-student')


    @elseif (request()->path() === 'general/my-courses')
        @include('page.general-page.my-courses-page-general')
    @elseif (preg_match('/^general\/certificate\/\d+$/', request()->path()))
        @include('page.general-page.view-certificate-page-general')
    @elseif (request()->path() === 'general/my-lowallet')
        @include('page.general-page.l-o-wallet-page-general')


    @elseif (request()->path() === 'insider/my-courses')
        @include('page.insider-page.my-courses-page-insider')
    @elseif (preg_match('/^insider\/certificate\/\d+$/', request()->path()))
        @include('page.insider-page.view-certificate-page-insider')
    @elseif (request()->path() === 'insider/my-lowallet')
        @include('page.insider-page.l-o-wallet-page-insider')


    @elseif (request()->path() === 'instructor/courses/create')
        @include('page.instructor-page.create-course-page')
    @elseif (preg_match('/^instructor\/courses\/\d+$/', request()->path()))
        @include('page.instructor-page.course-detail-page-instructor')
    @elseif (preg_match('/^instructor\/courses\/course_completions\/\d+$/', request()->path()))
        @include('page.instructor-page.course-completions-page-instructor')
    @elseif (preg_match('/^instructor\/courses\/edit\/\d+$/', request()->path()))
        @include('page.instructor-page.edit-course-page')


    @elseif (request()->path() === 'officer/certificates')
        @include('page.officer-page.certificate-requests-page-officer')
    @elseif (request()->path() === 'officer/signature-management')
        @include('page.officer-page.signature-management-page-officer')
    
    @else
        @include('page.auth-page.login-page')
    @endif
@endsection
