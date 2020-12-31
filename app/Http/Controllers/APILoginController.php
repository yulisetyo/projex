<?php

namespace App\Http\Controllers;

use \App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Tymon\JWTAuth\Claims\Issuer;
use Tymon\JWTAuth\Claims\IssuedAt;
use Tymon\JWTAuth\Claims\Expiration;
use Tymon\JWTAuth\Claims\NotBefore;
use Tymon\JWTAuth\Claims\JwtId;
use Tymon\JWTAuth\Claims\Subject;


class APILoginController extends Controller
{
	/**
	 * description 
	 */
	public function __construct()
	{
		//~ $this->middleware('auth:api', ['except' => ['login']]);
	}

	/**
	 * description 
	 */
	public function register(Request $request)
	{
		$user = User::create([
			'name'    => $request->name,
			'email'    => $request->email,
			'password' => bcrypt($request->password),
		]);
		
		$token = auth()->login($user);

		return $this->respondWithToken($token);
	}
    
    /**
	 * description 
	 */
	public function login()
	{
		$credentials = request(['email', 'password']);

		if (! $token = auth()->attempt($credentials)) {
			return response()->json(['error' => 'Unauthorized'], 401);
		}
		
		return $this->respondWithToken($token);
	}

	/**
	 * description 
	 */
	public function me()
	{
		return response()->json(auth()->user());
	}

	/**
	 * description 
	 */
	public function logout()
	{
		auth()->logout();

		return response()->json(['message' => 'Successfully logged out']);
	}

	/**
	 * description 
	 */
	public function refresh()
	{
		return $this->respondWithToken(auth()->refresh());
	}

	/**
	 * description 
	 */
	protected function respondWithToken($token)
	{
		return response()->json([
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => auth()->factory()->getTTL() * 15,
			'foo' => 'bar',
		]);
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
