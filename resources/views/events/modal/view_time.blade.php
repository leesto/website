<div class="modal fade" id="eventTimeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1>#title</h1>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => ['events.update', $event->id], 'class' => 'form-horizontal']) !!}
                    {{-- Name --}}
                    <div class="form-group">
                        {!! Form::label('name', 'Title:', ['class' => 'col-xs-4 control-label']) !!}
                        <div class="col-xs-8">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    {{-- Date --}}
                    <div class="form-group">
                        {!! Form::label('date', 'Date:', ['class' => 'col-xs-4 control-label']) !!}
                        <div class="col-xs-8">
                            {!! Form::text('date', null, ['class' => 'form-control', 'placeholder' => 'dd/mm/yyyy']) !!}
                        </div>
                    </div>
                    {{-- Start time --}}
                    <div class="form-group">
                        {!! Form::label('start_time', 'Start Time:', ['class' => 'col-xs-4 control-label']) !!}
                        <div class="col-xs-8">
                            {!! Form::text('start_time', null, ['class' => 'form-control', 'placeholder' => 'hh:mm'])!!}
                        </div>
                    </div>
                    {{-- End time --}}
                    <div class="form-group">
                        {!! Form::label('end_time', 'End Time:', ['class' => 'col-xs-4 control-label']) !!}
                        <div class="col-xs-8">
                            {!! Form::text('end_time', null, ['class' => 'form-control', 'placeholder' => 'hh:mm'])!!}
                        </div>
                    </div>
                    {!! Form::input('hidden', 'id', null) !!}
                    {{-- Buttons --}}
                    <div class="form-group" style="margin: 2em 0 0;">
                        <div class="col-xs-12 text-center">
                            <button class="btn btn-success" id="submitTimeModal" type="button">
                                <span class="fa fa-check"></span>
                                <span>Add Time</span>
                            </button>
                            <button class="btn btn-danger" data-form-action="{{ route('events.update', ['id' => $event->id, 'action' => 'delete-time']) }}" id="deleteTime" type="button">
                                <span class="fa fa-remove"></span>
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>