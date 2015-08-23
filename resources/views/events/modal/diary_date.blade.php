<div class="modal fade" id="diaryDateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            {!! Form::open() !!}
            <div class="modal-header">
                <h1>Change Date</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-8">
                        {!! Form::selectMonth('month', date('m'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-xs-4">
                        {!! Form::selectRange('year', date('Y') - 5, date('Y') + 5, date('Y'), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" data-url="{{ route('events.diary', ['year' => '%year', 'month' => '%month']) }}" disable-submit="Processing ..." id="submitDateModal" type="button">
                    <span class="fa fa-check"></span>
                    <span>Change date</span>
                </button>
                <button class="btn btn-danger" id="cancelDateModal" type="button">
                    <span class="fa fa-undo"></span>
                    <span>Cancel</span>
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>