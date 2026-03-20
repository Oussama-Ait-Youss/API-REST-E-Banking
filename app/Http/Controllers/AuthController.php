<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Inscription d'un nouvel utilisateur (US-01)
     */
    public function register(Request $request)
    {
        // Validation des données entrantes
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'birth_date' => 'required|date|before:today',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Création de l'utilisateur
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birth_date' => $request->birth_date,
            'role' => 'client', // Rôle par défaut
        ]);

        // Connexion immédiate après inscription
        $token = Auth::guard('api')->login($user);

        return $this->respondWithToken($token, 'Inscription réussie', 201);
    }

    /**
     * Connexion (US-02)
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Identifiants incorrects'], 401);
        }

        return $this->respondWithToken($token, 'Connexion réussie');
    }

    /**
     * Déconnexion (Invalide le token)
     */
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Déconnexion réussie']);
    }

    /**
     * Rafraîchir un token expiré
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::guard('api')->refresh(), 'Token rafraîchi');
    }

    /**
     * Récupérer le profil de l'utilisateur connecté
     */
    public function me()
    {
        return response()->json(Auth::guard('api')->user());
    }

    /**
     * Formater la réponse contenant le token
     */
    protected function respondWithToken($token, $message = 'Succès', $status = 200)
    {
        return response()->json([
            'message' => $message,
            'access_token' => $token,
            'token_type' => 'bearer',
            // Le token expire dans X minutes (défini dans config/jwt.php, par défaut 60)
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60 
        ], $status);
    }
}