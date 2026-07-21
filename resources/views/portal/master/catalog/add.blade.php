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

                <div class="col-md-12">
                    <x-text attr="title" name="catalog_title"  />
                </div>

                <div class="col-md-6 d-none">
                    <x-text attr="slug" name="catalog_slug" />
                </div>

                <div class="col-md-5">
                    <x-drag-drop-upload name="catalog_file_id" label="Catalog File" type="file" />
                </div>

                 <div class="col-md-5">
                    <x-drag-drop-upload name="catalog_image_id" />
                </div>

                <div class="col-md-6">
                    <x-text name="catalog_link" />
                </div>

                <div class="col-md-2">
                    <x-range name="catalog_priority" />
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