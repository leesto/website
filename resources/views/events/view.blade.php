@extends('app')

@section('title', 'Event Details')

@section('stylesheets')
    @include('partials.tags.style', ['path' => 'partials/events'])
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
                    @if($canEdit)
                        <h2>Paperwork</h2>
                        {{-- RA --}}
                        <div class="form-group">
                            {!! Form::label('ra', 'Risk Assessment:', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                <p class="form-control-static">
                                    <span class="paperwork">
                                        <span class="fa fa-check"></span>
                                        <span>completed</span>
                                    </span>
                                </p>
                            </div>
                        </div>
                        {{-- Insurance --}}
                        <div class="form-group">
                            {!! Form::label('insurance', 'Insurance:', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                <p class="form-control-static">
                                    <span class="paperwork">
                                        <span class="fa fa-remove"></span>
                                        <span>not completed</span>
                                    </span>
                                </p>
                            </div>
                        </div>
                        {{-- Finance (EM) --}}
                        <div class="form-group">
                            {!! Form::label('finance_em', 'EM Finance:', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                <p class="form-control-static">
                                    <span class="paperwork">
                                        <span class="fa fa-remove"></span>
                                        <span>not completed</span>
                                    </span>
                                </p>
                            </div>
                        </div>
                        {{-- Finance (Treasurer) --}}
                        <div class="form-group">
                            {!! Form::label('finance_treas', 'Treasurer Finance:', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                <p class="form-control-static">
                                    <span class="paperwork">
                                        <span class="fa fa-remove"></span>
                                        <span>not completed</span>
                                    </span>
                                </p>
                            </div>
                        </div>
                        {{-- Report --}}
                        <div class="form-group">
                            {!! Form::label('event_report', 'Event Report:', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                <p class="form-control-static">
                                    <span class="paperwork">
                                        <span class="fa fa-remove"></span>
                                        <span>not completed</span>
                                    </span>
                                </p>
                            </div>
                        </div>
                        {{-- Committee Report --}}
                        <div class="form-group">
                            {!! Form::label('committee_report', 'Committee Report:', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                <p class="form-control-static">
                                    <span class="paperwork">
                                        <span class="fa fa-remove"></span>
                                        <span>not completed</span>
                                    </span>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
                {{-- Crew list --}}
                @if($event->crew_list_status > -1 && $isMember)
                <div class="col-md-6">
                    <h2>Crew List <span class="crew-status">[{{ $event->crewListOpen() ? 'open' : 'closed' }}]</span></h2>
                    @if(count($event->crew) > 0)
                        <div class="container-fluid crew-list">
                            @foreach($event->crew_list as $role => $crew)
                                <div class="form-group">
                                    {!! Form::label('crew', $role . ':', ['class' => 'col-md-5 control-label']) !!}
                                    <div class="col-md-7">
                                        @foreach($crew as $name)
                                            <p class="form-control-static">{!! $name !!}</p>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>No one is crewing this event yet</p>
                    @endif
                    @if($event->crewListOpen())
                        <p>
                            @if($event->isCrew($user))
                                <button class="btn btn-danger" {!! $isEM ? ' title="You are the EM - you can\'t unvolunteer!" disabled' : '' !!}>
                                    <span class="fa fa-user-times"></span>
                                    <span>Unvolunteer</span>
                                </button>
                            @else
                                <button class="btn btn-success">
                                    <span class="fa fa-user-plus"></span>
                                    <span>Volunteer</span>
                                </button>
                            @endif
                        </p>
                    @endif
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
                                    <div class="event-time">
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
                    <button class="btn btn-success" data-toggle="modal" data-target="#eventTimeModal" type="button">
                        <span class="fa fa-clock-o"></span>
                        <span>Add a time</span>
                    </button>
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
    @include('events.modal.view_time')
@endsection