@extends('portal.template.app')
@section('content')

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css"> 
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<x-summernote />
<x-front.ratingCss /> 

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
                    <x-text attr="title" name="product_title"  />
                </div>

                <div class="col-md-6">
                    <x-text attr="slug" name="product_slug" />
                </div>

                <div class="col-md-6">
                    <label>Select Category</label>
                    <div style="border: 1px solid #124b9a;border-radius: 5px;">
                        <select placeholder="Search Category.." id="select-category" name="product_category" class="Category">
                            @if(isset($category))
                                <option value="{{ $category->category_id }}">{{ $category->category_title }}</option>
                            @endif
                        </select> 
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="withdrawinput1">Product Rating: </label> 
                    <div class="rating">
                        <input type="radio" name="product_rating" id="rating-5" value="5" {{ (isset($data['product_rating']) && $data['product_rating'] == 5) ? 'checked' : 'checked' }}>
                        <label for="rating-5"></label>
                        <input type="radio" name="product_rating" id="rating-4" value="4" {{ (isset($data['product_rating']) && $data['product_rating'] == 4) ? 'checked' : '' }}>
                        <label for="rating-4"></label>
                        <input type="radio" name="product_rating" id="rating-3" value="3" {{ (isset($data['product_rating']) && $data['product_rating'] == 3) ? 'checked' : '' }}>
                        <label for="rating-3"></label>
                        <input type="radio" name="product_rating" id="rating-2" value="2" {{ (isset($data['product_rating']) && $data['product_rating'] == 2) ? 'checked' : '' }}>
                        <label for="rating-2"></label>
                        <input type="radio" name="product_rating" id="rating-1" value="1" {{ (isset($data['product_rating']) && $data['product_rating'] == 1) ? 'checked' : '' }}>
                        <label for="rating-1"></label>    
                    </div> 
                </div>

                <div class="col-md-3">
                    <label>Votes</label>
                    <input type="number" name="product_rating_vote" value="{{ $data['product_rating_vote'] ?? 'rand(50, 100)' }}" class="form-control">
                </div>

                <div class="col-md-5">
                    <x-drag-drop-upload name="product_image_id" />
                </div>

                <div class="col-md-6">
                    <x-textarea label="Short Info" name="product_short_desc" rows="3" class="mb-3"/>
                </div>

                <div class="col-md-6"> 
                    <x-drag-drop-upload name="product_other_images" multiple="multiple" />
                </div>

                <div class="col-md-4">
                    <x-range name="product_priority" />
                </div>

                <div class="col-md-12">
                    <x-textarea label="Details" name="product_desc" rows="8" class="summernote" />
                </div>
            </div>

            <x-hr />
            <x-meta-data />
            <x-save-btn/>
        </div>
    </div>
</form>

<x-slug-js-code />
<x-save-js-code form-id="{{ isset($data) ? 'edit_form' : 'add_form' }}"/>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        new TomSelect('#select-category', {
            selectOnTab: true,
            valueField: 'category_id',
            labelField: 'category_title',
            searchField: 'category_title',
            highlight: false,
            load: function(query, callback) {
                var url = '{{ url('html_category_list') }}';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ search: query })
                })
                .then(response => response.json())
                .then(json => {
                    callback(json);
                })
                .catch(() => {
                    callback();
                });
            }
        });
    });
</script>
@endsection 