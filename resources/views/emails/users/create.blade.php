@extends('emails.base')

@section('title', "Hi {$name},")

@section('content')
    <p>This is just to let you know that an account for the Backstage website has recently been created for you.</p>
    <p>We have set your password to '<strong>{{ $password }}</strong>' but we recommend you {!! link_to_route('auth.login', 'log in') !!} and change this to something more memorable.</p>
    <p>The Backstage website can be accessed at: {!! link_to_route('home', route('home')) !!}.</p>
@endsection