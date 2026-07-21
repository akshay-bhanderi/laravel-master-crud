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
                    <x-text name="certificate_title" label="Client/Company Name" />
                </div>

                <div class="col-md-6">
                    <x-textarea name="certificate_link" />
                </div>

                <div class="col-md-6">
                    <x-drag-drop-upload name="certificate_image_id" />
                </div>

                <div class="col-md-12"></div>
                
                <div class="col-md-4">
                    <x-range name="certificate_priority" />
                </div>

                <div class="col-md-2">
                    <x-status-select name="status" />
                </div>

            </div>

            <x-save-btn/>

        </div>
    </div>
</form>

<x-save-js-code form-id="{{ isset($data) ? 'edit_form' : 'add_form' }}" />
@endsection