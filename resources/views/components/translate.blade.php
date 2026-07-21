<style type="text/css">
    .goog-te-gadget img { display: none;}
    .goog-te-gadget-simple .goog-te-menu-value {font-size: 15px; color: #333333;  font-weight: 600; display: block; text-transform: uppercase;}
    .goog-te-gadget-simple .goog-te-menu-value:hover {color: #a81313; font-size: 15px;  font-weight: 600; display: block; text-transform: uppercase;}
    .goog-te-gadget-simple  { 
        white-space: nowrap;
        border: none;
        padding: 0;
        margin: 0;
        background-color: #fff;
        display: inline-block; 
        cursor: pointer; 
        zoom: 1; 
        color:#696e7; 
        border-radius:15px; 
    }
    .goog-te-menu-value img { display:none;}
    .goog-te-banner-frame.skiptranslate {
      display: none !important;
    } 
    .goog-te-gadget{
        font-size: 15px; color: #333333; font-family: "Raleway", sans-serif;  font-weight: 600; display: block; text-transform: uppercase;
    }
    .goog-te-menu-frame {
        max-width:100% !important; 
    }
    .goog-te-menu2 { 
        max-width: 100% !important;
        overflow-x: scroll !important;
        box-sizing:border-box !important; 
        height:auto !important; 
    }
    .header-top-left li {
        line-height: 1;
        margin-left: 10px;
        padding-left: 5px;
        position: relative;
    }
    .goog-te-gadget-simple > span > a > span{
        display: none;
    }
    .goog-te-gadget-simple > span > a,
    .goog-te-gadget-simple > span > a > span:first-child{
        display: unset;
        font-family: var(--ar-body-font-family);
        text-transform: capitalize;
        font-size: 16px;
        font-weight: var(--ar-nav-link-font-weight);
        color: var(--ar-nav-link-color) !important;
    }
</style>
<div id="google_translate_element"></div>
<script type="text/javascript">
function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en',
        autoDisplay: false,
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'google_translate_element');
    function changeGoogleStyles() {
        if(jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2').length) {
            jQuery('a.goog-te-menu-value').text('Select Language')
            jQuery('a.goog-te-menu-value').css('padding','0px')
            jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2').css({
                'max-width':'100%',
                'overflow-x':'auto',
                'box-sizing':'border-box',
                'height':'auto'
            });
            jQuery('#google_translate_element').removeClass('d-none')
        } else {
            setTimeout(changeGoogleStyles, 50);
        }
    }
    changeGoogleStyles();
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>