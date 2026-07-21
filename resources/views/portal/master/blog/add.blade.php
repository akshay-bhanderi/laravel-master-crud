@extends('portal.template.app')
@section('content') 
<x-summernote />
<x-rating_css />

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
                    <x-text attr="title" name="blog_title" />
                </div>

                <div class="col-md-6">
                    <x-text attr="slug" name="blog_slug" />
                </div> 

                <!-- <div class="col-md-6">
                    <x-select name="blog_category" id="blog_category" class="select2"/>
                </div>  -->

                <div class="col-md-6">
                    <x-drag-drop-upload name="blog_image_id" />
                </div>

                <div class="col-md-6">
                    <x-textarea label="Short Info" name="blog_summary" rows="3" class="mb-3" /> 
                </div>  

                <div class="col-md-4">
                    <x-text  name="blog_tag" />
                </div>

                <div class="col-md-4">
                    <x-date  name="blog_date" />
                </div>

                <div class="col-md-4">
                    <x-text  name="blog_author" />
                </div>

                <div class="col-md-4">
                    <x-status-select name="status" />
                </div>

                <div class="col-md-12">
                    <x-textarea label="Details" name="blog_content" rows="8" class="summernote"  />
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

<script type="text/javascript">
    jQuery(document).ready(function($) {
        html_blog_category_list('#blog_category');  
    });
</script>
@endsection