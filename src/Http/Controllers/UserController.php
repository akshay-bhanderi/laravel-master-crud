<?php

namespace AkshayBhanderi\LaravelMasterCrud\Http\Controllers;

use Illuminate\Http\Request;
use AkshayBhanderi\LaravelMasterCrud\Concerns\HasMasterCrudActions;
use AkshayBhanderi\LaravelMasterCrud\Support\Modules;

class UserController extends Controller
{
    use HasMasterCrudActions;

    private $table;
    protected $model;

    protected $validation_rules = [
        'user_name' => 'required',
        'user_role_id' => 'required',
        'user_phone_no' => 'required|numeric|digits_between:10,12',
        'user_email' => 'nullable|email',
        'user_gender' => 'nullable',
        'user_address' => 'nullable',
        'user_profile_image' => 'nullable',
        'user_password' => 'nullable',
    ];

    public function __construct(){

        $this->model = Modules::model('User');

        $this->table = 'users';
        $this->route_name = 'user';
        $this->view_title = 'user & role';

        $this->view_path = 'portal.master.'.$this->route_name.'.';

        $grid_columns = [
            ['ID','5%','sortable','','text-center'],
            ['name','20%','sortable','',''],
            // ['email','20%','sortable','',''],
            ['role','10%','sortable','',''],
            ['phone','15%','sortable','',''],
            ['status','15%','sortable','',''],
            ['Added By','10%','','',''],
            ['action','10%','','',''],
        ];
        $this->columns = ["u.user_id","u.user_name","r.role_title","u.user_phone_no","u.status"];
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

        $this->gender_type = [
            '1' => 'Male',
            '0' => 'Female',
        ];

        \View::share('active', $this->route_name);
        \View::share('sub', $this->route_name);
        \View::share('title', $this->view_title.' Management');
        \View::share('route', $this->route_name);
        \View::share('page_title', $this->route_name);
        \View::share('breadcrumb', $this->breadcrumb());
        \View::share('gender_type', $this->gender_type);
    }

    public function index()
        {
            $data=[];

            $data["extra_pages"] = [];
            $data["filter_file"] = 'portal.master.role.filter';

            $roleClass = Modules::model('Role');
            $data['role_data']   = $roleClass::get_role_list();
            return view('portal.master.master',$data);
        }

    public function add(){
        $data = [];
        $roleClass = Modules::model('Role');
        $data['role_list'] = $roleClass::get_list()->pluck('role_title','role_id')->all();
        return view($this->view_path.'add',$data);
    }

    public function edit($passed_id){
        $model = $this->model;
        $data = $model::edit($passed_id);
        $data['user_password'] = $data['user_sweet_word'];
        $roleClass = Modules::model('Role');
        $data['role_list'] = $roleClass::get_list()->pluck('role_title','role_id')->all();
        \View::share('data', $data);
        return view($this->view_path.'add',$data);
    }

    protected function before_save(array $data, array $params, string $mode): array
    {
        $model = $this->model;

        if ($mode == 'add') {
            $data['user_sweet_word'] = $data['user_password'];
            $data['user_sweet_words'] = json_encode([$data['user_password']]);
        } else {
            $old_data = $model::edit($params['id']);
            if($data['user_password'] !== $old_data['user_sweet_word']){
                $data['user_sweet_word'] = $data['user_password'];
                $old_pass = json_decode($old_data['user_sweet_words']);
                $old_pass[] = $data['user_password'];
                $data['user_sweet_words'] = json_encode($old_pass);
            }
        }
        $data['user_password'] = \Hash::make($data['user_password']);

        return $data;
    }

    protected function dt_row($row)
    {
        return [
            '#'.$row->user_id,
            $this->text([
                '' => $row->user_name,
            ], 'full',url($this->route_name.'-edit/'.$row->user_id)),
            $row->role_title,
            $this->link('tel:'.$row->user_phone_no, '<i class="bi bi-telephone-fill text-indigo"></i> '.$row->user_phone_no),
            $this->simple_status($row->status),
            ($temp_user->user_name ?? '-'),
            $this->action_btn([
                $this->delete_btn($row->user_id),
                $this->status_btn($row->user_id,$row->status),
            ], url($this->route_name.'-edit/'.$row->user_id),'Edit'),
        ];
    }

}
