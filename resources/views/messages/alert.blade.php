@if ($message = Session::get('success'))  
   <script type="text/javascript">
    var message =  "{{ $message }}"; 
    jQuery(document).ready(function($) {
        successToast(message);
        console.log(message);
    });      
   </script>
@endif 

@if ($message = Session::get('error'))
    <script type="text/javascript">
        var message =  "{{ $message }}";
        jQuery(document).ready(function($) {
            errorToast(message); 
            console.log(message);
        });
   </script>  
@endif  
@if ($message = Session::get('warning'))
    <script type="text/javascript">
        var message =  "{{ $message }}";
        jQuery(document).ready(function($) {
            warningToast(message);
            console.log(message);
        }); 
   </script>  
@endif
@if ($message = Session::get('info'))
    <script type="text/javascript">
        var message =  "{{ $message }}"; 
        jQuery(document).ready(function($) {
            infoToast(message); 
            console.log(message);
        }); 
   </script>  
@endif 