@extends('emails.base')

@section('title', 'Hi all,')

@include('emails.partials.blockquote')

@section('content')
    <p>{{ $name }} has just sent the following enquiry:</p>
    <blockquote @yield('_blockquote')>
        @include('emails.partials.multiline', ['text' => $content])
    </blockquote>
    <p>{{ App\User::forename($name) }} can be contacted by:</p>
    <p><strong>Email:</strong> <a href="mailto:{{ $email }}?subject=Your enquiry to BTS">{{ $email }}</a></p>
    @if($phone)
        <p><strong>Phone:</strong> {{ $phone }}</p>
    @endif
@endsection