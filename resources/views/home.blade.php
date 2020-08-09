@extends('layouts.app')

@section('content')
    <app-component session-user="{{Auth::user()}}" login-route="{{route('login')}}" 
        register-route="{{route('register')}}" logout-route="{{route('logout')}}"
        get-comments-route="{{route('getComments')}}" save-comment-route="{{route('saveComment')}}"
        ></app-component>
@endsection
