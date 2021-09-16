@extends('templates.layouts.notifications')
@section('content')
    <h1>Request Password</h1>
    <p>Hi,{{ $user->name}} </p>
    <p>Email: {{ $user->email }} </p>
    <p>Token: {{ $token }} </p>
@endsection
