<?php

namespace AkshayBhanderi\LaravelMasterCrud\Http\Controllers;

use Illuminate\Http\Request;
use AkshayBhanderi\LaravelMasterCrud\Concerns\HasMasterCrudActions;
use AkshayBhanderi\LaravelMasterCrud\Support\Modules;

class RoleController extends Controller
{
    use HasMasterCrudActions;

    private $table;
    protected $model;

    protected $validation_rules = [
        "role_title"            => 'required',
        "role_permission"            => 'required',
    ];

    public function __construct(){

        $this->model = Modules::model('Role');

        $this->table = 'user_roles';
        $this->route_name = 'role';
        $this->view_title = 'User & Role';

        $this->view_path = 'portal.master.'.$this->route_name.'.';

        $grid_columns = [
            ['ID','5%','sortable','','text-center'],
            ['name','40%','sortable','',''],
            ['action','10%','','',''],
        ];
        $this->columns = ['role_id','role_title'];
        $this->order_by       = 0;
        $this->search_columns = [];
        $this->order_by_type  = "DESC";

        \View::share('grid', [
            'show_btn'              =>  true,
            'btn_name'              =>  'Add '.str_replace('-', ' ', $this->route_name),
            'btn_url'               =>  route($this->route_name.'.add'),
            'grid_columns'          =>  $grid_columns,
            'grid_order_by'         =>  $this->order_by,
            'grid_order_by_type'    =>  $this->order_by_type,
            'grid_tbl_name'         =>  $this->table,
            'grid_tbl_length'       =>  '50',
        ]);

        $this->type_arr = [
            '0' => 'inhouse',
            '1' => 'outsource',
        ];

        $this->module_data = \Access::module_list();

        \View::share('active', $this->route_name);
        \View::share('sub', $this->route_name);
        \View::share('title', $this->view_title.' Management');
        \View::share('route', $this->route_name);
        \View::share('page_title', $this->route_name);
        \View::share('breadcrumb', $this->breadcrumb());
        \View::share('role_type', $this->type_arr);

        \View::share('module_data', $this->module_data);
    }

    protected function before_save(array $data, array $params, string $mode): array
    {
        $data['role_permission'] = json_encode($data['role_permission']);
        return $data;
    }

    protected function dt_row($row)
    {
        return [
            '#'.$row->role_id,
            $row->role_title,
            $this->action_btn([
                $this->delete_btn($row->role_id),
            ],url($this->route_name.'-edit/'.$row->role_id),'Edit'),
        ];
    }

}
