<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\Hospital\HospitalPermissionService;

class CheckHospitalUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function __construct(
        HospitalPermissionService $hospital_permission_service
    ) {
        $this->hospital_permission_service = $hospital_permission_service;
    }

    public function handle($request, Closure $next)
    {
        $menu_key_name = $request->route()->getName();
        $return = $this->hospital_permission_service->hospitalCheckMenuPermission($menu_key_name);
        if(!$return['success'])
        {
            if($return['error_code'] == 403)
            {
                return response()->json($return);
            }
        }
        return $next($request);
    }
}
