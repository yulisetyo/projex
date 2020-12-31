<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        //~ return parent::render($request, $exception);

        if ($exception instanceof UnauthorizedHttpException) {
			
			$preException = $exception->getPrevious();

			if($preException instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
				
				return response()->json(['error' => 'TOKEN_EXPIRED']);
				
			} else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {

				return response()->json(['error' => 'TOKEN_INVALID']);
				
			} else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
				
				return response()->json(['error' => 'TOKEN_BLACKLISTED']);
				
			}

			if ($exception->getMessage() === 'Token not provided') {
				
				return response()->json(['error' => 'Token not provided']);
				
			}
			
		}
    }

	/**
	 * description 
	 */
	public function getTokenFromUserObject()
	{
		$user = User::first();
		$token = JWTAuth::fromUser($user);
		
		return $this->respondWithToken($token);
	}

	/**
	 * description 
	 */
	public function getTokenFromOtherAttributes()
	{
		$data = [
			'foo' => 'lorem',
			'bar' => 'ipsum',
			'iss' => new Issuer('faker'),
			'iat' => new IssuedAt(Carbon::now('UTC')),
			'exp' => new Expiration(Carbon::now('UTC')->addDays(1)),
			'nbf' => new NotBefore(Carbon::now('UTC')),
			'sub' => new Subject('faker'),
			'jti' => new JwtId('faker'),
		];

		$customClaims = JWTFactory::customClaims($data);
		$payload = JWTFactory::make($data);
		$token = JWTAuth::encode($payload);
		
		return $this->respondWithToken($token->get());
	}
}
