<?php
namespace App\Lib;
class ActionAuthorization{

    public static function check($user,$action_id)
    {
        $check = $user->isAdmin;
        foreach($user->roles as $role)
        {
            if($role->actions->contains($action_id))
               $check = true;
        }

        return $check;
    }
}
