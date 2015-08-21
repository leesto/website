<?php

namespace App\Http\Controllers;

use App\EquipmentBreakage;
use App\Http\Requests;
use App\Http\Requests\GenericRequest;
use Illuminate\Http\Request;
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
		return View::make('equipment.repairs_add');
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
			'name'        => $breakage->name,
			'location'    => $breakage->location,
			'label'       => $breakage->label,
			'description' => $breakage->description,
			'user_name'   => $breakage->user->name,
			'username'    => $breakage->user->username,
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
		return $id;
	}

	/**
	 * Process the form for updating a repair's details.
	 * @param $id
	 * @return Response
	 */
	public function update($id)
	{
		return $id;
	}
}
