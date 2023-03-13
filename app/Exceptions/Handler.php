<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use \Illuminate\Http\RedirectResponse as Redirection;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            return response()->json(array(
                'status' => $e->getCode(),
                'value' => array(
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace()
                )
            ));
        });
    }

    public function render($request, Throwable $exception)
    {
        $authorized = auth()->user();
        if(is_null($authorized)) return redirect('/');
        if(!is_null($authorized) && $authorized->role==0) return redirect('/');
        if($exception->getCode() == 404) return redirect('/');
        return parent::render($request, $exception);
    }
}
