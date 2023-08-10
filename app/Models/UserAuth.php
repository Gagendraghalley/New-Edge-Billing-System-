<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAuth extends Model
{

    public function setAuth($token)
    {

        $tokenParts = explode(".", $token);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

        $data = [
            'user' => $jwtPayload->user,
        ];

        return $data;
    }
    public function getUser($token)
    {
        $tokenParts = explode(".", $token);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        return $jwtPayload->user;
    }
}
