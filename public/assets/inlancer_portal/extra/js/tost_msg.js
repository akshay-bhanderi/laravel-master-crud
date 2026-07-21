
function successToast(message){
    iziToast.success({
        title: 'Success',
        message: message,
        position: 'topRight', 
    });
}
function errorToast(message){
    iziToast.error({
        title: 'Error',
        message: message,
        position: 'topRight', 
    });
}
function warningToast(message){
    iziToast.warning({
        title: 'Warning',
        message: message,
        position: 'topRight', 
    });
}
function infoToast(message){
    iziToast.info({
        title: 'Info',
        message: message,
        position: 'topRight', 
    });
}

