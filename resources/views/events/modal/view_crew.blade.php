{!! Form::open() !!}
<div class="modal-body">
    {{-- User --}}
    <div class="form-group">
        {!! Form::label('user_id', 'User:', ['class' => 'control-label']) !!}
        {!! Form::select('user_id', $users, null, ['class' => 'form-control']) !!}
    </div>
    {{-- Core --}}
    <div class="form-group">
        <div class="checkbox">
            <label>
                {!! Form::checkbox('core', 1, null) !!}
                Make this user core crew
            </label>
        </div>
    </div>
    {{-- Core title --}}
    <div class="form-group">
        {!! Form::text('name', null, ['class' => 'form-control', 'disabled' => 'disabled', 'placeholder' => 'Title']) !!}
    </div>
    {{-- EM --}}
    <div class="form-group">
        <div class="checkbox">
            <label>
                {!! Form::checkbox('em', 1, null, ['disabled' => 'disabled']) !!}
                This is an EM role
            </label>
        </div>
    </div>
    {{-- Crew id --}}
    {!! Form::input('hidden', 'id', null) !!}
</div>
<div class="modal-footer">
    <button class="btn btn-success" data-type="submit-modal" id="submitCrewModal">
        <span class="fa fa-check"></span>
        <span>Add Crew</span>
    </button>
    <button class="btn btn-danger" data-type="submit-modal" data-form-action="{{ route('events.update', ['id' => $event->id, 'action' => 'delete-crew']) }}" id="deleteCrew">
        <span class="fa fa-remove"></span>
        <span>Delete</span>
    </button>
</div>
{!! Form::close() !!}