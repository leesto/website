@extends('emails.base')
@include('emails.partials.table')

@section('title', 'Hi,')

@section('content')
    <p>This is to let you know that a breakage has just been reported, the details of which are listed below.</p>
    <table @yield('_table')>
        <tr>
            <td><strong>Equipment:</strong></td>
            <td>{{ $name }}</td>
        </tr>
        <tr>
            <td><strong>Location:</strong></td>
            <td>{{ $location }}</td>
        </tr>
        <tr>
            <td style="vertical-align: top"><strong>Description:</strong></td>
            <td>@include('emails.partials.multiline', ['text' => $description])</td>
        </tr>
        <tr>
            <td><strong>Labelled as:</strong></td>
            <td>{{ $label }}</td>
        </tr>
        <tr>
            <td><strong>Reported by:</strong></td>
            <td>{{ $user_name }} ({{ $username }})</td>
        </tr>
    </table>
@endsection