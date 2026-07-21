<?php

namespace AkshayBhanderi\LaravelMasterCrud\Concerns;

trait HasMasterCrudActions
{
    public function index()
    {
        return view('portal.master.master', ['extra_pages' => []]);
    }

    public function add()
    {
        return view($this->view_path.'add', []);
    }

    public function edit($passed_id)
    {
        $model = $this->model;
        $data = $model::edit($passed_id);
        \View::share('data', $data);
        return view($this->view_path.'add', $data);
    }

    public function view($passed_id)
    {
        $model = $this->model;
        $data = $model::edit($passed_id);
        return view($this->view_path.($this->view_template ?? 'edit'), $data);
    }

    public function duplicate($passed_id)
    {
        $model = $this->model;
        $data = $model::edit($passed_id);
        unset($data[$this->primary_key]);
        unset($data['id']);
        $new_id = $model::insert_data($data);
        return redirect($this->route_name.'-edit/'.$new_id);
    }

    public function delete()
    {
        $model = $this->model;
        $model::update_data(request()->get('id'), ['is_delete' => 1]);
        return $this->success_json('delete');
    }

    public function status()
    {
        $model = $this->model;
        $model::update_data(request()->get('id'), ['status' => request()->get('status')]);
        return $this->success_json('status');
    }

    public function save()
    {
        $model = $this->model;
        $params = request()->all();
        $id = $params['id'] ?? '';
        $mode = $params['mode'] ?? '';

        $data = $this->validate_data($params, $this->validation_rules);
        if (!is_array($data)) {
            return $data;
        }

        $data = $this->before_save($data, $params, $mode);

        if ($mode == 'add') {
            $model::insert_data($data);
            return $this->save_json();
        }

        $model::update_data($id, $data);
        return $this->update_json();
    }

    protected function before_save(array $data, array $params, string $mode): array
    {
        return $data;
    }

    public function dt_list()
    {
        $model = $this->model;
        $pass_data = $this->js_to_php($this->dt_filter());
        $all_data = $model::dt_list_data($pass_data);

        $data = [];
        foreach ($all_data['result'] as $row) {
            $data[] = $this->dt_row($row);
        }

        return $this->dt_response($all_data, $data);
    }

    protected function dt_filter(): string
    {
        return '';
    }
}
