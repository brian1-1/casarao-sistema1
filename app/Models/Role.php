<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Perfil de acesso (Cliente, Garçom, Cozinha, Gerente).
 */
class Role extends Model
{
    // Constantes para facilitar referência aos perfis no código
    public const CLIENTE = 'cliente';
    public const GARCOM  = 'garcom';
    public const COZINHA = 'cozinha';
    public const GERENTE = 'gerente';

    protected $fillable = ['slug', 'name'];

    /**
     * Usuários que possuem este perfil.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
