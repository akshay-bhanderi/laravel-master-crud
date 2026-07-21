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

                <div class="col-md-5">
                    <x-text attr="title" name="page_title" />
                </div>

                <div class="col-md-5">
                    <x-text attr="slug" name="page_slug" />
                </div>

                <div class="col-md-2">
                    <x-status-select name="status" />
                </div>

                <div class="col-md-12">
                    <x-textarea name="page_details" rows="5" class="RichTextEditor"/>
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