<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\PageRequest;
use App\Page;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Szykra\Notifications\Flash;

class PageController extends Controller
{
	/**
	 * Set the authentication middleware.
	 */
	public function __construct()
	{
		// Require authentication
		$this->middleware('auth.permission:admin', [
			'except' => [
				'show',
			],
		]);

		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 * @return Response
	 */
	public function index()
	{
		$pages = Page::paginate(15);

		return View::make('page.index')->with('pages', $pages);
	}

	/**
	 * Show the form for creating a new resource.
	 * @return Response
	 */
	public function create()
	{
		return View::make('page.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * @param PageRequest $request
	 * @return Response
	 */
	public function store(PageRequest $request)
	{
		// Create
		$page = Page::create($request->only('title', 'slug', 'content', 'published', 'user_id'));

		// Redirect
		if($page->save()) {
			Flash::success('Page Created', "The page '" . $request->get('title') . "' was successfully created.");

			return redirect(route('page.show', $request->get('slug')));
		} else {
			Flash::error('Error', "Something went wrong while trying to update the page '" . $request->get('title') . "'.");

			return redirect()->back();
		}
	}

	/**
	 * Display the specified resource.
	 * @param  string $slug
	 * @return Response
	 */
	public function show($slug)
	{
		// Get the page
		$page = Page::findBySlugOrFail($slug, false);

		// Check if the page is published
		if(!$page->published) {
			if(!$this->user->isAdmin()) {
				App::abort(404);
			} else {
				$this->unpublishedFlash($page);
			}
		}

		return View::make('page.view')->with('page', $page);
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param  string $slug
	 * @return Response
	 */
	public function edit($slug)
	{
		$page = Page::findBySlugOrFail($slug, false);

		// Flash message if unpublished
		if(!$page->published) {
			$this->unpublishedFlash($page);
		}

		return View::make('page.edit')->with('page', $page);
	}

	/**
	 * Update the specified resource in storage.
	 * @param   string      $slug
	 * @param   PageRequest $request
	 * @return  Response
	 */
	public function update($slug, PageRequest $request)
	{
		// Update
		$status = Page::findBySlugOrFail($slug, false)
		              ->update($request->only('title', 'slug', 'content', 'published', 'user_id'));

		// Flash message
		if($status) {
			Flash::success('Updated', "The page '" . $request->get('title') . "' was successfully updated.");
		} else {
			Flash::error('Error', "Something went wrong while trying to update the page '" . $request->get('title') . "'.");
		}

		// Redirect
		return redirect(route('page.show', $request->get('slug')));
	}

	/**
	 * Remove the specified resource from storage.
	 * @param  string $slug
	 * @return Response
	 */
	public function destroy($slug)
	{
		// Delete
		$page   = Page::findBySlugOrFail($slug, false);
		$status = $page->delete();

		// Flash message
		if($status) {
			Flash::success('Page Deleted', "The page '" . $page->title . "' was successfully deleted.");
		} else {
			Flash::error('Error', "Something went wrong while trying to delete the page '" . $page->title . "'.");
		}

		// Redirect
		return redirect(route('home'));
	}

	/**
	 * @param $page
	 */
	private function unpublishedFlash($page)
	{
		Flash::warning('Page not published',
			"The page '{$page->title}' is not visible to the public as it isn't published. <a href=\"" . route('page.edit', $page->slug)
			. "#published\">Edit the page</a>.");
	}
}
