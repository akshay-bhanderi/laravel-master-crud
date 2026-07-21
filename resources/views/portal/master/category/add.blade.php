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
                    <x-text attr="title" name="category_title" />
                    <x-text attr="slug" name="category_slug" />
                </div> 

                <div class="col-md-6">
                    <x-textarea name="category_description" rows="6" />
                </div>

                <div class="col-md-6">
                    <x-drag-drop-upload name="category_image_id" />
                </div>

                <div class="col-md-4">
                    <x-status-select name="status" />
                </div>

                <div class="col-md-4">
                    <x-range name="category_priority" />
                </div>
            </div>

            <x-hr />
            <x-meta-data />
            <x-save-btn/>
        </div>
    </div>
</form>

<x-slug-js-code />
<x-save-js-code form-id="{{ isset($data) ? 'edit_form' : 'add_form' }}" />

@endsection 