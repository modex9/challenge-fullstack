@extends('layouts.app')

@section('content')
    <auth-header login-route="{{route('login')}}" register-route="{{route('register')}}" logout-route="{{route('logout')}}"></auth-header>
@endsection
