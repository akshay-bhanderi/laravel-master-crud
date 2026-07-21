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
                    <x-text name="inquiry_name"/>
                </div>

                <div class="col-md-6">
                    <x-text name="inquiry_phone_no"/>
                </div>

                <div class="col-md-6">
                    <x-email name="inquiry_email"/>
                </div>

                <div class="col-md-6">
                    <x-date name="inquiry_date"/>
                </div>

                <div class="col-md-6">
                    <x-textarea rows="5" name="inquiry_details" />
                </div>

                <div class="col-md-6">
                    <x-status-select name="status" class="mb-2" />
                    <x-text name="form_link" label="Form Submited From This URL" value="{{ isset($data) ? (is_array($data) ? (isset($data['other_data']) ? json_decode($data['other_data'])->form_link : '') : ($data->other_data ? json_decode($data->other_data)->form_link : '')) : '' }}" />
                </div>
            </div>

            <x-hr />

            <div>                
                <div class="col-md-12">
                    <x-textarea rows="5" name="inquiry_admin_remarks" />
                </div>
            </div>

            <x-save-btn/>

        </div>
    </div>
</form>

@if(isset($data))
    <x-slug-js-code />
@endif
<x-save-js-code form-id="{{ isset($data) ? 'edit_form' : 'add_form' }}" />
@endsection 