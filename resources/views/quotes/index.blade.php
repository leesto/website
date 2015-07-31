@extends('app')

@section('title', 'Quotesboard')

@section('javascripts')
    @include('partials.tags.script', ['path' => 'partials/quotes'])
@endsection

@section('stylesheets')
    @include('partials.tags.style', ['path' => 'partials/quotes'])
@endsection

@section('add_quote_button')
    <button class="btn btn-success" data-toggle="modal" data-target="#newQuoteModal" id="newQuoteButton">
        <span class="fa fa-plus"></span>
        <span>Add a new quote</span>
    </button>
@endsection

@section('content')
    <h1 class="slim deco">Quotesboard</h1>
    @if(count($quotes) > 0)
        {!! Form::open(['route' => 'quotes.delete']) !!}
            @foreach($quotes as $i => $quote)
                <div class="quote">
                    <div class="quote-number">#{{ ($quotes->currentPage() - 1) * $quotes->perPage() + $i + 1 }}</div>
                    <div class="quote-details">
                        <div class="quote-content">
                            {!! nl2br($quote->quote) !!}
                        </div>
                        @if(!is_null($quote->added_by))
                            <div class="quote-date">
                                Said by {{ $quote->culprit }} {{ $quote->date->diffForHumans() }}
                            </div>
                        @endif
                        <div class="quote-actions">
                            <span class="fa fa-thumbs-up" title="Like this quote"></span>
                            <span class="fa fa-thumbs-down" title="Dislike this quote"></span>
                            <span class="fa fa-flag" title="Mark this as inappropriate"></span>
                            <button class="btn btn-link" name="deleteQuote" title="Delete this quote" value="{{ $quote->id }}">
                                <span class="fa fa-trash"></span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        {!! Form::close() !!}
        @yield('add_quote_button')
        @include('partials.app.pagination', ['paginator' => $quotes])
    @else
        <div class="text-center">
            <h3>We don't seem to have any good quotes ...</h3>
            <h4>You guys need to start embarrassing yourselves</h4>
            @yield('add_quote_button')
        </div>
    @endif
@endsection


@section('modal')
    @include('quotes.form')
@endsection