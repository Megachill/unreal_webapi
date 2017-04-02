<?php
/**
 * @author      Megachill
 * @date        31/03/2017
 * @file        MemberController.php
 * @copyright   MIT
 */

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class MemberController extends Controller
{

    public function index()
    {
        if (! $user = JWTAuth::parseToken()->authenticate())
        {
            return response()->json(['not_found'], 404);
        }

        return response()->json([
            'user' => compact('user'),
            'message' => 'Hello world'
        ]);
    }

}