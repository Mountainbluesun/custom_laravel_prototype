<?php

namespace App\Policies;

use App\Models\User;

class StockMovementPolicy
{
    public function export(User $user): bool
    {
        return (bool) $user->is_admin;
    }

    public function viewGlobal(User $user): bool
    {
        return (bool) $user->is_admin;
    }
}
