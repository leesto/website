@extends('emails.base')

@section('title', 'Hi ' . App\User::forename($name) . ',')

@include('emails.partials.blockquote')

@section('content')
    <p>This is just to confirm that we've received your enquiry, which we've included below for reference.</p>
    <p>We'll make sure this goes to the appropriate person and get back to you soon.</p>
    <blockquote @yield('_blockquote')>
        @include('emails.partials.multiline', ['text' => $content])
    </blockquote>
@endsection