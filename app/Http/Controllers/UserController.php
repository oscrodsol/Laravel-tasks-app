<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    const ROLE_SUPER_ADMIN = 3;

    public function addSuperAdminRoleToUser($id) {
        try {
            $user = User::find($id);

            $user->roles()->attach(self::ROLE_SUPER_ADMIN);

            return response()->json([
                'success' => true,
                'message' => 'Super admin role added to user',
            ]);

        } catch (\Exception $exception) {
            Log::error('Error adding super admin role to User: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error adding super admin role to User'
            ], 500);
        }
    }
}
