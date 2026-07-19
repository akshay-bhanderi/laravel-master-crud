<?php

namespace AkshayBhanderi\LaravelMasterCrud\Support;

class Access
{
    public static function permission_list()
    {
        return [
            'list'=>'List',
            'add'=>'Add',
            'edit'=>'Edit',
            'save'=>'Save',
            'status'=>'Status',
            'view'=>'View',
            'delete'=>'Delete',
        ];
    }

    public static function permission_fn_list()
    {
        return [
            'add'=>'add',
            'save'=>'save',
            'edit'=>'edit',
            'status'=>'status',
            'view'=>'view',
            'dt_col'=>'view',
            'dt_list'=>'list',
            'index'=>'view',
            'delete'=>'delete',
        ];
    }

    public static function module_list()
    {
        $common_permissions = self::permission_list();
        $common_permissions_fn_list = self::permission_fn_list();

        $modules = [];
        foreach (config('master-crud.modules', []) as $key => $module) {
            $modules[$key] = array_merge([
                'permissions' => array_merge($common_permissions, $module['permissions'] ?? []),
                'permission_functions' => array_merge($common_permissions_fn_list, $module['permission_functions'] ?? []),
                'db_permissions' => $module['db_permissions'] ?? [],
            ], $module);
        }

        return $modules;
    }

    public static function is_allowed($module_name='',$permission='view')
    {
        if(empty($module_name)){
            return false;
        }

        $userdata = session('admin');
        if(empty($userdata) || empty($userdata['user_role_id'])){
            return false;
        }

        if($userdata['user_role_id'] == 1){
            return true;
        }

        $index_permission = \DB::table('user_roles')->where('role_id', $userdata['user_role_id'])->first();
        if(empty($index_permission) || empty($index_permission->role_permission)){
            return false;
        }
        $role_permission = $index_permission->role_permission;

        if(!is_array($role_permission) && !is_object($role_permission)){
            $role_permission = json_decode($role_permission,true);
        }elseif(is_object($role_permission)){
            $role_permission = (array)$role_permission;
        }

        $is_allowed = false;
        try {
            $is_value_found = $role_permission[$module_name][$permission];
            if(!empty($is_value_found)){
                $is_allowed = true;
            }
        } catch (\Exception $e) {
        }
        try {
            $is_value_found = $role_permission[$module_name]['db_permissions'][$permission];
            if(!empty($is_value_found)){
                $is_allowed = true;
            }
        } catch (\Exception $e) {
        }

        return $is_allowed;
    }

    public static function is_route_allowed()
    {
        $userdata = session('admin');
        if(empty($userdata) || empty($userdata['user_role_id'])){
            return false;
        }

        if($userdata['user_role_id'] == 1){
            return true;
        }

        $index_permission = \DB::table('user_roles')->where('role_id', $userdata['user_role_id'])->first();
        if(empty($index_permission) || empty($index_permission->role_permission)){
            return false;
        }
        $role_permission = $index_permission->role_permission;

        $route_info = \Route::current()->getAction();

        if(!is_array($role_permission) && !is_object($role_permission)){
            $role_permission = json_decode($role_permission,true);
        }elseif(is_object($role_permission)){
            $role_permission = (array)$role_permission;
        }

        $temp_arr = explode('@', $route_info['controller']);
        $controller = $temp_arr[0];
        $function = $temp_arr[1];

        $all_permissions = collect(self::module_list());
        $all_permissions = $all_permissions->where('path',$controller)->all();

        $is_allowed = false;
        try {
            $module_name = key($all_permissions);
            $permission_type = $all_permissions[$module_name]['permission_functions'][$function];

            $is_value_found = $role_permission[$module_name][$permission_type];
            if(!empty($is_value_found)){
                $is_allowed = true;
            }
        } catch (\Exception $e) {
        }

        foreach (config('master-crud.always_allowed_controllers', []) as $allowed_controller) {
            if ($controller == $allowed_controller) {
                $is_allowed = true;
            }
        }

        return $is_allowed;
    }
}
