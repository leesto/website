@include('emails.partials.table')

<table @yield('_table')>
    <tr>
        <td><strong>Event:</strong></td>
        <td>{{ $event_name }}</td>
    </tr>
    @if($event_description)
        <tr>
            <td style="vertical-align: top"><strong>Description:</strong></td>
            <td>{!! nl2br($event_description) !!}</td>
        </tr>
    @endif
    <tr>
        <td><strong>Event Dates:</strong></td>
        <td>{{ $event_dates }}</td>
    </tr>
    <tr>
        <td><strong>Venue:</strong></td>
        <td>{{ $event_venue }}</td>
    </tr>
    <tr>
        <td style="vertical-align: top"><strong>Venue Access:</strong></td>
        <td>
            {!! str_replace(['0', '1', '2'], ['Morning', 'Afternoon', 'Evening'], implode('<br>', $event_access)) !!}
        </td>
    </tr>
    @if($show_time)
        <tr>
            <td><strong>Club/Organisation:</strong></td>
            <td>{{ $event_club }}</td>
        </tr>
    @endif
    <tr>
        <td><strong>Contact:</strong></td>
        <td>{{ $contact_name }}</td>
    </tr>
    <tr>
        <td><strong>Contact Email:</strong></td>
        <td><a href="mailto:{{ $contact_email }}?subject=BTS booking request ({{ $event_name }})">{{ $contact_email }}</a></td>
    </tr>
    @if($contact_phone)
        <tr>
            <td><strong>Contact Phone:</strong></td>
            <td>{{ $contact_phone }}</td>
        </tr>
    @endif
    @if($additional)
        <tr>
            <td style="vertical-align: top"><strong>Additional Info:</strong></td>
            <td>{!! nl2br($additional) !!}</td>
        </tr>
    @endif
</table>