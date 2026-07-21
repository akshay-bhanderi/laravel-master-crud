<script type="text/javascript">
$(document).on('keyup keypress blur', '[title],[slug]', function() {  
    $slug = $(this).parents('{{ $parent ?? "form"}}').find('[slug]');
    var myStr = $(this).val();
    myStr=myStr.trim().toLowerCase().replace(/[`~!@#$%^&*()_\+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ').replace(/^\s+|\s+$/gm,' ').replace(/\s+/g, '-');
    $slug.val(myStr); 
    $slug.val();
});
</script>