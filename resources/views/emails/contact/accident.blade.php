@extends('emails.base')

@section('title', 'Dear all,')

@section('content')
    <p>The following accident has been reported:</p>
    @include('emails.contact.accident_shared')
@endsection