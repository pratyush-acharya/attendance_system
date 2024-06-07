<!-- page title start -->
<section class="my-3 pt-3">
    <div class="text-center">
        <h1 class="fs-2 title">{{ $table_title }}</h1>
    </div>
    <div class="underline mx-auto"></div>
</section>
<!-- page title end -->

<section class="addbtn">
    @if(isset($url))
    <a href="{{route($url)}}">
        <button class="btn btn-primary">
            <i class='bx bx-add-to-queue'></i>
                Add
        </button>
    </a>
    @endif
</section> 

        
