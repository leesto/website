@extends('app')

@section('title', 'Event Details')

@section('stylesheets')
    @include('partials.tags.style', ['path' => 'partials/events'])
@endsection

@section('scripts')
    $modal.on('show.bs.modal', function(event) {
        var btn = $(event.relatedTarget);
        var form = $modal.find('form');
        var submitBtn = form.find('#submitTimeModal, #submitCrewModal');
        submitBtn.data('formAction', btn.data('formAction'));
        var action = btn.data('formAction').substr(btn.data('formAction').lastIndexOf('/') + 1);

        if(action == 'update-time') {
            form.find('h1').text('Edit a Time');
            submitBtn.children('span').eq(1).text('Save');
            form.find('[name="name"]').val(btn.data('name'));
            form.find('[name="date"]').val(btn.data('date'));
            form.find('[name="start_time"]').val(btn.data('start'));
            form.find('[name="end_time"]').val(btn.data('end'));
            form.find('[name="id"]').val(btn.data('timeId'));
            form.find('#deleteTime').show();
        } else if(action == 'add-time') {
            submitBtn.children('span').eq(1).text('Add Time');
            form.find('#deleteTime').hide();
        } else if(action == 'add-crew') {
            submitBtn.children('span').eq(1).text('Add Crew');
            form.find('select[name="user_id"]').parent().show();
            form.find('#deleteCrew').hide();
        } else if(action == 'update-crew') {
            submitBtn.children('span').eq(1).text('Save');
            form.find('select[name="user_id"]').parent().hide();
            form.find('input[name="core"]').prop('checked', !!btn.data('roleName')).trigger('change');
            form.find('input[name="name"]').val(btn.data('roleName') ? btn.data('roleName') : '');
            form.find('input[name="em"]').prop('checked', !!btn.data('roleEm'));
            form.find('input[name="id"]').val(btn.data('roleId'));
            form.find('#deleteCrew').show();
        }
    });
    $modal.on('change', 'input[type="checkbox"][name="core"]', function() {
        var disabled = $(this).prop('checked') ? '' : 'disabled';
        $modal.find('input[name="name"]').prop('disabled', disabled);
        $modal.find('input[name="em"]').prop('disabled', disabled);
        if(disabled == 'disabled') {
            $modal.find('input[name="em"]').prop('checked', false);
        }
    });
@endsection

@section('content')
    <h1>{{ $event->name }}</h1>
    <div id="viewEvent">
        {!! Form::open(['class' => 'form-horizontal']) !!}
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h2>Event Info</h2>
                    {{-- EM --}}
                    <div class="form-group">
                        {!! Form::label('em', 'EM:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-7">
                            <p class="form-control-static">{!! $event->em ? $event->em->name : '<em>&ndash; not yet assigned &ndash;</em>' !!}</p>
                        </div>
                    </div>
                    {{-- Type --}}
                    <div class="form-group">
                        {!! Form::label('type', 'Type:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-7">
                            <p class="form-control-static">
                                <span class="event-entry tag upper {{ $event->type_class }}">{{ $event->type_string }}</span>
                            </p>
                        </div>
                    </div>
                    {{-- Client --}}
                    <div class="form-group">
                        {!! Form::label('client', 'Client:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-7">
                            <p class="form-control-static">{{ $event->client }}</p>
                        </div>
                    </div>
                    {{-- Venue --}}
                    <div class="form-group">
                        {!! Form::label('venue', 'Venue:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-7">
                            <p class="form-control-static">{{ $event->venue }}</p>
                        </div>
                    </div>
                    {{-- Description --}}
                    @if($isMember)
                    <div class="form-group">
                        {!! Form::label('description', 'Description:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-7">
                            <p class="form-control-static">{!! nl2br($event->description) !!}</p>
                        </div>
                    </div>
                    @endif
                    {{-- Public Description --}}
                    @if($event->description_public && (!$isMember || $canEdit))
                        <div class="form-group">
                            {!! Form::label('description', $isMember ? 'Public Description:' : 'Description:', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                <p class="form-control-static">{!! nl2br($event->description_public) !!}</p>
                            </div>
                        </div>
                    @endif
                    {{-- Paperwork --}}
                    @if($canEdit)
                        <h2>Paperwork</h2>
                        @foreach(\App\Event::$Paperwork as $key => $name)
                            <div class="form-group">
                                {!! Form::label('', $name . ':', ['class' => 'col-md-5 control-label']) !!}
                                <div class="col-md-7">
                                    <p class="form-control-static">
                                        <span class="paperwork" data-key="{{ $key }}" data-status="{{ $event->paperwork[$key] }}">
                                            @if($event->paperwork[$key])
                                                <span class="fa fa-check"></span>
                                                <span>completed</span>
                                            @else
                                                <span class="fa fa-remove"></span>
                                                <span>not completed</span>
                                            @endif
                                        </span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                {{-- Crew list --}}
                @if($event->crew_list_status > -1 && $isMember)
                <div class="col-md-6">
                    <h2>Crew List <span class="crew-status">[{{ $event->crewListOpen() ? 'open' : 'closed' }}]</span></h2>
                    @if(count($event->crew) > 0)
                        <div class="container-fluid crew-list">
                            @foreach($event->crew_list as $role => $crew_list)
                                <div class="form-group">
                                    {!! Form::label('crew', $role . ':', ['class' => 'col-md-5 control-label']) !!}
                                    <div class="col-md-7">
                                        @foreach($crew_list as $crew)
                                            @if($canEdit && is_object($crew))
                                                <p class="form-control-static"
                                                   data-toggle="modal"
                                                   data-target="#modal"
                                                   data-modal-class="modal-sm"
                                                   data-modal-template="event_crew"
                                                   data-modal-title="Edit Crew Role"
                                                   data-form-action="{{ route('events.update', ['id' => $event->id, 'action' => 'update-crew']) }}"
                                                   data-role-id="{{ $crew->id }}"
                                                   data-role-name="{{ $crew->name }}"
                                                   data-role-em="{{ $crew->em }}"
                                                   data-editable="true">{{ $crew->user->name }}</p>
                                            @else
                                                <p class="form-control-static">{{ $crew->user->name }}</p>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>No one is crewing this event yet</p>
                    @endif
                    <p>
                        @if($event->crewListOpen())
                            @if($event->isCrew($user))
                                <button class="btn btn-danger" {!! $isEM ? ' title="You are the EM - you can\'t unvolunteer!" disabled' : '' !!}
                                        data-submit-ajax="{{ route('events.volunteer', ['id' => $event->id]) }}"
                                        type="button">
                                    <span class="fa fa-user-times"
                                            data-submit-ajax="{{ route('events.volunteer', ['id' => $event->id]) }}"></span>
                                    <span>Unvolunteer</span>
                                </button>
                            @else
                                <button class="btn btn-success"
                                        data-submit-ajax="{{ route('events.volunteer', ['id' => $event->id]) }}"
                                        type="button">
                                    <span class="fa fa-user-plus"></span>
                                    <span>Volunteer</span>
                                </button>
                            @endif
                        @endif
                        @if($canEdit)
                            <button class="btn btn-success"
                                    data-toggle="modal"
                                    data-target="#modal"
                                    data-modal-template="event_crew"
                                    data-modal-class="modal-sm"
                                    data-modal-title="Add Crew Role"
                                    data-form-action="{{ route('events.update', ['id' => $event->id, 'action' => 'add-crew']) }}"
                                    type="button">
                                <span class="fa fa-user-plus"></span>
                                <span>Add crew</span>
                            </button>
                        @endif
                    </p>
                </div>
                @endif
                @if($event->crew_list_status > -1 && $isMember)
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                @endif
                <div class="col-md-6">
                    <h2 class="{{ $event->crew_list_status > -1 && $isMember ? 'text-center' : '' }}">Event Times</h2>
                    <div class="event-times">
                        @if(count($event->event_times) > 0)
                            @foreach($event->event_times as $date => $times)
                                @foreach($times as $i => $time)
                                    @if($canEdit)
                                    <div class="event-time"
                                         data-editable="true"
                                         data-toggle="modal"
                                         data-target="#modal"
                                         data-mode="edit"
                                         data-name="{{ $time->name }}"
                                         data-date="{{ $time->start->format('d/m/Y') }}"
                                         data-start="{{ $time->start->format('H:i') }}"
                                         data-end="{{ $time->end->format('H:i') }}"
                                         data-time-id="{{ $time->id }}"
                                         data-form-action="{{ route('events.update', ['id' => $event->id, 'action' => 'update-time']) }}"
                                         data-modal-template="event_time"
                                         data-modal-title="Edit Event Time"
                                         data-modal-class="modal-sm"
                                         role="button">
                                    @else
                                    <div class="event-time">
                                    @endif
                                        <div class="date">{{ $i == 0 ? $date : '&nbsp;' }}</div>
                                        <div class="time">{{ $time->start->format('H:i') }} &ndash; {{ $time->end->format('H:i') }}</div>
                                        <div class="name">{{ $time->name }}</div>
                                    </div>
                                @endforeach
                            @endforeach
                        @else
                            <h4>No times exist for this event.</h4>
                        @endif
                    </div>
                    @if($canEdit)
                        <button class="btn btn-success"
                                data-toggle="modal"
                                data-mode="new"
                                data-form-action="{{ route('events.update', ['id' => $event->id, 'action' => 'add-time']) }}"
                                data-target="#modal"
                                data-modal-template="event_time"
                                data-modal-class="modal-sm"
                                data-modal-title="Add Event Time"
                                type="button">
                            <span class="fa fa-plus"></span>
                            <span>Add event time</span>
                        </button>
                    @endif
                </div>
                @if($event->crew_list_status > -1 && $isMember)
                <div class="col-md-3"></div>
                @endif
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

@section('modal')
    @if($canEdit)
        <div data-type="modal-template" data-id="event_time">
            @include('events.modal.view_time')
        </div>
        <div data-type="modal-template" data-id="event_crew">
            @include('events.modal.view_crew')
        </div>
    @endif
@endsection