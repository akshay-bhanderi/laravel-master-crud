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
                
                @if(isset($data))
                <div class="col-md-12"><h5>Setting - {{ ucfirst($setting_title) }}</h5></div>
                    <!-- Hidden inputs -->
                    <input type="hidden" name="setting_title" value="{{ $setting_title }}">
                    <input type="hidden" name="setting_type" value="{{ $setting_type }}">
                    <input type="hidden" name="setting_label" value="{{ $setting_label }}">
                    <input type="hidden" name="setting_description" value="{{ $setting_description }}">
                    <input type="hidden" name="setting_default" value="{{ $setting_default }}">

                    <!-- Input based on type -->
                    <div class="col-md-6">
                        <label>{{ $setting_label }}</label>

                        @if($setting_type == 'image')
                            <x-drag-drop-upload name="setting_value" value="{{ $setting_value }}" />
                        @elseif($setting_type == 'text')
                            <x-textarea name="setting_value" value="{{ $setting_value }}" class="form-control" />
                        @elseif($setting_type == 'product')
                            <x-select :options="$product" name="setting_value[]" selected="{{ $setting_value }}" multiple="multiple" class="select2" />
                        @elseif($setting_type == 'category')
                            <x-select :options="$category" name="setting_value[]" selected="{{ $setting_value }}" multiple="multiple" class="select2" />
                        @elseif($setting_type == 'treatment')
                            <x-select :options="$treatment" name="setting_value[]" selected="{{ $setting_value }}" multiple="multiple" class="select2" />
                        @endif
                    </div>

                @else
                    <!-- Add Mode Fields -->
                    <div class="col-md-6">
                        <label>Setting Title:</label>
                        <input type="text" name="setting_title" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Setting Value:</label>
                        <input type="text" name="setting_value" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Setting Type:</label>
                        <x-select name="setting_type" :options="$options" />
                    </div>

                    <div class="col-md-6">
                        <label>Setting Label:</label>
                        <input type="text" name="setting_label" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Setting Description:</label>
                        <input type="text" name="setting_description" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Setting Default:</label>
                        <input type="text" name="setting_default" class="form-control">
                    </div>
                @endif

            </div>

            <x-save-btn />
        </div>
    </div>
</form>

<x-save-js-code form-id="{{ isset($data) ? 'edit_form' : 'add_form' }}" />
  
@endsection