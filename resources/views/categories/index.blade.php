@extends('layouts.main')

@section('title', $cat->title)

@section('content')
    <!-- Home -->

    <div class="home">
        <div class="home_container">
            <div class="home_background" style="background-image:url(/images/{{$cat->img}})"></div>
            <div class="home_content_container">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="home_content">
                                <div class="home_title">{{$cat->title}}<span>.</span></div>
                                <div class="home_text"><p>{{ $cat->desc }}</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products -->

    <div class="products">
        <div class="container">
            <div class="row">
                <div class="col">

                    <!-- Product Sorting -->
                    <div
                        class="sorting_bar d-flex flex-md-row flex-column align-items-md-center justify-content-md-start">
                        <div class="results">Showing <span>{{$cat->products->count()}}</span> results</div>
                        <div class="sorting_container ml-md-auto">
                            <div class="sorting">
                                <ul class="item_sorting">
                                    <li>
                                        <span class="sorting_text">Sort by</span>
                                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                        <ul>
                                            <li class="product_sorting_btn" data-order="default">
                                                <span>Default</span></li>
                                            <li class="product_sorting_btn" data-order="price-low-high">
                                                <span>Price: Low-High</span></li>
                                            <li class="product_sorting_btn" data-order="price-high-low">
                                                <span>Price: High-Low</span></li>
                                            <li class="product_sorting_btn" data-order="name-a-z">
                                                <span>Name: A-Z</span></li>
                                            <li class="product_sorting_btn" data-order="name-z-a">
                                                <span>Name: Z-A</span></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">

                    <div class="product_grid">
                        @foreach ($cat->products as $product)
                            @php
                                $image = '';
                                if (count($product->images) > 0) {
                                    $image = $product->images[0]['img'];
                                } else {
                                    $image = 'no_image.png';
                                }
                            @endphp
						<!-- Product -->
						<div class="product">
							<div class="product_image"><img src="images/{{ $image }}" alt=""></div>
							<div class="product_extra product_new"><a href="{{ route('showCategory', $product->category->alias) }}">{{$product->category->title}}</a></div>
							<div class="product_content">
								<div class="product_title"><a href="{{ route('showProduct', [$product->category->alias, $product->alias]) }}">{{ $product->title }}</a></div>
                                @if ($product->new_price != null)
                                    <div style="text-decoration: line-through">${{ $product->price }}</div>
                                    <div class="product_price">${{ $product->new_price }}</div>
                                @else
								    <div class="product_price">${{ $product->price }}</div>
                                @endif
							</div>
						</div>
                        @endforeach
					</div>
                    <div>
                        {{$products->appends(request()->query())->links('pagination.index')}}
                    </div>
{{--                    <div class="product_pagination">--}}
{{--                        <ul>--}}
{{--                            <li class="active"><a href="#">01.</a></li>--}}
{{--                            <li><a href="#">02.</a></li>--}}
{{--                            <li><a href="#">03.</a></li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}

                </div>
            </div>
        </div>
    </div>

    <!-- Icon Boxes -->

    <div class="icon_boxes">
        <div class="container">
            <div class="row icon_box_row">

                <!-- Icon Box -->
                <div class="col-lg-4 icon_box_col">
                    <div class="icon_box">
                        <div class="icon_box_image"><img src="images/icon_1.svg" alt=""></div>
                        <div class="icon_box_title">Free Shipping Worldwide</div>
                        <div class="icon_box_text">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam a ultricies metus. Sed
                                nec molestie.</p>
                        </div>
                    </div>
                </div>

                <!-- Icon Box -->
                <div class="col-lg-4 icon_box_col">
                    <div class="icon_box">
                        <div class="icon_box_image"><img src="images/icon_2.svg" alt=""></div>
                        <div class="icon_box_title">Free Returns</div>
                        <div class="icon_box_text">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam a ultricies metus. Sed
                                nec molestie.</p>
                        </div>
                    </div>
                </div>

                <!-- Icon Box -->
                <div class="col-lg-4 icon_box_col">
                    <div class="icon_box">
                        <div class="icon_box_image"><img src="images/icon_3.svg" alt=""></div>
                        <div class="icon_box_title">24h Fast Support</div>
                        <div class="icon_box_text">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam a ultricies metus. Sed
                                nec molestie.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('partials._newsletter')
@endsection

@section('custom_js')
    <script>
        $(document).ready(function() {
            $('.product_sorting_btn').click(function () {
                let orderBy = $(this).data('order');
                $('.sorting_text').text($(this).find('span').text());

                $.ajax({
                    url: '{{ route('showCategory', $cat->alias ) }}',
                    type: "GET",
                    data: {
                        orderBy: orderBy,
                        page: {{isset($_GET['page']) ? $_GET['page'] : 1}}
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: (data) => {
                        let positionParameters = location.pathname.indexOf('?');
                        let url = location.pathname.substring(positionParameters, location.pathname.length);
                        let newURL = url + '?';
                        newURL += 'orderBy=' + orderBy + "&page={{isset($_GET['page']) ? $_GET['page'] : 1}}";
                        history.pushState({}, '', newURL);

                        $('.product_grid').html(data);

                        $('.product_grid').isotope('destroy');
                        $('.product_grid').imagesLoaded(function() {
                            var grid = $('.product_id').isotope({
                                itemSelector: '.product',
                                layoutMode: 'fitRows',
                                fitRows: {
                                    gutter: 30
                                }
                            });
                        });
                    }
                });
            });
        });
    </script>
@endsection

@section('custom_css')
    <link rel="stylesheet" type="text/css" href="{{asset('styles/categories.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('styles/categories_responsive.css')}}">
@endsection
