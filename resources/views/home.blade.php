@extends('layouts.app')

@section('content')
    <auth-component login-route="{{route('login')}}" register-route="{{route('register')}}" logout-route="{{route('logout')}}"></auth-component>
@endsection
