<?php

namespace AkshayBhanderi\LaravelMasterCrud\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class User extends Model
{
    private static $table_name = 'users';
    private static $primary_id = 'user_id';

    public static function dt_list_data($params = [])
        {
            if(empty($params)){
                return false;
            }
            $get = request()->all();
            $limit_start        =   $get['iDisplayStart'];
            $limit_length       =   $get['iDisplayLength'];

            $query = DB::table(static::$table_name.' as u')
                        ->leftJoin('user_roles as r','r.role_id','=','u.user_role_id')
                        ->where('u.user_id','<>',1)
                        ->select('u.*','r.role_title')
                        ->where('u.is_delete',0);

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

    public static function get_list($where = [])
        {
            return static::list($where)->get();
        }

    public static function list($where = [])
        {
            return DB::table(static::$table_name)
                                    ->where('user_id','<>',1)->where('is_delete',0)->where($where);
        }

    public static function edit($id = '')
        {
            $result = DB::table(static::$table_name)
                        ->where(static::$primary_id,$id)
                        ->where('user_id','<>',1)
                        ->where('is_delete',0)
                        ->first();
            $return = (array)$result;
            return array_merge($return,['id'=>$id]);
        }

    public static function insert_data($data=[])
        {
        	if(empty($data)){ return false; }
        	$is_inserted = DB::table(static::$table_name)->insertGetId($data);
        	return $is_inserted;
        }

    public static function update_data($id = '',$data=[])
        {
        	if(empty($data)){ return false; }
        	$is_updated = DB::table(static::$table_name)->where(static::$primary_id,$id)->update($data);
        	return $is_updated;
        }

    public static function is_duplicate($params = [])
        {
            $result = DB::table(static::$table_name)
                ->where('is_delete',0)
                ->where('user_id','<>',1)
                ->where($params)
                ->get()->count();

            if($result <= 0){
                return false;
            }
            return true;
        }

}
