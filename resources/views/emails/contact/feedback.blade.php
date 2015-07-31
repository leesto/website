@extends('emails.base')

@section('title', 'Hi all,')

@include('emails.partials.blockquote')

@section('content')
    <p>We have just been sent the following feedback for the event '<strong>{{ $event }}</strong>'.</p>
    <blockquote @yield('_blockquote')>
        @include('emails.partials.multiline', ['text' => $feedback])
    </blockquote>
    <p>To protect their anonymity, their contact details are not included.</p>
@endsection