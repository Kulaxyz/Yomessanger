<?php

namespace App\Http\Controllers\Api;

use App\Chat;
use App\Device;
use App\OTP;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Nexmo\Client\Exception\Exception;
use Nexmo\Laravel\Facade\Nexmo;

class AuthController
{
    public function login(Request $request)
    {
        if (!$request->device_id) {
            return response()->json(['data' => '', 'error' => 'device_id is required', 'status_code' => 400], 200);
        }

        $device = Device::where('uuid', $request->device_id)->first();
        if (!$device) {
            return response()->json(['status_code' => 403], 200);
        }

        $user = $device->user;
        $user->settings = $device->settings;

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'data' => ['access_token' => $tokenResult->accessToken,
                'user' => $user,
            ],
            'status_code' => 200,
        ], 200);
    }

    public function register(Request $request)
    {
        if (!$request->device_id) {
            return response()->json(['error' => 'Device_id is required', 'status_code' => 400], 200);
        }

        if (!$request->phone) {
            return response()->json(['status_code' => 422, 'error' => 'Incorrect phone number']);
        }

        $device = Device::where('uuid', $request->device_id)->first();

        if ($device) {
            if ($device->user->phone == $request->phone) {
                return response()->json(['data' => ['user' => $device->user], 'status_code' => 200], 200);
            }
            $device->delete();
        }

        $user = User::where('phone', $request->phone)->first();

        $verification = null;
        if (!$user) {
            $user = new User;
            $user->phone = $request->phone;
            $user->save();

            $verification = Nexmo::verify()->start([
                'number' => $user->phone,
                'brand'  => config('app.name')
            ]);


        } else {
            //TODO: send message with OTP
        }

        $device = new Device;
        $device->uuid = $request->device_id;
        $device->user_id = $user->id;
        $device->save();

        return response()->json([
            'data' => [
                'verification_id' => $verification->getRequestId(),
            ],
            'status_code' => 200,
        ], 200);
    }

    public function verify(Request $request)
    {
        if (!$request->device_id) {
            return response()->json(['error' => 'Device_id is required', 'status_code' => 400], 200);
        }

        if (!$request->code) {
            return response()->json(['error' => 'Verification code is required', 'status_code' => 400], 200);
        }

        if (!$request->verification_id) {
            return response()->json(['error' => 'Verification ID is required', 'status_code' => 400], 200);
        }

        $verified = false;
        if (substr($request->verification_id, 0, 4) == 'self') {
            $verified = OTP::where('expires_at', '>=', Carbon::now())
                ->where('code', $request->code)
                ->where('verification_id', $request->verification_id)->exists();
        } else {
            try {
                $verification = Nexmo::verify()->check($request->verification_id, $request->code);
                $verified = true;
            } catch (\Exception $e) {
                $verification = $e->getEntity();
                return response()->json(['error' => $verification['status'], 'status_code' => 422], 200);
            }
        }

        $device = Device::where('uuid', $request->device_id)->first();

        if (!$device) {
            return response()->json(['status_code' => 403], 200);
        }

        $device->verified_at = $verified ? Carbon::now() : null;

        if (!$verified) {
            return response()->json(['status_code' => 422, 'error' => 'Wrong verification code'], 200);
        }

        $user = $device->user;

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'data' => [
                'access_token' => $tokenResult->accessToken,
                'user' => $user,
                'verified' => $verified,
            ],
            'status_code' => 200,
        ], 200);
    }
}
