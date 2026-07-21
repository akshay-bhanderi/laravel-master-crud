<div class="row g-2">

    <div class="col-md-12"><h5>Meta Data (SEO Settings)</h5></div>

    <div class="col-md-6">
        <x-textarea name="meta_title" value="{{ $data['meta_title'] ?? $meta_title ?? '' }}" rows="2" class="form-control"/>
    </div>
    <div class="col-md-6">
        <x-textarea name="meta_description" value="{{ $data['meta_description'] ?? $meta_description ?? '' }}" rows="2" class="form-control"/>
    </div>
    <div class="col-md-6">
        <label for="withdrawinput1">Canonical Link: (Leave Blank if not needed!)</label>
        <x-textarea nolabel="true" name="canonical_link" value="{{ $data['canonical_link'] ?? $canonical_link ?? '' }}" rows="2" class="form-control"/>
        <small>Add a special purpose link for SEO. <b>If not required, leave the field blank.</b></small>
    </div>
    <div class="col-md-6">
        <x-drag-drop-upload name="meta_image_id" value="{{ $data['meta_image_id'] ?? $meta_image_id ?? '' }}" />
    </div>

</div>