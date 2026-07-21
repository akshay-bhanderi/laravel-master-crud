<div class="mb-2">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <div class="d-flex justify-content-between">
            <div>
                <ol class="breadcrumb align-items-center">
                        
                    <a href="#" id="back-btn" class="btn btn-outline-dark btn-sm me-3">&lt; Back</a>
                    <li class="breadcrumb-item">
                        <a href="{{url('/portal')}}">
                            <i class="bi bi-house small me-2"></i>
                            Home
                        </a>
                    </li>
                    <?php 
                    if(!empty($breadcrumb)){
                        $count = count($breadcrumb);
                        $i = 0;
                    foreach($breadcrumb as $key => $value){ $i++; ?>
                        <li class="breadcrumb-item text-capitalize " aria-current="page">
                            <?php if($i != $count){ ?>
                                <a href="<?=$value?>">{{$key}}</a>
                            <?php }else{ ?>
                                {{$key}}
                            <?php } ?>
                        </li>
                    <?php } } ?>
                </ol>
            </div>
            <div>
                <button class="btn btn-sm btn-danger d-md-none d-block" onclick="location.reload()">Refresh</button>
            </div>
                    
        </div>
    </nav>
</div>

<script>
$(document).ready(function() {
    var secondBreadcrumbItem = $('.breadcrumb-item').eq(-2);
    if (secondBreadcrumbItem.find('a').length) {
        var link = secondBreadcrumbItem.find('a').attr('href');
        $('#back-btn').attr('href', link);
    }
});
</script>