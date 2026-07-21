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
                    <x-text name="client_name" label="Client Name" />
                </div>

                <div class="col-md-6">
                    <x-drag-drop-upload name="client_image_id" label="Client Image (100x100 PX) :" />
                </div> 

                <div class="col-md-12"></div>
                
                <div class="col-md-4">
                    <x-range name="client_priority" />
                </div>


                <div class="col-md-4">
                    <x-status-select name="status" />
                </div>

                <div class="col-md-12"></div>

                <div class="col-md-12">
                    <x-textarea name="other_data" />
                </div>

            </div>  

            <x-save-btn/>

        </div>
    </div>
</form> 
 

<x-save-js-code form-id="{{ isset($data) ? 'edit_form' : 'add_form' }}" />
 
@endsection
