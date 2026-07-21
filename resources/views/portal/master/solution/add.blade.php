@extends('portal.template.app')
@section('content')
<x-summernote />

<x-form id="add_form" />
    <x-mode-add />
    <div class="card">
        <div class="card-body">

            <div class="row g-3">

                <x-page-title />

                <div class="col-md-6">
                    <x-text attr="title" name="solution_title" />
                </div>

                <div class="col-md-6">
                    <x-text attr="slug" name="solution_slug" />
                </div> 

                <div class="col-md-6">
                    <style>
                        .tag_div .ts-wrapper{
                            width: 85%!important;
                        }
                    </style>
                    <div class="tag_div" style="position: relative;">  
                        <label>Select Tags</label>
                        <select id="select-tag" placeholder="Search Tag..." name="solution_tags[]" multiple></select> 
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
                    <x-textarea label="Short Info" name="solution_detail" /> 
                </div>  

                <div class="col-md-6">
                    <label>Select Diseases</label>
                    <select id="select-disease" name="solution_diseases[]" multiple placeholder="Search Disease..."></select>
                </div>

                <div class="col-md-6">
                    <x-drag-drop-upload name="solution_image_id" label="Solution image :" />
                </div>

                <div class="col-md-3">
                    <x-date  name="solution_date" />
                </div>

                <div class="col-md-3">
                    <x-text  name="solution_author" />
                </div>

                <div class="col-md-4 d-none">
                    <x-status-select name="status" />
                </div>

                <div class="col-md-12">
                    <x-textarea label="Details" name="solution_content" class="summernote" rows="5"  />
                </div>

            </div>

            <x-hr />

            <x-meta-data />

            <x-save-btn/>

        </div>
    </div>
</form> 

<x-slug-js-code />
<x-save-js-code form-id="add_form" />

<x-tag-tomselect-js />

<script type="text/javascript">
new TomSelect('#select-disease', {
    valueField: 'disease_id',
    labelField: 'disease_title',
    searchField: 'disease_title',
    highlight: false,
    plugins: ['remove_button'],
    mode: 'multi',
    maxItems: null,
    options: @json($all_diseases ?? []),
    onItemAdd: function() {
        this.setTextboxValue('');
        this.refreshOptions(false);
    }
});
</script>
@endsection