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

                <div class="col-md-12">
                    <x-text name="faq_title" />
                </div>

                <div class="col-md-12">
                    <x-textarea name="faq_details" class=""/>
                </div>

                <div class="col-md-6">
                    <x-status-select name="status" />
                </div>
                <div class="col-md-6">
                    <x-range name="faq_priority" />
                </div>

            </div>

            <x-save-btn/>

        </div>
    </div>
</form>

<x-save-js-code form-id="{{ isset($data) ? 'edit_form' : 'add_form' }}" />
@endsection