@extends('portal.template.app')
@section('content')
<x-summernote />

<x-form id="edit_form" />
    <x-mode-edit />
    <div class="card">
        <div class="card-body">

            <div class="row g-3">

                <x-page-title />

                <div class="col-md-6">
                    <x-text attr="title" name="disease_title" />
                </div>

                <div class="col-md-6">
                    <x-text attr="slug" name="disease_slug" />
                </div>  

                <div class="col-md-6">
                    <style>
                        .tag_div .ts-wrapper{
                            width: 85%!important;
                        }
                    </style>
                    <div class="tag_div" style="position: relative;">  
                        <label>Select Tags</label>
                        <select id="select-tag" placeholder="Search Tag..." name="disease_tags[]" multiple>
                            @if(!empty($tags))
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->tag_id }}" selected data-data='@json(["tag_id" => $tag->tag_id, "tag_title" => $tag->tag_title])'>
                                        {{ $tag->tag_title }}
                                    </option>
                                @endforeach
                            @endif
                        </select> 
                        <button type="button" class="btn btn-sm btn-primary" style="position: absolute; right: 5px; top: 0px;" onclick="add_new_tag()">
                            +
                        </button>
                        <script> 
                            function add_new_tag(){
                                $('#tag_details').toggle('show');
                                $('.tag_div').toggle('hide'); 
                            }
                        </script>
                    </div>
                    <div id="tag_details" style="display: none;position: relative;">  
                        <button type="button" class="btn btn-sm btn-primary" style="position: absolute; right: 5px; top: 0px;" onclick="add_new_tag()">
                            +
                        </button>
                        <div style="width: 90%;">
                            <x-text name="tag" label="Tag Title" /> 
                        </div>
                    </div>

                </div>

                <div class="col-md-6">
                    <x-textarea label="Short Info" name="disease_detail" /> 
                </div>  

                <div class="col-md-6">
                    <x-drag-drop-upload name="disease_image_id" label="disease image :" />
                </div>

                <div class="col-md-3">
                    <x-date  name="disease_date" />
                </div>

                <div class="col-md-3">
                    <x-text  name="disease_author" />
                </div>

                <div class="col-md-3">
                    <x-range name="disease_priority" />
                </div>

                <div class="col-md-3 d-none">
                    <x-status-select name="status" />
                </div>

                <div class="col-md-12">
                    <x-textarea label="Details" name="disease_content" class="summernote" rows="5"  />
                </div>

            </div>

            <x-hr />

            <x-meta-data />

            <x-save-btn/>

        </div>
    </div>
</form> 

<x-slug-js-code />
<x-save-js-code form-id="edit_form"/> 
 
<x-tag-tomselect-js />
@endsection