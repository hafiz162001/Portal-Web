<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SubMenu;

class AclIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $routeName = \Route::currentRouteName();
        $rn = explode(".", $routeName);
        $subMenu = SubMenu::where('path', 'LIKE', $rn[0] . "%")->first();
        // access menu validation
        $status = false;

        if ($subMenu) {
            $aclMenu = $subMenu->aclMenu->where("user_id", \Auth::user()->id)->first();
            if ($aclMenu) {
                $role = !empty($aclMenu->user) ? $aclMenu->user->role : null;
                if ($role) {
                    if ((count($rn) > 1) && ($rn[1] == "index") && ($role->view == 1)) {
                        $status = true;
                    }
                    if ((count($rn) > 1) && ($rn[1] == "create" || $rn[1] == "store") && ($role->create == 1)) {
                        $status = true;
                    }
                    if ((count($rn) > 1) && ($rn[1] == "update" || $rn[1] == "edit") && ($role->update == 1)) {
                        $status = true;
                    }
                    if ((count($rn) > 1) && ($rn[1] == "delete" || $rn[1] == "destroy") && ($role->delete == 1)) {
                        $status = true;
                    }
                    if ((count($rn) > 1) && ($rn[1] == "show") && ($role->view == 1)) {
                        $status = true;
                    }
                }
            }
        }
        if ($status) return $next($request);
        return \Redirect::back();
    }
}
