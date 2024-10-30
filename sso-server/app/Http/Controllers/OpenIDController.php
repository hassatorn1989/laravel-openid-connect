<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

class OpenIDController extends Controller
{

    public function issueIDToken(Request $request)
    {
        $user = Auth::user(); // ดึงข้อมูลผู้ใช้ที่ล็อกอิน

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // สร้าง ID Token
        $signer = new Sha256();
        $token = (new Builder())
            ->issuedBy(env('APP_URL'))
            ->permittedFor('http://localhost:8001')
            ->identifiedBy('4f1g23a12aa', true)
            ->issuedAt(time())
            ->expiresAt(time() + 3600)
            ->withClaim('sub', $user->id)
            ->withClaim('name', $user->name)
            ->withClaim('email', $user->email)
            ->getToken($signer, new Key('your-signature-key'));

        return response()->json([
            'id_token' => (string) $token,
            'token_type' => 'Bearer',
            'expires_in' => 3600,
        ]);
    }
}
