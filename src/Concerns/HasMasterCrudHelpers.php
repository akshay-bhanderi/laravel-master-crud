<?php

namespace AkshayBhanderi\LaravelMasterCrud\Concerns;

use Illuminate\Support\Facades\Validator;
use AkshayBhanderi\LaravelMasterCrud\Support\Access;

trait HasMasterCrudHelpers
{
    public function js_to_php($filter='')
    {
        $arr = $this->columns;
        $search_arr = $this->search_columns;
        $order_by = $this->order_by;
        $order_by_type = $this->order_by_type;

        $search_arr = array_merge($arr,$search_arr);
        $order_by = $arr[$order_by];

        $get = request()->all();
        $where = '';

        if ( $get['iSortCol_0'] !== FALSE ){
            for ( $i=0 ; $i<intval($get['iSortingCols']); $i++ ){ if ($get['bSortable_'.intval($get['iSortCol_'.$i])] == "true" ){ $order_by = $arr[ intval( ( $get['iSortCol_'.$i] ) ) ]; $order_by_type = $this->mres( $get['sSortDir_'.$i] ); }
            }
        }

        for ( $i=0 ; $i<count($search_arr) ; $i++ ){ if ( isset($get['bSearchable_'.$i])  && $get['bSearchable_'.$i] == "true" && $get['sSearch_'.$i] != '' ){if($where != ''){$where .= " AND ";} $where .= $search_arr[$i]." = '".$this->mres($get['sSearch_'.$i])."' ";}
        }

        if( isset($get['sSearch'])  ){
            $where .= '('; $or = '';foreach( $search_arr as $row ){ $where .= $or.$row." LIKE '%".str_replace("'","\\\\\''",$this->mres($get['sSearch']))."%'"; if($or== ''){$or =' OR ';} }$where .= ')';
        }

        if(!empty($where) && !empty($filter) && !preg_match('/^\s*(AND|OR)\b/', trim($filter))){
            $where = $where.' AND '.$filter;
        }elseif(!empty($filter)){
            $where = $where.$filter;
        }

        return [
            'where_raw' =>  $where,
            'order_by' => $order_by,
            'order_by_type' => $order_by_type,
        ];
    }

    public function dt_response($all_data=[],$data=[]){
        return response()->json([
            'iTotalRecords' => $all_data['total'],
            'iTotalDisplayRecords' => $all_data['total'],
            'aaData' => $data,
        ]);
    }

    public function show_image($link='',$width='auto',$height='100px')
    {
        if(!empty($link)){
            if(is_numeric($link)){
                $link = $this->get_image_from_id($link);
            }
            return '<img src="'.$link.'" class="img-fluid" style="width:'.$width.';height:'.$height.';object-fit:contain;" />';
        }else{
            return '<img src="'.$link.'" class="img-fluid" style="width:'.$width.';height:'.$height.';object-fit:contain;" />';
        }
    }

    public function edit_btn($id,$title='edit')
    {
        try {
            $url = route($this->route_name.'.edit',[$id]);
        } catch (\Exception $e) {
            $url = '#';
        }
        if(Access::is_allowed($this->route_name,'edit')){
            return '<a class="dropdown-item"  href="'.$url.'" title="'.$title.'">'.ucfirst($title).'</a>';
        }
    }

    public function copy_btn($id,$title='duplicate')
    {
        try {
            $url = route($this->route_name.'.duplicate',[$id]);
        } catch (\Exception $e) {
            $url = '#';
        }
        if(Access::is_allowed($this->route_name,'edit')){
            return '<a class="dropdown-item"  href="'.$url.'" title="'.$title.'">'.ucfirst($title).'</a>';
        }
    }

    public function view_btn($id,$title='view')
    {
        try {
            $url = route($this->route_name.'.view',[$id]);
        } catch (\Exception $e) {
            $url = '#';
        }
        if(Access::is_allowed($this->route_name,'view')){
            return '<a class="dropdown-item"  href="'.$url.'" title="'.$title.'">'.ucfirst($title).'</a>';
        }
    }

    public function other_btn($title='view',$url='#')
    {
        if(Access::is_allowed($this->route_name,'view')){
            return '<a class="dropdown-item"  href="'.$url.'" title="'.$title.'">'.ucfirst($title).'</a>';
        }
    }

    public function party_btn($title='',$id='',$fn='')
    {
        return '<a class="dropdown-item" href="javascript:;" onclick="'.$fn.'('.$id.')" title="'.$title.'">'.ucfirst($title).'</a>';
    }

    public function delete_btn($id)
    {
        if(Access::is_allowed($this->route_name,'delete')){
            return '<a class="dropdown-item" href="javascript:;" onclick="js_delete('.$id.')"  title="Delete">Delete</a>';
        }
    }

    public function text($arr=[],$full_text='',$link="")
    {
        if(empty($arr)){
            return '';
        }
        $return_data = [];
        foreach ($arr as $key => $value) {
            if(empty($full_text)){
                $value = ((strlen($value) > 50) ? substr($value, 0, 50).'...' : $value);
            }
            if(!empty($key)){
                $return_data[] = '<b>'.ucfirst($key).':</b> '. $value ;
            }else{
                $return_data[] = $value ;
            }
        }
        return '<div data-href="'.$link.'" style="display: inline-block; width: 100%; word-break: break-all;text-wrap: pretty;">'.implode('<br>', $return_data).'</div>';
    }

    public function date($date,$formate='d-m-Y')
    {
        $return_data = date($formate, strtotime($date));
        if( $return_data !== '01-01-1970'){
            return $return_data;
        }
        return '-';
    }

    public function a_href($link,$title,$target="_self")
    {
        return '<a target="'.$target.'" href="'.$link.'">'.$title.'</a>';
    }

    public function status_btn($id='',$type='')
    {
        if(empty($this->status)){
            if($type == 1){
                $status = 'InActive';
                $status_type = 0;
            }else{
                $status = 'Active';
                $status_type = 1;
            }
            return '<a class="dropdown-item" href="javascript:;" onclick="js_status('.$id.','.$status_type.')">'.$status.'</a>';
        }else{
            if($type == 1 ){
                return '<a class="dropdown-item" href="javascript:;" onclick="js_status('.$id.',0,\''.$this->status[0].'\')">'.$this->status[0].'</a>';
            }else{
                return '<a class="dropdown-item" href="javascript:;" onclick="js_status('.$id.',1,\''.$this->status[1].'\')">'.$this->status[1].'</a>';
            }
        }
    }

    public function simple_status($current_status='0',$list=['0'=>'InActive','1'=>'Active'])
    {
        $key = (string)$current_status;
        if (empty($this->status)){
            $label = $list[$key] ?? ($list[0] ?? ($list['0'] ?? '-'));
            if ($key == '0' || $key === 0){
                return '<span class="badge px-3 px-2 bg-info">'.$label.'</span>';
            }elseif ($key == '1' || $key === 1){
                return '<span class="badge px-3 px-2 bg-success">'.$label.'</span>';
            }else{
                return '<span class="badge px-3 px-2 bg-secondary">'.$label.'</span>';
            }
        }else{
            $label = $this->status[$key] ?? ($list[$key] ?? '-');
            if ($key == '1' || $key === 1){
                return '<span class="badge px-3 px-2 bg-info">'.$label.'</span>';
            }else{
                return '<span class="badge px-3 px-2 bg-success">'.$label.'</span>';
            }
        }
    }

    public function select($arr='', $selected='' , $attr='')
    {
        $return = '<select class="form-control" '.$attr.' >';
        foreach ($arr as $key => $value) {
            $is_selected = '';
            if($selected == $key){
                $is_selected = 'selected';
            }
            $return .= '<option '.$is_selected.' value="'.$key.'">'.$value.'</option>';
        }
        $return .= '</select>';
        return $return;
    }

    public function badge($title='', $class="bg-success")
    {
        return '<span class="badge my-1 px-2 '.$class.'">'.ucfirst($title).'</span>';
    }

    public function action_btn($arr,$link='',$title='')
    {
        if(empty($link) && empty($title) ){
            return '<div class="dropdown ms-auto">
                <a href="#" data-bs-toggle="dropdown" class="btn btn-floating btn-sm" aria-haspopup="true" aria-expanded="false">
                    <i class="bi bi-three-dots"></i>
                </a>
                <div class="dropdown-menu " style="margin: 0px;">'.implode('', $arr).'</div>
            </div>';
        }else{
            return '<div class="btn-group me-1">
                <a href="'.$link.'" type="button" class="btn btn-sm btn-outline-primary">'.$title.'</a>
                <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Action</span>
                </button>
                <ul class="dropdown-menu" style="margin: 0px;">'.implode('', $arr).'</ul>
            </div>';
        }
    }

    public function mres($params)
    {
        $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
        $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

        return str_replace($search, $replace, $params);
    }

    public function breadcrumb($arr = [])
    {
        try {
            if(!\App::runningInConsole()){
                $route_info = \Route::current()->getAction();
                $function = explode('@', $route_info['uses'])[1];
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return [];
        }

        $breadcrumb = [];
        switch ($function) {
            case 'index':
                $breadcrumb = [
                    ucfirst($this->view_title).' List' => route($this->route_name.'.master'),
                ];
                break;
            case 'add':
                $breadcrumb = [
                    ucfirst($this->view_title).' List' => route($this->route_name.'.master'),
                    'Add '.ucfirst($this->view_title) => '#',
                ];
                break;
            case 'edit':
                $breadcrumb = [
                    ucfirst($this->view_title).' List' => route($this->route_name.'.master'),
                    'Edit '.ucfirst($this->view_title) => '#',
                ];
                break;
            case 'view':
                $breadcrumb = [
                    ucfirst($this->view_title).' List' => route($this->route_name.'.master'),
                    'View '.ucfirst($this->view_title) => '#',
                ];
                break;

            default:
                $arr_1 = [];
                try {
                    $arr_1 = [
                        ucfirst($this->view_title).' List' => route($this->route_name.'.master'),
                    ];
                } catch (\Exception $e) {

                }
                $breadcrumb = array_merge($arr_1, $arr);
                break;
        }

        return $breadcrumb;
    }

    public function link($link,$title='',$target="_blank"){

        if(empty($title)){
            $title = $link;
        }
        return '<a href="'.$link.'" target="'.$target.'" >'.$title.'</a>';
    }

    public function validate_data($params, $passed_data)
    {
        $data = [];

        $passed_data = array_merge($passed_data,[
            "meta_title" => 'nullable',
            "meta_description" => 'nullable',
            "meta_image_id" => 'nullable',
            "canonical_link" => 'nullable',
            "status" => 'nullable',
        ]);

        $fields = array_keys($passed_data);
        foreach ($fields as $field) {
            if (array_key_exists($field, $params)) {
                $data[$field] = $params[$field];
            }
        }

        $validator = Validator::make($params, $passed_data);

        if($validator->fails()){
            return response()->json(['status'=>500,'message'=>\Arr::flatten($validator->errors()->toArray())[0]]);
        }else{
            return $data;
        }
    }
}
