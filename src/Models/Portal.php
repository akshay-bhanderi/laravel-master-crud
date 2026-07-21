<?php

namespace AkshayBhanderi\LaravelMasterCrud\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * Default "current admin user" lookup model backing AuthController.
 * An app overrides this by defining its own App\Models\portal\Portal
 * class, which then wins automatically (see Modules::model()).
 */
class Portal extends Model
{
    public static function get_admin_user_data_new($email)
    {
        $result = DB::table('users as u')
            ->select('u.*', 'r.*')
            ->leftJoin('user_roles as r', 'u.user_role_id', '=', 'r.role_id')
            ->where('u.is_delete', 0)
            ->where('u.user_status', '=', 1)
            ->whereRaw('( u.user_email = ? OR user_phone_no = ? )', [$email, $email])
            ->first();

        if ($result) {
            return (array) $result;
        }

        return false;
    }

    public static function get_user_data($params = [])
    {
        $query = DB::table('users as u')
            ->leftJoin('user_roles as r', 'u.user_role_id', '=', 'r.role_id')
            ->where('u.is_delete', 0);

        if (!empty($params)) {
            $query = $query->where($params);
        }

        $result = $query->first();

        if ($result) {
            return (array) $result;
        }

        return false;
    }
}
