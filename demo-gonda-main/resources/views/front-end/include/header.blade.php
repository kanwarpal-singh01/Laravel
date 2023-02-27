<!-- Header in -->
<header>
    <div class="header-nav">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="{{route('home')}}"><img src="{{asset('public/front-end/images/logo.svg')}}"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        @forelse($menu_header as $menu_data)
                            <li class="nav-item">
                                <a class="nav-link" href="{{menu_route($menu_data->slug)}}">{{ucfirst($menu_data->title)}}</a>
                            </li>
                        @empty
                        @endforelse
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="{{route('about.us')}}">About</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="{{url('home/#features')}}">Features</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="{{route('knowledge.center')}}">Knowledge Center</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="{{route('marketplace')}}">Marketplace</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="{{url('/home/#testimonial')}}">Testimonials</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="{{url('/home/#screenshot')}}">Screenshot</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="{{url('/home/#plan')}}">Pricing</a>--}}
{{--                        </li>--}}
                    </ul>
                </div>
                <div class="login-btns">
                    <a href="#" class="btn btn-yellow" data-toggle="{{!empty($popup['status']) ? 'modal' : ''}}" data-target="{{!empty($popup['status']) ? '#exampleModalCenter' : ''}}" id="app-store-id">Download the app</a>
                </div>
            </nav>
        </div>
    </div>
</header>
<!-- Header in -->