let lastCapturedImage = null;

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

$(document).ready(function() {
    $(document).on("change", 'input[type="file"]', function() {
        if ($(this).next().hasClass("drop-area")) {
            const $dropArea = $(this).next();
            const files = Array.from(this.files);
            lastCapturedImage = files[0];

            let processedFiles = files;
            const processFile = async(file) => {
                if (file.type.startsWith('image/')) {
                    const croppedFile = await cropImage(file);
                    if ($dropArea.hasClass("compress")) {
                        const img = new Image();
                        img.src = URL.createObjectURL(croppedFile);
                        await new Promise(resolve => img.onload = resolve);
                        const maxWidth = 1524;
                        const maxHeight = 1524;
                        if (img.width > maxWidth || img.height > maxHeight) {
                            return compressImage(croppedFile, maxWidth, maxHeight, 1);
                        }
                    }
                    return croppedFile;
                }
                return file;
            };

            Promise.all(files.map(processFile))
                .then(processedFiles => {
                    const dataTransfer = new DataTransfer();
                    processedFiles.forEach(file => dataTransfer.items.add(file));
                    this.files = dataTransfer.files;
                    handleFiles(this.files, $dropArea);
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

    $(document).on('paste', function (e) {
        if ($('#image_modal').is(':visible')) {
            var items = (e.clipboardData || e.originalEvent.clipboardData).items;
            for (var i = 0; i < items.length; i++) {
                if (items[i].type.indexOf("image") !== -1) {
                    var file = items[i].getAsFile();
                    handleFiles([file], $('#uploader_type').next());
                }
            }
        }
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
                div += '<div style="width: auto;" class="mx-2 uploaded-image-container"><div style="padding: 5px;margin-bottom: 8px;text-align:center;"><img class="previewImage" src="' + temp_uploaded_image + '"></div></div>';
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
    var files = dt.files;
    handleFiles(files, $passsed_this);
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
    if ($passsed_this.find('.row').find('img').length <= 0) {
        $passsed_this.find('.row').empty();
    }
    if ($passsed_this.prev().attr('multiple') != 'multiple') {
        $passsed_this.find('.row').empty();
    }
    if (file.type.split('/')[0] == 'image') {
        var img = ' <img class="previewImage" src="' + URL.createObjectURL(file) + '" style="max-width: 100%;">';
    } else {
        var img = '<svg style="height: 130px;  object-fit: contain; margin: auto;" enable-background="new 0 0 600 600" inkscape:version="0.92.3 (2405546, 2018-03-11)" sodipodi:docname="general.svg" version="1.1" viewBox="0 0 600 600" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"><sodipodi:namedview bordercolor="#666666" borderopacity="1" gridtolerance="10" guidetolerance="10" inkscape:current-layer="Layer_1" inkscape:cx="256" inkscape:cy="256" inkscape:pageopacity="0" inkscape:pageshadow="2" inkscape:window-height="667" inkscape:window-maximized="1" inkscape:window-width="1366" inkscape:window-x="0" inkscape:window-y="27" inkscape:zoom="0.4609375" objecttolerance="10" pagecolor="#ffffff" showgrid="false"></sodipodi:namedview><polygon transform="matrix(.93509 0 0 .93509 16.617 16.617)" points="337.21 4 441.88 108.67 441.88 508 63.24 508 63.24 4" fill="#fff" stroke-width="1.0694"></polygon><path d="m330.38 24.098 95.693 95.693v368.11h-346.58v-463.8h250.89m3.097-7.4807h-261.47v478.77h361.54v-378.69z" fill="#ccc" inkscape:connector-curvature="0"></path><g stroke-width="1.0694"><polygon transform="matrix(.93509 0 0 .93509 16.617 16.617)" points="338.84 0 445.88 107.02 338.84 107.02" fill="#999"></polygon><polygon transform="matrix(.93509 0 0 .93509 16.617 16.617)" points="22.04 293.01 489.96 293.01 407.53 422.9 22.04 422.9" fill="#333"></polygon><text x="235" y="375" text-anchor="middle" fill="#fff" font-size="70" style="font-weight: 600;">' + file.name.split('.').pop().toUpperCase() + '</text><polygon transform="matrix(.93509 0 0 .93509 16.617 16.617)" points="22.04 422.9 59.24 460.08 59.24 422.9" fill="#999"></polygon></g></svg><div>' + file.name.split('.').slice(0, -1).join('.').substring(0, 15) + '.' + file.name.split('.').pop() + '</div>';
    }
    var div = '<div id="' + passed_id + '" style="width: auto;" class="mx-2 uploaded-image-container"><div style="padding: 5px;margin-bottom: 8px;text-align:center;">' + img + '</div><div class="progress progress-primary progress-da mb-3"><div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div> </div></div>';
    $passsed_this.find('.row').append(div);
}

function doUpload(file, $passsed_this, passed_id) {
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
                if (status === 'error' || status === 'timeout' || xhr.status === 0) {
                    errorToast('Upload failed due to network issue.');
                    errorToast('Retrying...');
                    doUpload(file, $passsed_this, passed_id);
                } else {
                    errorToast('Error uploading file. Upload new file.');
                }
            }
        });
    } else {
        errorToast('Please Select valid file to upload');
    }
}

$(document).on('click', '.remove-image-btn', function() {
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