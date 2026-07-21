@extends('portal.template.app')
@section('content')

<style>
.faq-row {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 12px 14px;
    margin-bottom: 10px;
    transition: box-shadow .15s;
}
.faq-row.sortable-ghost {
    opacity: .4;
    background: #f0f4ff;
}
.drag-handle {
    cursor: grab;
    color: #aaa;
    font-size: 20px;
    line-height: 1;
    padding: 4px 6px 0 0;
    flex-shrink: 0;
    user-select: none;
}
.drag-handle:active { cursor: grabbing; }
.row-number {
    font-size: 12px;
    color: #888;
    min-width: 22px;
    padding-top: 10px;
    flex-shrink: 0;
}
.faq-title-input {
    font-weight: 500;
    border-radius: 6px;
}
.faq-details-input {
    resize: vertical;
    min-height: 60px;
    border-radius: 6px;
    font-size: 13px;
}
.delete-faq-btn {
    flex-shrink: 0;
    width: 30px;
    height: 30px;
    padding: 0;
    font-size: 16px;
    line-height: 1;
    border-radius: 50%;
    margin-top: 6px;
}
#save-all-btn {
    min-width: 120px;
}
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0">FAQ Management</h6>
                <button class="btn btn-primary btn-sm" id="save-all-btn">
                    <i class="bi bi-check2-all me-1"></i> Save All
                </button>
            </div>
            <div class="card-body">

                <div id="faq-list">
                    @foreach($faqs as $faq)
                    <div class="faq-row d-flex align-items-start gap-2" data-id="{{ $faq->faq_id }}">
                        <span class="drag-handle"><i class="bi bi-grip-vertical"></i></span>
                        <span class="row-number">{{ $loop->iteration }}</span>
                        <div class="flex-grow-1">
                            <input
                                type="text"
                                class="form-control form-control-sm faq-title-input mb-2"
                                placeholder="Question"
                                value="{{ $faq->faq_title }}"
                            >
                            <textarea
                                class="form-control form-control-sm faq-details-input"
                                placeholder="Answer"
                                rows="2"
                            >{{ $faq->faq_details }}</textarea>
                        </div>
                        <button class="btn btn-sm btn-outline-danger delete-faq-btn" title="Delete">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    @endforeach
                </div>

                <div class="mt-3">
                    <button class="btn btn-outline-secondary btn-sm" id="add-row-btn">
                        <i class="bi bi-plus-lg me-1"></i> Add FAQ
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- SortableJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
var SAVE_ALL_URL  = "{{ route('faq.save_all') }}";
var REORDER_URL   = "{{ route('faq.reorder') }}";
var DELETE_URL    = "{{ route('faq.delete') }}";
var CSRF          = "{{ csrf_token() }}";

// ── Drag & Drop ──────────────────────────────────────────────────────────────
var sortable = Sortable.create(document.getElementById('faq-list'), {
    handle: '.drag-handle',
    animation: 150,
    ghostClass: 'sortable-ghost',
    onEnd: function () {
        reNumberRows();
        saveOrder();
    }
});

function reNumberRows() {
    $('#faq-list .faq-row').each(function (i) {
        $(this).find('.row-number').text(i + 1);
    });
}

function saveOrder() {
    var order = [];
    $('#faq-list .faq-row').each(function (i) {
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
    var count = $('#faq-list .faq-row').length + 1;
    var row = $(
        '<div class="faq-row d-flex align-items-start gap-2" data-id="">' +
            '<span class="drag-handle"><i class="bi bi-grip-vertical"></i></span>' +
            '<span class="row-number">' + count + '</span>' +
            '<div class="flex-grow-1">' +
                '<input type="text" class="form-control form-control-sm faq-title-input mb-2" placeholder="Question">' +
                '<textarea class="form-control form-control-sm faq-details-input" placeholder="Answer" rows="2"></textarea>' +
            '</div>' +
            '<button class="btn btn-sm btn-outline-danger delete-faq-btn" title="Delete"><i class="bi bi-x"></i></button>' +
        '</div>'
    );
    $('#faq-list').append(row);
    row.find('.faq-title-input').focus();
});

// ── Delete ───────────────────────────────────────────────────────────────────
$(document).on('click', '.delete-faq-btn', function () {
    var $row = $(this).closest('.faq-row');
    var id   = $row.data('id');

    if (!id) {
        // New unsaved row — just remove from DOM
        $row.remove();
        reNumberRows();
        return;
    }

    Swal.fire({
        title: 'Delete?',
        text: 'Remove this FAQ?',
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
    var $btn  = $(this);
    var faqs  = [];
    var hasError = false;

    $('#faq-list .faq-row').each(function () {
        var title = $(this).find('.faq-title-input').val().trim();
        if (!title) {
            $(this).find('.faq-title-input').addClass('is-invalid').focus();
            hasError = true;
            return false; // break loop
        }
        $(this).find('.faq-title-input').removeClass('is-invalid');
        faqs.push({
            id:          $(this).data('id') || '',
            faq_title:   title,
            faq_details: $(this).find('.faq-details-input').val().trim(),
        });
    });

    if (hasError) {
        warningToast('Question field is required');
        return;
    }
    if (faqs.length === 0) {
        warningToast('Nothing to save');
        return;
    }

    $btn.prop('disabled', true).text('Saving...');

    $.ajax({
        url: SAVE_ALL_URL,
        method: 'POST',
        data: { _token: CSRF, faqs: faqs },
        success: function (res) {
            if (res.status === 200) {
                successToast(res.message || 'Saved');
                // Reload to get fresh IDs for newly inserted rows
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
