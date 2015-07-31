@extends('emails.base')

@section('title', 'Hi ' . App\User::forename($contact_name) . ',')

@include('emails.partials.blockquote')

@section('content')
    <p>This is an automated response to confirm that your booking request has been sent. A copy of this request is included below for your records.</p>
    <blockquote @yield('_blockquote')>
        @include('emails.contact.book_shared')
    </blockquote>
    <p>Please note that this is <strong>not</strong> a confirmation that Backstage can support your event; your requirements are currently being assessed and
        you should be contacted within the next few days.</p>
    <p>If you need to get in contact with us for any reason please reply to this email.</p>
@endsection