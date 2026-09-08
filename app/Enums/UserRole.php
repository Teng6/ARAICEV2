<?php

namespace App\Enums;

/**
 * The three roles are fixed per build plan §2.1 — Farmer (self-signup),
 * Technician (admin-created), Admin (seed/other admin). If a fourth role
 * is ever needed, this is a design decision to revisit, not just a value
 * to add.
 */
enum UserRole: string
{
    case Farmer = 'farmer';
    case Technician = 'technician';
    case Admin = 'admin';
}
