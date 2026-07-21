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
                    <x-text attr="title" name="project_title"  />
                </div>
                <div class="col-md-6">
                    <x-text attr="slug" name="project_slug" />
                </div>

                <div class="col-md-6">
                    <x-textarea label="Short Info" name="project_short_desc" rows="3" class="mb-3"/>
                </div>

                <div class="col-md-6">
                    <x-drag-drop-upload name="project_image_id" />
                </div>

                <div class="col-md-6"> 
                    <x-drag-drop-upload name="project_other_images" multiple="multiple" />
                </div>

                <div class="col-md-6">
                    <x-range name="project_priority" />
                </div>

                 <div class="col-md-12">
                    <x-textarea label="Details" name="project_desc" rows="8" class="summernote" />
                </div>

            </div>

            <x-hr />
            <x-save-btn/>
        </div>
    </div>
</form>

<x-slug-js-code />
<x-save-js-code form-id="{{ isset($data) ? 'edit_form' : 'add_form' }}"/>

@endsection 