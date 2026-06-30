<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware que restringe o acesso às rotas conforme o perfil do usuário.
 * Uso: ->middleware('role:gerente') ou ->middleware('role:garcom,gerente')
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Não autenticado → vai para o login
        if (! $user) {
            return redirect()->route('login');
        }

        // Verifica se o perfil do usuário está entre os permitidos
        if (! $user->role || ! in_array($user->role->slug, $roles, true)) {
            abort(403, 'Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
}
