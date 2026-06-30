<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controla autenticação (login/logout) e redirecionamento por perfil.
 */
class AuthController extends Controller
{
    /**
     * Exibe o formulário de login.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    /**
     * Processa a tentativa de login.
     */
    public function login(Request $request)
    {
        // Validação dos campos
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Informe o e-mail.',
            'email.email'       => 'Informe um e-mail válido.',
            'password.required' => 'Informe a senha.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Bloqueia usuários inativos
            if (! Auth::user()->active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Usuário inativo. Contate o gerente.'])->onlyInput('email');
            }

            $request->session()->regenerate();

            return $this->redirectByRole();
        }

        return back()
            ->withErrors(['email' => 'Credenciais inválidas. Verifique e-mail e senha.'])
            ->onlyInput('email');
    }

    /**
     * Encerra a sessão.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redireciona o usuário para o painel correto conforme o perfil.
     */
    protected function redirectByRole()
    {
        $slug = Auth::user()->role?->slug;

        return match ($slug) {
            Role::GERENTE => redirect()->route('gerente.dashboard'),
            Role::GARCOM  => redirect()->route('garcom.index'),
            Role::COZINHA => redirect()->route('cozinha.index'),
            Role::CLIENTE => redirect()->route('cliente.mesas'),
            default       => redirect()->route('login'),
        };
    }
}
