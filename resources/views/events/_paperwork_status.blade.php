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