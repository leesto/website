@extends('emails.base')

@section('title', 'Hi all,')

@section('content')
    <p>We recently received the following booking request:</p>
    @include('emails.contact.book_shared')
@endsection