<?php

namespace App\enums;

enum Roles: string
{
    case ROLE_USER = 'ROLE_USER';
    case ROLE_EDITOR = 'ROLE_EDITOR';
    case ROLE_ADMIN = 'ROLE_ADMIN';

    public function getFrenchName(): string
    {
        return match ($this) {
            self::ROLE_USER => 'Utilisateur',
            self::ROLE_EDITOR => 'Éditeur',
            self::ROLE_ADMIN => 'Administrateur',
        };
    }
}
