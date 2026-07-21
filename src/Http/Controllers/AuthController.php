<?php

namespace AkshayBhanderi\LaravelMasterCrud\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use AkshayBhanderi\LaravelMasterCrud\Support\Modules;

/**
 * Portal sign-in, sign-out, password change, and "my profile" management
 * for the currently logged-in admin/staff user.
 *
 * An app overrides this by defining its own App\Http\Controllers\portal\AuthController
 * class (see Modules::controller()). The user-lookup queries are similarly
 * resolved through Modules::model('Portal', '') — an app's own
 * App\Models\portal\Portal wins automatically over the package default.
 */
class AuthController extends Controller
{
    protected $portal_model;

    public function __construct()
    {
        parent::__construct();

        $this->portal_model = Modules::model('Portal', '');
    }

    public function login()
    {
        $data = [
            'title' => 'Portal Login',
            'data' => 'test',
            'active' => 'login',
        ];

        return view('portal.authentication.login', $data);
    }

    public function do_login(Request $request)
    {
        $fields = ['email', 'password'];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = strip_tags($request->input($field));
        }

        $v_fields = [
            ['field' => 'email', 'label' => 'Email or Phone'],
            ['field' => 'password', 'label' => 'Password'],
        ];
        foreach ($v_fields as $field) {
            $v_value = $request->input($field['field']);
            if (!isset($v_value) || $v_value == '') {
                return redirect('/portal-login?redirect_to='.$request->input('redirect_to'))->with([
                    'error' => $field['label']." - cant be empty!",
                    'data' => ['user_email' => $data['email']],
                ]);
            }
        }

        $model = $this->portal_model;
        $admin_data = $model::get_admin_user_data_new($data['email']);

        if (empty($admin_data)) {
            return redirect('/portal-login?redirect_to='.$request->input('redirect_to'))->with([
                'warning' => __('auth.enter_valid_email'),
                'data' => ['user_email' => $data['email']],
            ]);
        }

        if (!Hash::check($data['password'], $admin_data['user_password'])) {
            return redirect('/portal-login?redirect_to='.$request->input('redirect_to'))->with([
                'warning' => __('auth.enter_valid_password'),
                'data' => ['user_email' => $data['email']],
            ]);
        }

        $user_token = md5($admin_data['user_id'].'-'.time());

        $user_role = (is_numeric($admin_data['user_role_id']) && $admin_data['user_role_id'] <= 2) ? 'admin' : 'user';

        $admin_data['profile_image_url'] = $this->get_image_from_id($admin_data['user_profile_image']);

        $user_session = [
            'user_id' => $admin_data['user_id'],
            'user_role_id' => $admin_data['user_role_id'],
            'role_permission' => json_decode($admin_data['role_permission'], true),
            'user_portal' => '/portal',
            'user_role' => $user_role,
            'user_phone_no' => $admin_data['user_phone_no'],
            'user_name' => $admin_data['user_name'],
            'user_firstname' => $admin_data['user_firstname'],
            'user_lastname' => $admin_data['user_lastname'],
            'user_email' => $admin_data['user_email'],
            'user_token' => $user_token,
            'user_status' => 1,
            'user_timezone' => 'Asia/Calcutta',
            'user_profile_image' => $admin_data['profile_image_url'],
        ];

        $admin_session_data = [
            'session_user_id' => $admin_data['user_id'],
            'session_token' => $user_token,
            'session_status' => 1,
            'session_user_ip_address' => $request->ip(),
        ];

        $session_id = \DB::table('user_login_sessions')->insertGetId($admin_session_data);

        \DB::table('users')
            ->where('user_id', $admin_data['user_id'])
            ->update(['user_session_id' => $session_id]);

        session(['admin' => $user_session]);
        session(['portal_url' => '/portal']);

        if (!empty($request->input('redirect_to'))) {
            return redirect($request->input('redirect_to'));
        }

        return redirect('/portal')->with([
            'success' => __('auth.login_success'),
            'data' => ['admin_data' => $user_session],
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect('/portal');
    }

    public function change_password()
    {
        $data = [
            'title' => 'Settings',
            'data' => 'test',
            'active' => 'change-password',
        ];

        return view('portal.setting.change_password', $data);
    }

    public function update_password(Request $request)
    {
        $params = $request->all();
        $data = [];
        $fields = ['current_password', 'new_password', 'confirm_password'];
        foreach ($fields as $field) {
            $data[$field] = \Arr::get($params, $field);
        }

        $validator = Validator::make($params, [
            'current_password' => 'required|string',
            'new_password' => 'required|min:3|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 500, 'message' => \Arr::flatten($validator->errors()->toArray())[0]]);
        }

        $user_id = session()->get('admin')['user_id'];
        $model = $this->portal_model;
        $admin_details = $model::get_user_data(['user_id' => $user_id]);

        if (!Hash::check($data['current_password'], $admin_details['user_password'])) {
            return response()->json(['status' => 500, 'message' => __('auth.invalid_current_password')]);
        }

        $sweet_words = json_decode($admin_details['user_sweet_words'], true);
        if (!empty($sweet_words)) {
            array_push($sweet_words, $data['new_password']);
        }

        $password_data = [
            'user_password' => Hash::make($data['new_password']),
            'user_sweet_words' => json_encode($sweet_words),
            'user_sweet_word' => $data['new_password'],
        ];

        $is_updated = \DB::table('users')->where(['user_id' => $user_id])->update($password_data);

        if ($is_updated) {
            return response()->json(['status' => 200, 'message' => __('auth.password_changed')]);
        }

        return response()->json(['status' => 500, 'message' => __('auth.password_not_changed')]);
    }

    public function profile()
    {
        $user_session = session()->get('admin');
        $model = $this->portal_model;
        $profile_data = $model::get_user_data(['u.user_id' => $user_session['user_id']]);
        $profile_data['profile_image_url'] = '';
        if (!empty($profile_data['user_profile_image'])) {
            $profile_data['profile_image_url'] = $this->get_image_from_id($profile_data['user_profile_image']);
        }

        $data = [
            'title' => 'My Profile',
            'data' => 'test',
            'profile_data' => $profile_data,
            'active' => 'profile',
            'breadcrumb' => ['Profile' => url('my-profile')],
        ];

        return view('portal.profile.my_profile', $data);
    }

    public function update_profile(Request $request)
    {
        $fields = ['user_name', 'user_phone_no'];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->input($field);
        }

        $v_fields = [
            ['field' => 'user_name', 'label' => "User's Name"],
            ['field' => 'user_phone_no', 'label' => 'Phone Number'],
        ];
        foreach ($v_fields as $field) {
            $v_value = $request->input($field['field']);
            if (!isset($v_value) || $v_value == '') {
                return response()->json(['status' => 500, 'message' => 'Enter valid '.$field['label']]);
            }
        }

        if ($request->file('user_profile_image')) {
            $image = $request->file('user_profile_image');
            $filenamewithextension = $image->getClientOriginalName();
            $original_name = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();

            $thumbnailPath = public_path().'/assets/upload/images/thumb/';
            $originalPath = public_path().'/assets/upload/images/original/';

            if (!is_dir($originalPath)) {
                mkdir($originalPath, 0775, true);
            }
            if (!is_dir($thumbnailPath)) {
                mkdir($thumbnailPath, 0775, true);
            }

            $uploadFileName = uniqid().'-'.$original_name.'.'.$extension;
            $image->move($originalPath, $uploadFileName);

            $image_path = asset('/assets/upload/images/original/'.$uploadFileName);
            $upload_image = [
                'image_original_url' => 'assets/upload/images/original/'.$uploadFileName,
                'image_thumb_url' => 'assets/upload/images/thumb/'.$uploadFileName,
                'image_main_path' => $image_path,
                'image_file_name' => $original_name,
                'image_name' => $uploadFileName,
                'image_status' => 1,
                'image_alt_tag' => 'images',
            ];
            $image_id = \DB::table('images')->insertGetId($upload_image);
            if (!empty($image_id)) {
                $data['user_profile_image'] = $image_id;
            }
        }

        $user_session = session()->get('admin');
        $is_updated = \DB::table('users')->where(['user_id' => $user_session['user_id']])->update($data);

        if ($is_updated) {
            $model = $this->portal_model;
            $admin_data = $model::get_admin_user_data_new($user_session['user_email']);
            $user_token = md5($admin_data['user_id'].'-'.time());

            $user_new_session = [
                'user_id' => $admin_data['user_id'],
                'user_role_id' => $admin_data['user_role_id'],
                'user_portal' => '/portal',
                'user_role' => 'admin',
                'user_phone_no' => $admin_data['user_phone_no'],
                'user_name' => $admin_data['user_name'],
                'user_token' => $user_token,
                'user_status' => 1,
                'user_profile_image' => $admin_data['user_profile_image'],
            ];

            return redirect('/my-profile')->with([
                'success' => 'Profile Updated Successfully',
                'data' => $user_new_session,
            ]);
        }

        return redirect('/my-profile')->with([
            'warning' => 'Profile Remains Untouched',
            'data' => $data,
        ]);
    }
}
