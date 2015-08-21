@extends('emails.base')

@section('title', 'Dear ' . ucfirst($contact_name) . ',')

@include('emails.partials.blockquote')

@section('content')
    <p>Your accident report has been sent to the relevant parties; a copy of this report is included below for reference.</p>
    <blockquote @yield('_blockquote')>
        @include('emails.contact.accident_shared')
    </blockquote>
@endsection