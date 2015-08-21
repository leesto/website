<?php

namespace App\Exceptions;

use Exception;
use Facebook\Exceptions\FacebookResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Response;
use ReflectionException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that should not be reported.
	 * @var array
	 */
	protected $dontReport = [
		HttpException::class,
	];

	/**
	 * Report or log an exception.
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 * @param  \Exception $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Exception               $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		// Make sure a 404 is thrown for:
		//   ModelNotFound
		//   ReflectionException
		if($e instanceof ModelNotFoundException || $e instanceof ReflectionException || $e instanceof FacebookResponseException) {
			return Response::view('errors.404', ['exception' => $e], 404);
		}

		return parent::render($request, $e);
	}
}
