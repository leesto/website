@extends('error')

@section('title', 'Access restricted')
@section('subtitle', 'Sorry, you can\'t access that')
@section('num', 403)
@section('description')
    <p>Unfortunately you do not have permission to access the page you requested. Please either <a href="javascript:history.back();">go back to the previous page</a> or go to the <a href="{{ route('home') }}">home page</a>.</p>
    <p>If you feel that you should have access to this page, please contact an administrator.</p>
@endsection