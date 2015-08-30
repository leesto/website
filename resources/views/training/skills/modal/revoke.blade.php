<div data-type="modal-template" data-id="revoke_skill">
    <div class="modal-header">
        <h1>Revoke Skill Level</h1>
    </div>
    <div class="modal-body">
        {!! Form::open() !!}

        {{-- Skills --}}
        @include('training.skills.modal._skill_list')

        {{-- Member --}}
        <div class="form-group">
            {!! Form::label('user_id', 'Member:', ['class' => 'control-label']) !!}
            {!! Form::select('user_id', $members, null, ['class' => 'form-control']) !!}
        </div>

        {{-- Level --}}
        <div class="form-group">
            {!! Form::label('level', 'Select their new skill level:', ['class' => 'control-label']) !!}
            {!! Form::select('level', [0 => 'Completely revoke', 1 => 'Level 1', 2 => 'Level 2'], null, ['class' => 'form-control']) !!}
        </div>

        {{-- Buttons --}}
        <div class="form-group text-right">
            <button class="btn btn-success" data-type="submit-modal" data-form-action="{{ route('training.skills.revoke') }}">
                <span class="fa fa-check"></span>
                <span>Revoke</span>
            </button>
        </div>

        {!! Form::close() !!}
    </div>
</div>