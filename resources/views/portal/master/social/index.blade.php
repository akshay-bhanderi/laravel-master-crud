@extends('portal.template.app')
@section('content')

<style>
.social-row {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 10px;
    transition: box-shadow .15s;
}
.social-row.sortable-ghost {
    opacity: .4;
    background: #f0f4ff;
}
.drag-handle {
    cursor: grab;
    color: #aaa;
    font-size: 20px;
    padding: 0 4px;
    flex-shrink: 0;
    user-select: none;
    align-self: center;
}
.drag-handle:active { cursor: grabbing; }
.row-number {
    font-size: 12px;
    color: #888;
    min-width: 22px;
    flex-shrink: 0;
    align-self: center;
}
.uploader-wrap {
    width: 200px;
    flex-shrink: 0;
}
.uploader-wrap .image_selection {
    min-height: 90px !important;
    height: 90px;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px dashed #ccc;
    border-radius: 6px;
    cursor: pointer;
    overflow: hidden;
}
.uploader-wrap .image_selection .img-upload-title {
    font-size: 9px;
    margin: 0;
}
.uploader-wrap .image_selection .upload-icon {
    font-size: 14px;
}
.uploader-wrap .image_selection [image_parent] img {
    max-height: 44px;
    max-width: 76px;
    object-fit: contain;
}
.uploader-wrap .image_selection [image_parent] .btn-danger {
    font-size: 9px;
    padding: 1px 4px;
}
.social-link-input {
    border-radius: 6px;
}
.delete-social-btn {
    flex-shrink: 0;
    width: 30px;
    height: 30px;
    padding: 0;
    font-size: 16px;
    line-height: 1;
    border-radius: 50%;
    align-self: center;
}
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0">Social Media Management</h6>
                <button class="btn btn-primary btn-sm" id="save-all-btn">
                    <i class="bi bi-check2-all me-1"></i> Save All
                </button>
            </div>
            <div class="card-body">

                <div id="social-list">
                    @foreach($socials as $social)
                    <div class="social-row d-flex align-items- center gap-2" data-id="{{ $social->social_id }}">
                        <span class="drag-handle"><i class="bi bi-grip-vertical"></i></span>
                        <span class="row-number">{{ $loop->iteration }}</span>
                        <div class="uploader-wrap">
                            <x-drag-drop-upload
                                name="social_image_id"
                                value="{{ $social->social_image_id }}"
                                nolabel="true"
                            />
                        </div>
                        <div class="flex-grow-1">
                            <x-textarea name="social_title" class="social-link-input" placeholder="https://..." value="{{ $social->social_link }}" />
                        </div>
                        <button class="btn btn-sm btn-outline-danger delete-social-btn" title="Delete">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    @endforeach
                </div>

                <div class="mt-3">
                    <button class="btn btn-outline-secondary btn-sm" id="add-row-btn">
                        <i class="bi bi-plus-lg me-1"></i> Add Social
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Hidden template row for cloning new rows (rendered once by PHP so the uploader component is correct) --}}
<div id="row-template" style="display:none;" aria-hidden="true">
    <div class="social-row d-flex align-items-center gap-2" data-id="">
        <span class="drag-handle"><i class="bi bi-grip-vertical"></i></span>
        <span class="row-number"></span>
        <div class="uploader-wrap">
            <x-drag-drop-upload name="social_image_id" nolabel="true" />
        </div>
        <div class="flex-grow-1">
            <input type="text" class="form-control form-control-sm social-link-input" placeholder="https://...">
        </div>
        <button class="btn btn-sm btn-outline-danger delete-social-btn" title="Delete">
            <i class="bi bi-x"></i>
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
var SAVE_ALL_URL = "{{ route('social.save_all') }}";
var REORDER_URL  = "{{ route('social.reorder') }}";
var DELETE_URL   = "{{ route('social.delete') }}";
var CSRF         = "{{ csrf_token() }}";

// ── Drag & Drop ──────────────────────────────────────────────────────────────
Sortable.create(document.getElementById('social-list'), {
    handle: '.drag-handle',
    animation: 150,
    ghostClass: 'sortable-ghost',
    onEnd: function () {
        reNumberRows();
        saveOrder();
    }
});

function reNumberRows() {
    $('#social-list .social-row').each(function (i) {
        $(this).find('.row-number').text(i + 1);
    });
}

function saveOrder() {
    var order = [];
    $('#social-list .social-row').each(function (i) {
        var id = $(this).data('id');
        if (id) {
            order.push({ id: id, priority: i });
        }
    });
    if (order.length === 0) return;
    $.ajax({
        url: REORDER_URL,
        method: 'POST',
        data: { _token: CSRF, order: order },
        success: function (res) {
            if (res.status === 200) {
                infoToast('Order saved');
            }
        }
    });
}

// ── Add Row ──────────────────────────────────────────────────────────────────
$('#add-row-btn').on('click', function () {
    var $template = $('#row-template .social-row');
    var $clone = $template.clone(true);

    // Reset uploader state
    $clone.find('input[data-image]').val('');
    $clone.find('[image_selection]').attr('uploaded-image', '');
    $clone.find('[image_selection_child]').html(
        '<i class="bi bi-cloud-arrow-up me-2 upload-icon"></i>' +
        '<h3 class="img-upload-title">Click here to Select/upload Image</h3>'
    );
    $clone.find('[image_parent]').remove();

    // Reset other fields
    $clone.find('.social-link-input').val('');
    $clone.attr('data-id', '');

    $('#social-list').append($clone);
    reNumberRows();
    $clone.find('.social-link-input').focus();
});

// ── Delete ───────────────────────────────────────────────────────────────────
$(document).on('click', '.delete-social-btn', function () {
    var $row = $(this).closest('.social-row');
    var id   = $row.data('id');

    if (!id) {
        $row.remove();
        reNumberRows();
        return;
    }

    Swal.fire({
        title: 'Delete?',
        text: 'Remove this social link?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete',
        confirmButtonColor: '#dc3545',
    }).then(function (result) {
        if (result.isConfirmed) {
            $.ajax({
                url: DELETE_URL,
                method: 'POST',
                data: { _token: CSRF, id: id },
                success: function (res) {
                    if (res.status == '200') {
                        $row.remove();
                        reNumberRows();
                        successToast('Deleted');
                    } else {
                        errorToast('Could not delete');
                    }
                }
            });
        }
    });
});

// ── Save All ─────────────────────────────────────────────────────────────────
$('#save-all-btn').on('click', function () {
    var $btn    = $(this);
    var socials = [];
    var hasError = false;

    $('#social-list .social-row').each(function () {
        var imageId = $(this).find('input[data-image]').val();
        if (!imageId) {
            $(this).find('.uploader-wrap .image_selection').css('border-color', '#dc3545');
            hasError = true;
            return false;
        }
        $(this).find('.uploader-wrap .image_selection').css('border-color', '');
        socials.push({
            id:               $(this).data('id') || '',
            social_image_id:  imageId,
            social_link:      $(this).find('.social-link-input').val().trim(),
        });
    });

    if (hasError) {
        warningToast('Icon is required for all rows');
        return;
    }
    if (socials.length === 0) {
        warningToast('Nothing to save');
        return;
    }

    $btn.prop('disabled', true).text('Saving...');

    $.ajax({
        url: SAVE_ALL_URL,
        method: 'POST',
        data: { _token: CSRF, socials: socials },
        success: function (res) {
            if (res.status === 200) {
                successToast(res.message || 'Saved');
                setTimeout(function () { location.reload(); }, 800);
            } else {
                errorToast('Save failed');
                $btn.prop('disabled', false).html('<i class="bi bi-check2-all me-1"></i> Save All');
            }
        },
        error: function () {
            errorToast('Server error');
            $btn.prop('disabled', false).html('<i class="bi bi-check2-all me-1"></i> Save All');
        }
    });
});
</script>

@endsection
