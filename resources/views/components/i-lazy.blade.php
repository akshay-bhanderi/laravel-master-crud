<style type="text/css">
    [lazy-src]{
        object-fit: contain;
        width: inherit;
        max-height: 100px;
        max-width: 100px;
        margin: auto;
        display: block;
    }
</style>
<script >
var domains = [];
var currentDomainIndex = 0;
var is_proxy_started = false;
var is_proxy_running = false;

$(document).ready(function () {
    initLazyLoad();
});

var proxy_interval = setInterval(function() {
    console.log(is_proxy_started, is_proxy_running);
    if(!is_proxy_started && !is_proxy_running){
        initLazyLoad();
    }
    if(is_proxy_started && is_proxy_running){
        clearInterval(proxy_interval);
    }
    console.log('is_proxy_started',is_proxy_started, 'is_proxy_running', is_proxy_running);
}, 1000);

function initLazyLoad() {
    is_proxy_running = true;
    $('img[no-lazy-load]').each(function(index, el) {
        let Lazy_img = $(el).attr('lazy-src');
        if(Lazy_img){
            $(el).attr('src', $(el).attr('lazy-src'));
            $(el).removeAttr('lazy-src');
        }
    });
    lazyLoadImages();
    lazyLoadBackgroundImages();
    is_proxy_started = true;
}


function getNextDomain() {
    currentDomainIndex = (currentDomainIndex + 1) % domains.length;
    return domains[currentDomainIndex];
}

function lazyLoadImages(){
    let options = {
        threshold: 0.1
    };

    let observer = new IntersectionObserver(imageObserver, options);

    function imageObserver(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                let img = entry.target;
                let img_src = img.dataset.src;
                let orig_img = img.getAttribute('lazy-src');
                // orig_img = orig_img.replaceAll('{{url("/")}}','//'+getNextDomain());
                if(orig_img){
                    let tempImage = new Image();
                    tempImage.onload = function() {
                        img.width = tempImage.width;
                        img.height = tempImage.height;
                        img.src = orig_img;
                        img.removeAttribute('lazy-src');
                        observer.unobserve(img);
                    };
                    tempImage.src = orig_img;
                }
            }
        })
    }

    let imgs = document.querySelectorAll('img[lazy-src]:not([no-lazy-load])');
    imgs.forEach(img => {
        observer.observe(img);
    });
}


function lazyLoadBackgroundImages() {
    let options = {
        threshold: 0.1
    };

    let observer = new IntersectionObserver(backgroundImageObserver, options);

    function backgroundImageObserver(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                let element = entry.target;
                let imageUrl = element.getAttribute('bg-lazy-img');
                let backgroundImageUrl = imageUrl;
                try{
                    backgroundImageUrl = imageUrl.match(/url\(["']?([^"']+)["']?\)/)[1];
                }catch(e){}
                let finalImageUrl = backgroundImageUrl;
                // console.log(finalImageUrl);

                if (backgroundImageUrl) {
                    element.style.backgroundImage = `url('${finalImageUrl}')`;
                    element.removeAttribute('bg-lazy-img');
                    observer.unobserve(element);
                }
            }
        });
    }

    let elements = document.querySelectorAll('[bg-lazy-img]');
    elements.forEach(element => {
        observer.observe(element);
    });
}
</script>