<script type="text/javascript">
$(document).ready(function(){
    var dd = {
        beforeSend: function() {
            // Disable submit button
            $("#{{ $formId ?? 'add_form' }}").find('button[type="submit"]').prop('disabled', true);
            $("#{{ $formId ?? 'add_form' }}").find('button[type="submit"] > span').removeClass('d-none');
        },
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function() {},
        complete: function(response) {
            var result = jQuery.parseJSON(response.responseText);
            // Re-enable submit button
            $("#{{ $formId ?? 'add_form' }}").find('button[type="submit"]').prop('disabled', false);
            $("#{{ $formId ?? 'add_form' }}").find('button[type="submit"] > span').addClass('d-none');
            
            if (result.status == 200) {
                successToast(result.message);
                @if(!empty($script))
                    try{
                        {!! $script !!}
                    }catch(e){
                        <?php 
                            try { $link = route($route.'.master'); ?>

                            window.open('<?php echo $link ?>','_self')

                        <?php  } catch (\Exception $e) { ?>

                            window.location.reload();

                        <?php } ?>
                    }
                @else
                    var redirectUrl = '{{ request()->get('redirect') }}';
                    if (redirectUrl) {
                        window.open(decodeURIComponent(redirectUrl),'_self');
                    } else {
                        window.open('<?php echo route($route.'.master') ?>','_self')
                    }
                @endif
            }else{
                errorToast(result.message);
            }
        },
        error: function() {
            // Re-enable submit button on error
            $("#{{ $formId ?? 'add_form' }}").find('button[type="submit"]').prop('disabled', false);
            $("#{{ $formId ?? 'add_form' }}").find('button[type="submit"] > span').addClass('d-none');
        }
    };
    jQuery("#{{ $formId ?? 'add_form' }}").ajaxForm(dd);
});
</script>