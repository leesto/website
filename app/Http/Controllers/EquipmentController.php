<?php

namespace App\Http\Controllers;

use App\EquipmentBreakage;
use App\Http\Requests;
use App\Http\Requests\GenericRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Szykra\Notifications\Flash;

class EquipmentController extends Controller
{
	/**
	 * Set the necessary middleware.
	 */
	public function __construct()
	{
		// Set the middleware
		$this->middleware('auth.permission:member', [
			'except' => [
				'update',
			],
		]);
		$this->middleware('auth.permission:admin', [
			'only' => [
				'update',
			],
		]);

		parent::__construct();
	}

	/**
	 * View the equipment dashboard
	 * @return Response
	 */
	public function dash()
	{
		// For the moment, just redirect
		// to the repairs database
		return redirect(route('equipment.repairs'));
	}

	/**
	 * View the repairs database.
	 * @return Response
	 */
	public function repairsDb()
	{
		// Get the breakages
		$breakages = EquipmentBreakage::where('status', '<>', EquipmentBreakage::STATUS_RESOLVED)->orderBy('created_at', 'DESC')->paginate(15);
		$this->checkPagination($breakages);

		return View::make('equipment.repairs_db')->withBreakages($breakages);
	}

	/**
	 * View the form to add a repair.
	 * @return Response
	 */
	public function getAddRepair()
	{
		return View::make('equipment.add_breakage');
	}

	/**
	 * Process the form to add a repair.
	 * @param \App\Http\Requests\GenericRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function postAddRepair(GenericRequest $request)
	{
		// Validate the input
		$this->validate($request, [
			'name'        => 'required',
			'location'    => 'required',
			'label'       => 'required',
			'description' => 'required',
		], [
			'name.required'        => 'Please enter the name of the broken equipment',
			'location.required'    => 'Please enter the current location of the equipment',
			'label.required'       => 'Please enter how the item is labelled',
			'description.required' => 'Please enter the details of the breakage',
		]);

		// Create the new breakage
		$breakage = EquipmentBreakage::create($request->stripped('name', 'label', 'location', 'description') + [
				'status'  => EquipmentBreakage::STATUS_REPORTED,
				'user_id' => $this->user->id,
			]);

		// Email the E&S officer
		Mail::queue('emails.equipment.new_breakage', [
			'breakage'  => $breakage,
			'user_name' => $breakage->user->name,
			'username'  => $breakage->user->username,
		], function ($message) {
			$message->subject('Equipment breakage')
			        ->to('equip@bts-crew.com');
		});

		// Flash message and redirect
		Flash::success('Breakage reported');

		return redirect(route('equipment.repairs'));
	}

	/**
	 * View the details of a reported repair.
	 * @param $id
	 * @return Response
	 */
	public function view($id)
	{
		$breakage = EquipmentBreakage::findOrFail($id);

		return View::make('equipment.breakage')->withBreakage($breakage);
	}

	/**
	 * Process the form for updating a repair's details.
	 * @param                                   $id
	 * @param \App\Http\Requests\GenericRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function update($id, GenericRequest $request)
	{
		// Get the breakage entry
		$breakage = EquipmentBreakage::findOrFail($id);

		// Validate
		$this->validate($request, [
			'status' => 'required|in:' . implode(',', array_keys(EquipmentBreakage::$status)),
		], [
			'status.required' => 'Please choose a status for the breakage',
			'status.in'       => 'Please choose a valid status',
		]);

		// Update, message and redirect
		$breakage->update($request->stripped('comment', 'status'));
		Flash::success('Breakage updated');

		return redirect(route('equipment.repairs'));
	}
}
