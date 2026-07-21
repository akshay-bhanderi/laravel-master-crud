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
                    <x-drag-drop-upload name="banner_image_id" label="Banner Image:" />
                </div>

                <div class="col-12"></div>
                <div class="col-md-6">
                    <x-textarea name="banner_title" rows="2"/>
                </div>

                <div class="col-md-6">
                    <x-textarea name="banner_subtitle" rows="2"/>
                </div>

                <div class="col-md-6">
                    <x-text name="banner_link" />
                </div>

                <div class="col-md-6">
                    <x-textarea name="banner_link_title" rows="2"/>
                </div>

                <div class="col-md-4">
                    <x-range name="banner_priority" />
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