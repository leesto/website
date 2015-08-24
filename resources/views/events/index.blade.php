@extends('app')

@section('title', 'Event Manager')

@section('stylesheets')
    @include('partials.tags.style', ['path' => 'partials/events'])
@endsection

@section('content')
    <h1 class="page-header">@yield('title')</h1>
    <div id="eventList">
        <table class="table table-striped">
            <thead>
                <th class="event">Event</th>
                <th class="venue">Venue</th>
                <th class="manager">EM</th>
                <th class="crew">Crew</th>
                <th class="date">Date</th>
            </thead>
            <tbody>
                @if(count($events) > 0)
                    @foreach($events as $event)
                        <tr>
                            <td class="event">
                                <div>
                                    {!! link_to_route('events.view', $event->name, $event->id) !!}
                                </div>
                                <div>
                                    <span class="event-entry tag {{ $event->type_class }}">{{ $event->type_string }}</span>
                                </div>
                            </td>
                            <td class="venue">{{ $event->venue }}</td>
                            <td class="manager">{{ $event->em['forename'] }} {{ $event->em['surname'] }}</td>
                            <td class="crew">{{ $event->crew->count() }}</td>
                            <td class="date">{{ $event->start_date }} &ndash; {{ $event->end_date }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">We don't have any events :/</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <a class="btn btn-success" href="{{ route('events.add') }}">
            <span class="fa fa-plus"></span>
            <span>Add an event to the diary</span>
        </a>
    </div>
    @include('partials.app.pagination', ['paginator' => $events])
@endsection