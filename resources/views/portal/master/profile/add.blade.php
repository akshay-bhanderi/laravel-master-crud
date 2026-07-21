@extends('portal.template.app')
@section('content')
<x-summernote /> 

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
                    <x-drag-drop-upload name="profile_image_id" label="Profile Image:" />
                </div>

                <div class="col-12"></div>
                <div class="col-md-6">
                    <x-textarea name="profile_name" rows="2"/>
                </div>

                <div class="col-md-6">
                    <x-textarea name="profile_subtitle" rows="2"/>
                </div>
                <div class="col-md-6">
                    <x-textarea name="profile_opd_morning_time" rows="2"/>
                </div>
                <div class="col-md-6">
                    <x-textarea name="profile_opd_evening_time" rows="2"/>
                </div>
                <div class="col-md-6">
                    <x-textarea name="profile_contact" rows="2"/>
                </div>
                <div class="col-md-6">
                    <x-textarea name="profile_email" rows="2"/>
                </div>

                 <div class="col-md-6">
                    <x-textarea name="profile_experience" rows="2"/>
                </div>
                <div class="col-md-12">
                    <x-textarea label="Short Info" name="profile_details" rows="3" class=""  /> 
                </div>
                     

                <div class="col-md-2">
                    <x-status-select name="status" />
                </div>
 
            </div>
 
            <x-save-btn/>
        </div>
    </div>
</form>

<x-slug-js-code />
<x-save-js-code form-id="{{ isset($data) ? 'edit_form' : 'add_form' }}" />

@endsection 