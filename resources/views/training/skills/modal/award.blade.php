<div data-type="modal-template" data-id="award_skill">
    <div class="modal-body">
        {!! Form::open() !!}
        <p style="margin-top:0;">If you are an admin, or are <strong>Level 3</strong> in {{ isset($skill) ? 'this' : 'a' }} skill, you can award it to any other member.</p>

        {{-- Skill --}}
        @include('training.skills.modal._skill_list')

        {{-- Member --}}
        <div class="form-group">
            {!! Form::label('user_id', 'Select the member to award the skill to:', ['class' => 'control-label']) !!}
            {!! Form::select('user_id', $members, null, ['class' => 'form-control']) !!}
        </div>

        {{-- Level --}}
        <div class="form-group">
            {!! Form::label('level', 'What level should they be awarded?', ['class' => 'control-label']) !!}
            {!! Form::select('level', [1 => 'Level 1', 2 => 'Level 2', 3 => 'Level 3'], null, ['class' => 'form-control']) !!}
        </div>

        {{-- Awarded --}}
        <div class="form-group text-right">
            <button class="btn btn-success" data-type="submit-modal" data-form-action="{{ route('training.skills.award') }}" type="button">
                <span class="fa fa-check"></span>
                <span>Award skill</span>
            </button>
        </div>
        {!! Form::close() !!}
    </div>
</div>