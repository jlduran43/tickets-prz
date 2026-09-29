<?php

namespace App\Http\Controllers;

use App\Services\OfflineAuthSigner;
use Illuminate\Http\Request;

class OfflineAuthController extends Controller
{
    public function preparar(
        Request $request,
        OfflineAuthSigner $signer
    ) {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'ok' => false,
                'message' => 'Sesión no válida.',
            ], 401);
        }

        if ($user->rol !== 'CONTROL') {
            return response()->json([
                'ok' => false,
                'message' => 'Usuario no autorizado.',
            ], 403);
        }

        $request->validate([
            'device_id' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        $resultado = $signer->generarPermiso(
            $user,
            $request->device_id
        );

        return response()->json([
            'ok' => true,

            'permit' => $resultado['permit'],

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'CONTROL',
            ],

            'expires_at' =>
            $resultado['expires_at'],
        ]);
    }
}