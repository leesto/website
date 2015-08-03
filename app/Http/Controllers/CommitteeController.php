<?php

namespace App\Http\Controllers;

use App\CommitteeRole;
use App\Http\Requests;
use App\Http\Requests\CommitteeRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Szykra\Notifications\Flash;

class CommitteeController extends Controller
{
	/**
	 * View the committee.
	 * @return Response
	 */
	public function view()
	{
		$roles = CommitteeRole::orderBy('order', 'ASC')->get();
		$order = ['1' => 'At the beginning'];
		foreach($roles as $role) {
			$order[$role->order + 1] = "After '{$role->name}'";
		}

		return View::make('committee.view')->with([
			'roles' => $roles,
			'order' => $order,
		]);
	}

	/**
	 * Add a new committee position by ajax
	 * @param \App\Http\Requests\CommitteeRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CommitteeRequest $request)
	{
		// Require ajax
		$this->requireAjax($request);

		// Check the order
		$order = $this->verifyRoleOrder($request->get('order'));

		// Re-order any roles after
		$roles = CommitteeRole::where('order', '>=', $order)->get();
		foreach($roles as $role) {
			$role->order++;
			$role->save();
		}

		// Create the new role
		CommitteeRole::create($request->stripped('name', 'email', 'description', 'user_id') + ['order' => $order]);

		// Flash message
		Flash::success('Committee role added');

		return ['success' => true];
	}

	/**
	 * Process and save the changes.
	 * @param \App\Http\Requests\CommitteeRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function update(CommitteeRequest $request)
	{
		// Require ajax
		$this->requireAjax($request);

		// Get the role
		$role = CommitteeRole::find($request->get('id'));
		if(!$role) {
			return response("Could not find the role.", 422);
		}

		// Verify the order value
		$order = $this->verifyRoleOrder($request->get('order'));

		// Fix the ordering if it's changed
		if($order != $role->order) {
			// Moving earlier
			if($order < $role->order) {
				$roles = CommitteeRole::where('order', '>=', $order)->where('order', '<', $role->order)->get();
				foreach($roles as $r) {
					$r->order++;
					$r->save();
				}
			} // Moving later
			else {
				$roles = CommitteeRole::where('order', '<=', $order)->where('order', '>', $role->order)->get();
				foreach($roles as $r) {
					$r->order--;
					$r->save();
				}
			}
		}

		// Update the role
		$role->update($request->stripped('name', 'email', 'description', 'user_id') + ['order' => $order]);
		Flash::success("Role '{$role->name}' updated");

		return response(['success' => true]);
	}

	/**
	 * @param int $order
	 * @return int
	 */
	private function verifyRoleOrder($order)
	{
		if($order < 1 || ($order > CommitteeRole::count() && $order != 1)) {
			$order = CommitteeRole::count() + 1;

			return $order;
		}

		return $order;
	}
}
