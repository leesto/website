@extends('error')

@section('title', 'Fatal Error')
@section('subtitle', 'Something major went wrong')
@section('num', 500)
@section('description')
    <p>Unfortunately a fatal error occurred and further processing of this page had to be stopped. There isn't much you can do apart from try other pages to see if they work.</p>
    <p>If you are an administrator you can refer to the error logs for more information.</p>
@endsection