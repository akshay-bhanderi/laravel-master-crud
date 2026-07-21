<?php

namespace AkshayBhanderi\LaravelMasterCrud\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use AkshayBhanderi\LaravelMasterCrud\Concerns\HasMasterCrudHelpers;

/**
 * Base controller for package-provided ("fixed") modules like User/Role.
 * Self-contained — does not depend on the consuming app's own base
 * Controller, so it works in any app that installs this package.
 */
class Controller extends BaseController
{
    use HasMasterCrudHelpers;

    protected $status_arr;

    public function __construct()
    {
        $this->status_arr = ['1' => 'Active', '0' => 'InActive'];
        \View::share('status_arr', $this->status_arr);
    }

    public function save_json($params = '')
    {
        $message = 'Data Saved Successfully';
        if (! empty($params)) {
            $message = $params;
        }

        return response()->json(['status' => 200, 'message' => $message]);
    }

    public function update_json($params = '')
    {
        $message = 'Data Updated Successfully';
        if (! empty($params)) {
            $message = $params;
        }

        return response()->json(['status' => 200, 'message' => $message]);
    }

    public function error_json($params = '')
    {
        $message = 'Oops! Try Later!';
        if (! empty($params)) {
            $message = $params;
        }

        return response()->json(['status' => 500, 'message' => $message]);
    }

    public function success_json($type = '', $params = '')
    {
        if (empty($type) || ($type != 'status' && $type != 'delete')) {
            $message = 'Success!';
        } elseif ($type == 'status') {
            $message = 'Status Changed Successfully';
        } elseif ($type == 'delete') {
            $message = 'Data Deleted Successfully';
        }

        if (! empty($params)) {
            $message = $params;
        }

        return response()->json(['status' => 200, 'message' => $message]);
    }
}
