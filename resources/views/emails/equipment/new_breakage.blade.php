@extends('emails.base')
@include('emails.partials.table')

@section('title', 'Hi,')

@section('content')
    <p>This is to let you know that a breakage has just been reported, the details of which are listed below.</p>
    <table @yield('_table')>
        <tr>
            <td><strong>Equipment:</strong></td>
            <td>{{ $breakage['name'] }}</td>
        </tr>
        <tr>
            <td><strong>Location:</strong></td>
            <td>{{ $breakage['location'] }}</td>
        </tr>
        <tr>
            <td style="vertical-align: top"><strong>Description:</strong></td>
            <td>@include('emails.partials.multiline', ['text' => $breakage['description']])</td>
        </tr>
        <tr>
            <td><strong>Labelled as:</strong></td>
            <td>{{ $breakage['label'] }}</td>
        </tr>
        <tr>
            <td><strong>Reported by:</strong></td>
            <td>{{ $user_name }} ({{ $username }})</td>
        </tr>
    </table>
    <p>You can access the breakage here: {!! link_to_route('equipment.repairs.view', 'Breakage info', $breakage['id']) !!}</p>
@endsection