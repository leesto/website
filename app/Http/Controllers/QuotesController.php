<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\QuoteRequest;
use App\Quote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Szykra\Notifications\Flash;

class QuotesController extends Controller
{
	/**
	 * List quotes, with pagination.
	 * @return string
	 */
	public function index()
	{
		$quotes = Quote::orderBy('date', 'DESC')->paginate(15);

		// Go to page 1 if no results
		if(count($quotes) == 0 && !is_null(Input::get('page')) && (int) Input::get('page') != 1) {
			return redirect(route('quotes.index'));
		}

		return View::make("quotes.index")->with("quotes", $quotes);
	}

	/**
	 * @param \App\Http\Requests\QuoteRequest $request
	 * @return string
	 */
	public function store(QuoteRequest $request)
	{
		if($request->ajax()) {
			Quote::create($request->stripped('culprit', 'quote') + [
					'date'     => Carbon::createFromFormat("Y-m-d H:i", $request->get('date')),
					'added_by' => Auth::user()->id,
				]);
			Flash::success('The quote was added successfully');

			return ['success' => true];
		} else {
			App::abort(404);
		}
	}

	/**
	 * @param \Illuminate\Http\Request $request
	 * @return string
	 */
	public function destroy(Request $request)
	{
		$quote = Quote::find($request->get('deleteQuote'));

		if($quote) {
			if($quote->delete()) {
				Flash::success("Quote deleted");
			} else {
				Flash::error("Oops", "Something went wrong when trying to delete that quote.");
			}
		} else {
			Flash::warning("Oops", "The selected quote couldn't be found; perhaps it's been deleted?");
		}

		return redirect()->back();
	}
}
