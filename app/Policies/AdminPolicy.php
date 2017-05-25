<?php

namespace Noox\Policies;

use Noox\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Super admin can do anything.
     * 
     * @param  \Noox\Models\Admin $user    
     * @param  string $ability 
     * @return mixed          
     */
    public function before(Admin $user, $ability)
    {
        if ($user->role === 2) {
            return true;
        }
    }

    /**
     * Determine whether the current admin can view the list admin.
     *
     * @param  \Noox\Models\Admin  $user
     * @param  \Noox\Models\Admin  $admin
     * @return mixed
     */
    public function view(Admin $user)
    {
        return $user->role === 2;
    }

    /**
     * Determine whether the current admin can view the admin.
     *
     * @param  \Noox\Models\Admin  $user
     * @param  \Noox\Models\Admin  $admin
     * @return mixed
     */
    public function profile(Admin $user, Admin $admin)
    {
        return $user->id === $admin->id;
    }

    /**
     * Determine whether the current admin can create admins.
     *
     * @param  \Noox\Models\Admin  $user
     * @return mixed
     */
    public function create(Admin $user)
    {
        return $user->role === 2;
    }

    /**
     * Determine whether the current admin can update the admin.
     *
     * @param  \Noox\Models\Admin  $user
     * @param  \Noox\Models\Admin  $admin
     * @return mixed
     */
    public function update(Admin $user, Admin $admin)
    {
        return $user->id === $admin->id;
    }

    /**
     * Determine whether the current admin can delete the admin.
     *
     * @param  \Noox\Models\Admin  $user
     * @param  \Noox\Models\Admin  $admin
     * @return mixed
     */
    public function delete(Admin $user, Admin $admin)
    {
        return $user->role === 2;
    }
}
