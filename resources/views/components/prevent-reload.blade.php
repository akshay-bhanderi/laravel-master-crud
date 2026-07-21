<script>
    let formSubmitted = false;
    $(document).on('click', '.save-btns > [type="submit"]', function() {
        formSubmitted = true;
    });
    $(window).on('beforeunload', function(e) {
        if (!formSubmitted) {
            e.preventDefault();
            e.returnValue = '';
            return 'You have unsaved changes. Are you sure you want to leave this page?';
        }
    });
    document.body.style.overscrollBehavior = 'none';
</script>