<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\PollRequest;
use App\Poll;
use App\PollOption;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Szykra\Notifications\Flash;

class PollsController extends Controller
{
	/**
	 * List all the existing polls, with pagination.
	 * @return mixed
	 */
	public function index()
	{
		$polls = Poll::paginate(10);
		$this->checkPagination($polls);

		return View::make('polls.index')->with('polls', $polls);
	}

	/**
	 * The create form.
	 * @param \Illuminate\Http\Request $request
	 * @return Response
	 */
	public function create(Request $request)
	{
		$options = $request->old('option');
		if(!is_array($options)) {
			$options = [1 => '', 2 => '', 3 => ''];
		}

		return View::make('polls.create')->with('options', $options);
	}

	/**
	 * Process the poll creation.
	 * @param \App\Http\Requests\PollRequest $request
	 * @return Response
	 */
	public function store(PollRequest $request)
	{
		$poll = Poll::create($request->stripped('question', 'description') + [
				'show_results' => $request->has('show_results'),
				'user_id'      => $this->user->id,
			]);

		foreach($request->get('option') as $num => $text) {
			$poll->options()->create([
				'number' => (int) $num,
				'text'   => strip_tags($text),
			]);
		}

		Flash::success('Poll created');

		return redirect(route('polls.view', $poll->id));
	}

	/**
	 * Add a new option.
	 * @param \Illuminate\Http\Request $request
	 * @return Response
	 */
	public function addOption(Request $request)
	{
		$options   = $request->get('option');
		$options[] = '';

		return redirect()->back()->withInput(array_merge($request->all(), ['option' => $options]));
	}

	/**
	 * Remove the select option.
	 * @param \Illuminate\Http\Request $request
	 * @return Response
	 */
	public function deleteOption(Request $request)
	{
		$options = $request->get('option');
		if(count($options) > 1) {
			$index = $request->get('deleteOption');
			if(array_key_exists($index, $options)) {
				unset($options[$index]);
			}
		} else {
			Flash::warning('You need at least one answer.');
		}
		$options = array_combine(range(1, count($options)), array_values($options));

		return redirect()->back()->withInput(array_merge($request->all(), ['option' => $options]));
	}

	/**
	 * View a poll.
	 * @param int $id
	 * @return Response
	 */
	public function show($id)
	{
		$poll = Poll::find($id);
		if(!$poll) {
			App::abort(404);
		}

		return View::make('polls.view')->with('poll', $poll);
	}

	/**
	 * Cast the user's vote.
	 * @param int                      $id
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function castVote($id, Request $request)
	{
		// Get the poll
		$poll = Poll::find($id);
		if(!$poll) {
			return redirect(route('polls.index'));
		}

		// Check if already voted
		if($poll->voted($this->user)) {
			Flash::warning('You have already voted for this poll.');

			return redirect(route('polls.view', $id));
		}

		// Cast vote
		$option = PollOption::find($request->get('vote'));
		if(!$option) {
			return redirect(route('polls.view', $id));
		}
		$option->votes()->create(['user_id' => $this->user->id]);
		Flash::success('Vote cast');

		return redirect(route('polls.view', $id));
	}

	/**
	 * Delete a poll
	 * @param int $id
	 * @return Response
	 */
	public function delete($id)
	{
		$poll = Poll::find($id);
		if($poll) {
			$poll->delete();
			Flash::success('Poll deleted');
		}

		return redirect(route('polls.index'));
	}
}
