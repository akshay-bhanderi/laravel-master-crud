@extends('portal.template.app')
@section('content')

<x-form id="{{ isset($data) ? 'edit_form' : 'add_form' }}" />
    @if(isset($data))
        <x-mode-edit />
    @else
        <x-mode-add />
    @endif
    <div class="card">
        <div class="card-body">

            <div class="row g-3">

                <x-page-title />

                <div class="col-md-6">
                    <x-text label="name" name="user_name" />
                </div>

                <div class="col-md-3">
                    <x-select label="gender" name="user_gender" :options="$gender_type" />
                </div>

                <div class="col-md-3">
                    <x-select label="role" name="user_role_id" :options="$role_list" />
                </div>

                <div class="col-md-4">
                    <x-number label="phone no (For login)" name="user_phone_no" />
                </div>
                <div class="col-md-4">
                    <x-email label="Email (For login)" name="user_email" />
                </div>
                <div class="col-md-4">
                    <x-text label="password" name="user_password" />
                </div>

                <div class="col-md-6">
                    <x-textarea label="Address" name="user_address" rows="6"/>
                </div>


                <div class="col-md-6">
                    <x-drag-drop-upload label="profile pic" name="user_profile_image" />
                </div>

                <div class="col-md-4">
                    <x-status-select name="status" />
                </div>

            </div>

            <x-save-btn/>

        </div>
    </div>
</form>

<x-save-js-code form-id="{{ isset($data) ? 'edit_form' : 'add_form' }}" />
@endsection