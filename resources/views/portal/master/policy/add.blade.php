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

                <div class="col-md-5">
                    <x-text attr="title" name="policy_title"/>
                </div>

                <div class="col-md-5">
                    <x-text attr="slug" name="policy_slug"/>
                </div>

                <div class="col-md-2">
                    <x-status-select name="status" />
                </div>

                <div class="col-md-12">
                    <x-textarea name="policy_details" class="summernote" rows="5" />
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