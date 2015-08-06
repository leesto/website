@extends('emails.base')

@section('title', 'Hi ' . $user->forename . ',')

@section('content')
    <p>We recently received a request to reset your password from {{ Request::getClientIp() }}.</p>
    <p>If this was you then simply click <a href="{{ route('pwd.reset', $token) }}">here</a> to reset it.</p>
    <p>If this was not you don't worry; nothing has been changed or shared. However we do recommend you change your email address for security.</p>
@endsection