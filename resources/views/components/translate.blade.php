<style type="text/css">
    div.skiptranslate {
        display: none !important;
    }
    body {
        top: 0px !important;
    }
    .goog-te-gadget img { display: none;}
    .goog-te-gadget-simple .goog-te-menu-value {font-size: 15px; color: #333333;  font-weight: 600; display: block; text-transform: uppercase;}
    .goog-te-gadget-simple .goog-te-menu-value:hover {color: #a81313; font-size: 15px;  font-weight: 600; display: block; text-transform: uppercase;}
    .goog-te-gadget-simple  { 
        white-space: nowrap;
        border: none;
        padding: 0;
        margin: 0;
        background-color: transparent;
        display: inline-block; 
        cursor: pointer; 
        zoom: 1;         
        font-size: 15px;
        font-weight: 500;
        color: #000000;
        position: relative;
        text-transform: capitalize;
        transition: color 0.3s ease;
    }
    .translate-btn { 
        padding-left: 10px;    
    }
    /* .goog-te-gadget-simple::before  { 
        content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="%23081E2A" class="bi bi-globe-americas" viewBox="0 0 16 16"><path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0M2.04 4.326c.325 1.329 2.532 2.54 3.717 3.19.48.263.793.434.743.484q-.121.12-.242.234c-.416.396-.787.749-.758 1.266.035.634.618.824 1.214 1.017.577.188 1.168.38 1.286.983.082.417-.075.988-.22 1.52-.215.782-.406 1.48.22 1.48 1.5-.5 3.798-3.186 4-5 .138-1.243-2-2-3.5-2.5-.478-.16-.755.081-.99.284-.172.15-.322.279-.51.216-.445-.148-2.5-2-1.5-2.5.78-.39.952-.171 1.227.182.078.099.163.208.273.318.609.304.662-.132.723-.633.039-.322.081-.671.277-.867.434-.434 1.265-.791 2.028-1.12.712-.306 1.365-.587 1.579-.88A7 7 0 1 1 2.04 4.327Z"/></svg>');
        top: -22px;
        left: 50%;
        transform: translate(-50%, 0);
        position: absolute;
    } */
    
    iframe .indicator {
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 40px;
        /* background-color: #f3f3f3; */
        display: none;
    }

    .goog-te-menu-value img { display:none;}
    .goog-te-banner-frame.skiptranslate {
      display: none !important;
    } 
    .goog-te-gadget{
        font-size: 15px; color: #333333; font-family: "Raleway", sans-serif;  font-weight: 600; display: block; text-transform: uppercase;
        /* position: absolute; */
        /* top: 32px; */
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
    .goog-te-gadget-simple{
        height: 10px;
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
        color: #fff !important;
    }
    
    @media(max-width: 991px){
        .goog-te-gadget{
            padding: 20px;
        }
        .goog-te-gadget-simple::before{
            top: 0px;
        }
    }
</style>
<div id="google_translate_element" style="display: none;"></div>
<script type="text/javascript">
function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'en,de,es,fr', 
        autoDisplay: false,
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
        defaultLanguage: 'en'
    }, 'google_translate_element');
}
function changeLanguage(lang) {
    var $frame = $('iframe.skiptranslate');
    if (!$frame.length) {
        alert("Error: Could not find Google translate frame.");
        return false;
    }
    $frame.contents().find('span.text:contains('+lang+')').get(0).click();    
    $('#languageDropdown').text(lang.toUpperCase());
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
