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

                <div class="col-md-4">
                    <x-drag-drop-upload name="social_image_id" label="Social Media Image (32x32 PX) :" />
                </div>

                <div class="col-12"></div>
                <div class="col-md-6">
                    <x-textarea name="social_link" />
                </div>

                <div class="col-md-6">
                    <x-range name="social_priority" />
                </div>

                <div class="col-md-6">
                    <x-status-select name="status" />
                </div>

            </div>

            <x-save-btn/>

        </div>
    </div>
</form>

<x-save-js-code form-id="{{ isset($data) ? 'edit_form' : 'add_form' }}" script="window.open('{{route('social.master')}}','_self')" />

@endsection