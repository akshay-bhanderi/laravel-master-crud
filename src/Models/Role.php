<?php

namespace AkshayBhanderi\LaravelMasterCrud\Models;

use Illuminate\Database\Eloquent\Model;
use AkshayBhanderi\LaravelMasterCrud\Concerns\HasMasterCrudModel;
use DB;

class Role extends Model
{
    use HasMasterCrudModel;

    private static $table_name = 'user_roles';
    private static $primary_id = 'role_id';

    public static function dt_list_data($params = [])
        {
            if(empty($params)){
                return false;
            }
            $get = request()->all();
            $limit_start        =   $get['iDisplayStart'];
            $limit_length       =   $get['iDisplayLength'];

            $query = DB::table(static::$table_name)
                        ->select('role_id','role_title','status')
                        ->where('is_delete',0);

            if (!empty($params['where_raw'])) {
                $query = $query->WhereRaw($params['where_raw']);
            }
            if (!empty($params['order_by'])) {
                $query = $query->orderBy($params['order_by'], ($params['order_by_type'] ?? 'DESC') );
            }
            $total = $query->get()->count();
            $query = $query->limit($limit_length)->offset($limit_start);
            $data = $query->get();
            return ['total'=>$total,"result"=>$data->all()];
        }

    public static function get_role_list()
        {
            $result = DB::table(static::$table_name)
                   ->where('status',1)
                   ->where('is_delete',0)
                   ->get()->toArray();
            if(!empty($result)){
                foreach($result as $k=>$val){
                    $user_count = DB::table('users')
                                    ->select(DB::raw('COUNT(user_id) as user_count'))
                                    ->where('is_delete',0)
                                    ->where('user_id','<>',1)
                                    ->where('user_role_id',$val->role_id)
                                    ->first();
                    if(!empty($user_count)){
                        $result[$k]->user_count = $user_count->user_count;
                    }
                }
            }
            return $result;
        }

}
