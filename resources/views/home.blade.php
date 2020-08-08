@extends('layouts.app')

@section('content')
    <auth-component session-user="{{Auth::user()}}" login-route="{{route('login')}}" register-route="{{route('register')}}" logout-route="{{route('logout')}}"></auth-component>
    <comments-component get-route="{{route('getComments')}}"></comments-component>
@endsection
