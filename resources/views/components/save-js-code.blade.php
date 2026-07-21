<script type="text/javascript">
$(document).ready(function(){
    var dd = {
        beforeSend: function() {
            // Disable submit button
            $("#{{ $formId ?? 'a_form_id' }}").find('button[type="submit"]').prop('disabled', true);
            $("#{{ $formId ?? 'a_form_id' }}").find('button[type="submit"] > span').removeClass('d-none');
        },
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function() {},
        complete: function(response) {
            var result = jQuery.parseJSON(response.responseText);
            // Re-enable submit button
            $("#{{ $formId ?? 'a_form_id' }}").find('button[type="submit"]').prop('disabled', false);
            $("#{{ $formId ?? 'a_form_id' }}").find('button[type="submit"] > span').addClass('d-none');
            
            if (result.status == 200) {
                successToast(result.message);
                @if(!empty($script))
                    try{
                        {!! $script !!}
                    }catch(e){
                        setTimeout(function() {
                            window.open('<?php echo route($route.'.master') ?>','_self')
                        }, 1000);
                    }
                @else
                    var redirectUrl = '{{ request()->get('redirect') }}';
                    if (redirectUrl) {
                        setTimeout(function() {
                            window.open(decodeURIComponent(redirectUrl),'_self');
                        }, 1000);
                    } else {
                        setTimeout(function() {
                            window.open('<?php echo route($route.'.master') ?>','_self')
                        }, 1000);
                    }
                @endif
            }else{
                errorToast(result.message);
            }
        },
        error: function() {
            // Re-enable submit button on error
            $("#{{ $formId ?? 'a_form_id' }}").find('button[type="submit"]').prop('disabled', false);
            $("#{{ $formId ?? 'a_form_id' }}").find('button[type="submit"] > span').addClass('d-none');
        }
    };
    jQuery("#{{ $formId ?? 'a_form_id' }}").ajaxForm(dd);
});
</script>