<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Traits\TLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use TLog;

    private $jwtProvider;

    public function __construct(
        private UserRepositoryInterface $modelRepo,
    )
    {
        $this->middleware('auth:api', ['except' => ['register', 'login', 'refresh']]);
        $this->jwtProvider = JWTAuth::getJWTProvider();
    }

    //
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->modelRepo->create($request->all());

            event(new Registered($user));

            DB::commit();

            return responseApi([
                'user' => $user,
                'message' => __('User registered successfully!'),
            ], true, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('User registration failed!')
            ], false, 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');
        if (!$token = auth('api')->attempt($credentials)) {
            return responseApi([
                'message' => __('Email or password is incorrect!'),
            ], false, 401);
        }

        $refreshToken = $this->generateRefreshToken(auth('api')->user());

        return responseApi($this->getTokenInfo($token, $refreshToken));
    }

    public function refresh(Request $request): JsonResponse
    {
        $request->validate([
            'refresh_token' => 'required|string'
        ]);

        try {
            $refreshToken = $request->get('refresh_token');

            $decodedData = $this->jwtProvider->decode($refreshToken);

            if (!$user = $this->modelRepo->find($decodedData['user_id'])) {
                return responseApi([
                    'message' => __('User not found')
                ], false, 404);
            }

            if (auth('api')->check()) {
                auth('api')->invalidate();
            }

            $token = auth('api')->login($user);

            return responseApi($this->getTokenInfo($token));
        } catch (JWTException $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Refresh token is invalid')
            ], false, 401);
        }
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return responseApi([
            'message' => __('Successfully logged out')
        ]);
    }

    protected function getTokenInfo($token, $refreshToken = null): array
    {
        $dataReturn = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => auth('api')->user()->load('division'),
        ];

        return $refreshToken
            ? array_merge($dataReturn, ['refresh_token' => $refreshToken, 'refresh_token_expires_in' => config('jwt.refresh_ttl') * 60])
            : $dataReturn;
    }

    protected function generateRefreshToken($user): string
    {
        $dataToGenerateRefreshToken = [
            'user_id' => $user->id,
            'random' => rand() . time(),
            'exp' => time() + config('jwt.refresh_ttl')
        ];

        return $this->jwtProvider->encode($dataToGenerateRefreshToken);
    }
}
