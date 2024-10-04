<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DashboardPolicy
{
    use HandlesAuthorization;

    /**
     * Verifica si el usuario puede ver el dashboard del administrador.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAdminDashboard(User $user)
    {
        // Lógica para determinar si el usuario puede ver el dashboard del admin
        return $user->hasRole('admin'); // Ajusta según tu lógica
    }

    /**
     * Verifica si el usuario puede ver el dashboard del encargado de sucursal.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewBranchManagerDashboard(User $user)
    {
        // Lógica para determinar si el usuario puede ver el dashboard del encargado de sucursal
        return $user->hasRole('branch_manager'); // Ajusta según tu lógica
    }
}
