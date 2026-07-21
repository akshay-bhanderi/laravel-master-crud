let lastCapturedImage = null;

/*
 * Shared file-type/preview helpers used by both the drop-area upload
 * previews and the image-library modal (imagePress.blade.php), so a
 * PDF/non-image file looks the same everywhere: an inline SVG icon
 * showing the extension, no external icon asset, with the filename
 * shown underneath.
 */
function getUrlExtension(url) {
    try {
        var clean = (url || '').split(/[?#]/)[0];
        var parts = clean.split('.');
        return parts.length > 1 ? parts.pop().toLowerCase() : '';
    } catch (e) {
        return '';
    }
}

function isImageExtension(ext) {
    return ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'].indexOf((ext || '').toLowerCase()) !== -1;
}

function getUrlFileName(url) {
    var clean = (url || '').split(/[?#]/)[0];
    return decodeURIComponent(clean.split('/').pop() || '');
}

function buildFileIconSvg(extension, heightPx) {
    heightPx = heightPx || 110;
    return '<svg viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg" style="height:' + heightPx + 'px;width:100%;object-fit:contain;margin:auto;">' +
        '<polygon transform="matrix(.93509 0 0 .93509 16.617 16.617)" points="337.21 4 441.88 108.67 441.88 508 63.24 508 63.24 4" fill="#fff" stroke-width="1.0694"></polygon>' +
        '<path d="m330.38 24.098 95.693 95.693v368.11h-346.58v-463.8h250.89m3.097-7.4807h-261.47v478.77h361.54v-378.69z" fill="#ccc"></path>' +
        '<g stroke-width="1.0694">' +
        '<polygon transform="matrix(.93509 0 0 .93509 16.617 16.617)" points="338.84 0 445.88 107.02 338.84 107.02" fill="#999"></polygon>' +
        '<polygon transform="matrix(.93509 0 0 .93509 16.617 16.617)" points="22.04 293.01 489.96 293.01 407.53 422.9 22.04 422.9" fill="#333"></polygon>' +
        '<text x="235" y="375" text-anchor="middle" fill="#fff" font-size="70" font-weight="600">' + (extension || '').toUpperCase() + '</text>' +
        '<polygon transform="matrix(.93509 0 0 .93509 16.617 16.617)" points="22.04 422.9 59.24 460.08 59.24 422.9" fill="#999"></polygon>' +
        '</g>' +
        '</svg>';
}

function buildFileIconWithName(fileName, heightPx) {
    var parts = (fileName || '').split('.');
    var ext = parts.length > 1 ? parts.pop() : '';
    var baseName = parts.join('.');
    if (baseName.length > 20) {
        baseName = baseName.substring(0, 20);
    }
    var label = ext ? (baseName + '.' + ext) : baseName;
    return '<span class="file-icon-wrap">' + buildFileIconSvg(ext, heightPx) +
        '<span class="file-name-caption" title="' + (fileName || '').replace(/"/g, '&quot;') + '">' + label + '</span></span>';
}

function compressImage(file, maxWidth, maxHeight, quality) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function(event) {
            const img = new Image();
            img.src = event.target.result;
            img.onload = function() {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;

                if (width > height) {
                    if (width > maxWidth) {
                        height *= maxWidth / width;
                        width = maxWidth;
                    }
                } else {
                    if (height > maxHeight) {
                        width *= maxHeight / height;
                        height = maxHeight;
                    }
                }

                canvas.width = width;
                canvas.height = height;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob((blob) => {
                    resolve(new File([blob], file.name, {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    }));
                }, 'image/jpeg', quality);
            };
        };
        reader.onerror = reject;
    });
}

function cropImage(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const cropperModal = $('<div class="modal fade" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Crop Image</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-outline-dark rotate-left-btn mx-auto ms-0" ><i class="bi bi-arrow-counterclockwise"></i></button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-primary" id="crop-btn">Crop</button></div></div></div></div>');
                $('body').append(cropperModal);

                cropperModal.find('.modal-body').append(img);
                if (typeof Cropper === 'undefined') {
                    reject(new Error('Cropper library is not loaded'));
                    return;
                }
                let cropper;

                const initCropper = () => {
                    cropper = new Cropper(img, {
                        // initialAspectRatio: 1,
                        // aspectRatio: 1,
                        viewMode: 1,
                        modal: true,
                        responsive: true,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: true,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                        imageSmoothingEnabled: false,
                        imageSmoothingQuality: 'high',
                        checkOrientation: false,
                        background: false,
                        fillColor: 'transparent',
                        minContainerWidth: 250,
                        minContainerHeight: 250,
                        minCropBoxWidth: 100,
                        minCropBoxHeight: 100,
                        rotatable: true,
                        movable: false,
                        zoomable: false,
                    });
                };

                cropperModal.on('shown.bs.modal', function() {
                    const modalBody = cropperModal.find('.modal-body');
                    const windowHeight = window.innerHeight;
                    const modalHeaderHeight = cropperModal.find('.modal-header').outerHeight() || 0;
                    const modalFooterHeight = cropperModal.find('.modal-footer').outerHeight() || 0;
                    const availableHeight = windowHeight - modalHeaderHeight - modalFooterHeight;

                    modalBody.css({
                        'height': `${availableHeight}px`,
                        'max-height': `${availableHeight}px`,
                        'padding': '0'
                    });

                    if (cropper) {
                        cropper.destroy();
                    }
                    initCropper();
                    cropper.resize();

                    cropper.container.style.height = '100%';
                    cropper.crop();

                    // Add rotate buttons
                });
                const rotateimage = function() {
                    if (cropper) {
                        cropper.setAspectRatio(1);
                        cropper.rotate(90);
                        cropper.setAspectRatio(NaN); // Unset aspect ratio after rotation
                    }
                }

                const updateCropper = function() {
                    setTimeout(function() {
                        if (cropper) {
                            cropper.destroy();
                        }
                        initCropper();
                        cropper.resize();
                        cropper.crop();
                    }, 200);
                };

                window.addEventListener('orientationchange', updateCropper);
                window.addEventListener('resize', updateCropper);
                $('.rotate-left-btn').on('click', rotateimage);

                cropperModal.modal('show');

                $('#crop-btn').on('click', function() {
                    canvas.width = cropper.getCroppedCanvas().width;
                    canvas.height = cropper.getCroppedCanvas().height;
                    cropper.getCroppedCanvas({
                        fillColor: '#ffffff'
                    }).toBlob((blob) => {
                        const fileType = file.type === 'image/png' ? 'image/png' : 'image/jpeg';
                        resolve(new File([blob], file.name, {
                            type: fileType,
                            lastModified: Date.now()
                        }));
                        cropperModal.modal('hide');
                        cropperModal.remove();
                    }, file.type === 'image/png' ? 'image/png' : 'image/jpeg');
                });

                cropperModal.on('hidden.bs.modal', function() {
                    if (cropper) {
                        cropper.destroy();
                    }
                    cropperModal.remove();
                });

                // Handle btn-close click
                cropperModal.find('.btn-close').on('click', function() {
                    if (cropper) {
                        cropper.destroy();
                    }
                    cropperModal.remove();
                    return false;
                });
            };
            img.src = e.target.result;
        };
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

/*
 * Shared per-file processing for both file-picker and drag-and-drop uploads.
 * Crop is opt-in (drop-area needs the "crop" class) and off by default;
 * compress is likewise opt-in via the existing "compress" class.
 */
async function processFile(file, $dropArea) {
    if (!file.type.startsWith('image/')) {
        return file;
    }
    let workingFile = file;
    if ($dropArea.hasClass("crop")) {
        workingFile = await cropImage(workingFile);
    }
    if ($dropArea.hasClass("compress")) {
        const img = new Image();
        img.src = URL.createObjectURL(workingFile);
        await new Promise(resolve => img.onload = resolve);
        const maxWidth = 1524;
        const maxHeight = 1524;
        if (img.width > maxWidth || img.height > maxHeight) {
            workingFile = await compressImage(workingFile, maxWidth, maxHeight, 1);
        }
    }
    return workingFile;
}

$(document).ready(function() {
    $(document).on("change", 'input[type="file"]', function() {
        if ($(this).next().hasClass("drop-area")) {
            const $dropArea = $(this).next();
            const files = Array.from(this.files);
            const inputEl = this;
            lastCapturedImage = files[0];

            Promise.all(files.map(file => processFile(file, $dropArea)))
                .then(processedFiles => {
                    const dataTransfer = new DataTransfer();
                    processedFiles.forEach(file => dataTransfer.items.add(file));
                    inputEl.files = dataTransfer.files;
                    handleFiles(inputEl.files, $dropArea);
                })
                .catch(error => {
                    console.error("Error processing images:", error);
                    handleFiles(files, $dropArea);
                });
        }
    });

    $(document).on("click", ".drop-area", function() {
        console.log($(this));
        $(this).prev().click();
    });

    // $(document).on("click", ".use-last-image-btn", function() {
    //     if (lastCapturedImage) {
    //         cropImage(lastCapturedImage).then(croppedFile => {
    //             const dataTransfer = new DataTransfer();
    //             dataTransfer.items.add(croppedFile);
    //             let id = $(this).data('id');
    //             const input = $('#' + id).prev('input[type="file"]')[0];
    //             input.files = dataTransfer.files;
    //             handleFiles(input.files, $('#' + id));
    //         }).catch(error => {
    //             console.error("Error cropping last captured image:", error);
    //         });
    //     }
    // });

    set_drop_images();
});

jQuery(document).on('show.bs.modal', '.modal', function() {
    set_drop_images();
});

jQuery(document).on('reset', 'form', function(e) {
    $('.drop-area').each(function(index, el) {
        $(el).find('.row').html('<i class="bi bi-cloud-arrow-up mb-2 upload-icon"></i><h3 class="img-upload-title">Click "Here" or drop your Image here</h3>');
    });
});

function set_drop_images() {
    // $('.use-last-image-btn').remove();
    $(".drop-area").each(function(index, el) {
        var uploaded_image = $(el).attr('uploaded-image');
        if (typeof uploaded_image !== 'undefined' && uploaded_image !== '') {
            var uploaded_image_arr = uploaded_image.split(',');
            var div = '';
            for (let temp_uploaded_image of uploaded_image_arr) {
                var ext = getUrlExtension(temp_uploaded_image);
                var mediaHtml = isImageExtension(ext) ?
                    '<img class="previewImage" src="' + temp_uploaded_image + '">' :
                    buildFileIconWithName(getUrlFileName(temp_uploaded_image), 90);
                div += '<div style="width: auto;" class="mx-2 uploaded-image-container"><div style="padding: 5px;margin-bottom: 8px;text-align:center;">' + mediaHtml + '</div></div>';
            }
            if (div !== '') {
                $(el).find('.row').empty();
                $(el).find('.row').append(div);
            }
        }
        if (lastCapturedImage) {
            // let id = $(el).attr('id');
            // $(el).parent().find('label').append('<button type="button" class="btn btn-sm btn-outline-primary use-last-image-btn" data-id="' + id + '">Use Last Captured Image</button>');
        }
    });
}

$(document).on('dragenter dragover dragleave', ".drop-area", function(e) {
    e.preventDefault();
    e.stopPropagation();
});

$(document).on('drop', ".drop-area", function(e) {
    e.preventDefault();
    handleDrop(e, $(this));
});

/************************* Drag and drop ***************** */

function handleDrop(e, $passsed_this) {
    var dt = e.originalEvent.dataTransfer;
    var files = Array.from(dt.files);

    Promise.all(files.map(file => processFile(file, $passsed_this)))
        .then(processedFiles => {
            handleFiles(processedFiles, $passsed_this);
        })
        .catch(error => {
            console.error("Error processing dropped files:", error);
            handleFiles(files, $passsed_this);
        });
}

function handleFiles(files, $passsed_this) {
    files = [...files];
    for (let file of files) {
        var timestamp = +new Date
        previewFile(file, $passsed_this, timestamp);
        doUpload(file, $passsed_this, timestamp);
    }
}

function previewFile(file, $passsed_this, passed_id) {
    if ($passsed_this.find('.row').find('.uploaded-image-container').length <= 0) {
        $passsed_this.find('.row').empty();
    }
    if ($passsed_this.prev().attr('multiple') != 'multiple') {
        $passsed_this.find('.row').empty();
    }
    if (file.type.split('/')[0] == 'image') {
        var img = ' <img class="previewImage" src="' + URL.createObjectURL(file) + '" style="max-width: 100%;">';
    } else {
        var img = buildFileIconWithName(file.name, 130);
    }
    var div = '<div id="' + passed_id + '" style="width: auto;" class="mx-2 uploaded-image-container"><button type="button" class="remove-image-btn" data-image-id="" title="Remove">&times;</button><div style="padding: 5px;margin-bottom: 8px;text-align:center;">' + img + '</div><div class="progress progress-primary progress-da mb-3"><div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div> </div></div>';
    $passsed_this.find('.row').append(div);
}

function doUpload(file, $passsed_this, passed_id, retryCount) {
    retryCount = retryCount || 0;
    var fd = new FormData();
    if (typeof(iat) !== 'undefined' && iat.length != 0) {
        fd.append('image_alt_tag', iat);
    } else {
        fd.append('image_alt_tag', file.name);
    }
    if (typeof(file) !== 'undefined' && file.length != 0) {
        fd.append('image_file', file, file.name);
        fd.append('_token', csrf_token);
        /* // console.log(fd.get('image_file'));*/
        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        var $progressBar = $('#' + passed_id).find('[role="progressbar"]');
                        $progressBar.attr('aria-valuenow', percentComplete);
                        $progressBar.css('width', percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            type: "POST",
            data: fd,
            contentType: false,
            processData: false,
            url: APPLICATION_URL + '/common-image-upload',
            success: function(response) {
                /*$('body').css('pointer-events','none'); */
                // console.log(response);
                storePerformance($passsed_this, response);
                $('#' + passed_id).find('.remove-image-btn').attr('data-image-id', response.image_id);
                setTimeout(function() {

                    $('#' + passed_id).find('[role="progressbar"]').attr('aria-valuenow', '100').css('width', '100%').addClass('bg-success');

                    var upload_true = false
                    if ($passsed_this.prev().attr('multiple') != 'multiple') {
                        upload_true = true;
                    } else {
                        if ($passsed_this.find('.previewImage').length == $passsed_this.next("[data-image]").val().split(',').filter(x => x).length) {
                            upload_true = true;
                        }
                    }
                    if (upload_true) {
                        // $passsed_this.addClass('success');
                    }

                }, 500);
                var json_data = response;
                if (json_data.status == "500") {
                    Swal.fire({
                        type: 'warning',
                        title: 'Oops',
                        text: json_data.message,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                }
                // lastCapturedImage = file;
                set_drop_images();
            },
            error: function(xhr, status, error) {
                var maxRetries = 3;
                if ((status === 'error' || status === 'timeout' || xhr.status === 0) && retryCount < maxRetries) {
                    errorToast('Upload failed due to network issue. Retrying...');
                    setTimeout(function() {
                        doUpload(file, $passsed_this, passed_id, retryCount + 1);
                    }, 1000);
                } else if (retryCount >= maxRetries) {
                    errorToast('Upload failed after multiple attempts. Please try again.');
                } else {
                    errorToast('Error uploading file. Upload new file.');
                }
            }
        });
    } else {
        errorToast('Please Select valid file to upload');
    }
}

$(document).on('click', '.remove-image-btn', function(e) {
    e.stopPropagation();
    const $btn = $(this);
    const imageId = $btn.data('image-id');
    const $imageContainer = $btn.closest('.uploaded-image-container');
    const $dropArea = $imageContainer.closest('.drop-area');
    const $dataImage = $dropArea.next("[data-image]");
    const imageIds = $dataImage.val().split(',').filter(id => id !== imageId.toString());
    $dataImage.val(imageIds.join(','));
    $imageContainer.remove();
    if ($dropArea.find('.previewImage').length == 0) {
        $dropArea.find('.row').html('<i class="bi bi-cloud-arrow-up mb-2 upload-icon"></i><h3 class="img-upload-title">Click "Here" or drop your Image here</h3>');
    }

});

function storePerformance($passsed_this, return_data) {
    if (typeof $passsed_this.prev('[type="file"]').attr('multiple') !== 'undefined' && $passsed_this.next("[data-image]").val().length > 0) {

        $passsed_this.next("[data-image]").val($passsed_this.next("[data-image]").val() + ',' + return_data.image_id);
        // $passsed_this.next("[data-url]").val($passsed_this.next().next("[data-url]").val()+','+return_data.image_url);
    } else {
        $passsed_this.next("[data-image]").val(return_data.image_id);
        $passsed_this.next("[data-image]").next("[data-url]").val(return_data.image_url);
    }

    $call_fn = $passsed_this.next("[data-image]").attr('callback-function');
    if (typeof $call_fn != 'undefined' && $call_fn.length > 0) {
        fn_callback = window[$call_fn]();
    }
}