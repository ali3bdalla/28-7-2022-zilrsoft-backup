<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @if ($page == 'show-category')
                <div class="breadcrumb-text">
                    <a href="{{ route('web.index') }}"><i class="fa fa-home"></i> Home</a>
                    <span>{{ $category->web_name }}</span>
                </div>
                @endif
            </div>

        </div>
    </div>

</div>