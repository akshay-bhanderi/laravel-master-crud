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
                    <x-text attr="title" name="review_title" label="Client/Company Name" />
                </div>

                <div class="col-md-6">
                    <x-text attr="title" name="review_subtitle" label="Sub-Title" />
                </div>  

                <div class="col-md-6">
                    <label for="withdrawinput1">Review Rating: </label> 
                    <div class="rating">
                        <input type="radio" name="review_rating" id="rating-5" value="5" {{ (isset($data['review_rating']) && $data['review_rating'] == 5) ? 'checked' : '' }}>
                        <label for="rating-5"></label>
                        <input type="radio" name="review_rating" id="rating-4" value="4" {{ (isset($data['review_rating']) && $data['review_rating'] == 4) ? 'checked' : '' }}>
                        <label for="rating-4"></label>
                        <input type="radio" name="review_rating" id="rating-3" value="3"  {{ (isset($data['review_rating']) && $data['review_rating'] == 3) ? 'checked' : '' }}>
                        <label for="rating-3"></label>
                        <input type="radio" name="review_rating" id="rating-2" value="2"  {{ (isset($data['review_rating']) && $data['review_rating'] == 2) ? 'checked' : '' }}>
                        <label for="rating-2"></label>
                        <input type="radio" name="review_rating" id="rating-1" value="1"  {{ (isset($data['review_rating']) && $data['review_rating'] == 1) ? 'checked' : '' }}>
                        <label for="rating-1"></label>    
                    </div> 
                </div> 

                <div class="col-md-6">
                    <x-drag-drop-upload name="review_image_id" label="Client/Company Image" label="Client/Company Image :" />
                </div>  

                <div class="col-md-12"></div>

                <div class="col-md-12">
                    <x-textarea name="review_description" rows="3" />
                </div>  

                <div class="col-md-6">
                    <x-status-select name="status" />
                </div>
                
                <div class="col-md-6">
                    <x-range name="review_priority" />
                </div>  
               
            </div>
 
            <x-save-btn/>
        </div>
    </div>
</form>

<x-slug-js-code />
<x-save-js-code form-id="{{ isset($data) ? 'edit_form' : 'add_form' }}" />

@endsection 