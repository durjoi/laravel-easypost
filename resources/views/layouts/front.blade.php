<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ config('app.name') }}</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="https://getbootstrap.com/docs/4.5/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{ url('assets/css/style.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ url('assets/plugins/fontawesome-free/css/all.min.css') }}">
  	<link rel="stylesheet" href="{{ url('css/admin-style.css') }}">
  	<link rel="stylesheet" href="{{ url('library/plugins/sweetalert/dist/sweetalert.css') }}">
	@yield('page-css')
</head>
<body data-url="{{ url('/') }}">>
	@include('partial.front.navbar')
  @yield('content')
  <footer class="section-footer">
		<div class="container">
			<div class="row">
				<div class="col-lg-2 col-md-6 column-1">
					<div class="footer-title"><h4 class="footer-subtitle">Contact Us</h4></div>
					<div class="top-margin">
						<div class="media">
							<i class="far fa-fw fa-clock"></i>
							<div class="media-body footer-column">
								<p>
									10:00AM - 7:00PM <br />
									Monday - Saturday<br />
									12:00PM - 5:00PM <br />
									Sunday
								</p>
							</div>
						</div>
						<div class="media">
							<i class="fas fa-fw fa-map-marker-alt"></i>
							<div class="media-body footer-column"><p>1214 S Noland Rd, Independence, MO 64055</p></div>
						</div>
						<div class="media">
							<i class="fas fa-fw fa-phone-alt"></i>
							<div class="media-body footer-column"><p>1-816-886-7285</p></div>
						</div>
						<div class="media">
							<i class="far fa-fw fa-envelope"></i>
							<div class="media-body footer-column"><p>tronicspay@gmail.com</p></div>
						</div>
					</div>
				</div>
				<div class="col-lg-2 col-md-6 column-2">
					<div class="footer-title"><h4 class="footer-subtitle">Navigation</h4></div>
					<div class="top-margin">
						<ul class="list-footer">
							@if(isset($footermenus))
								@foreach($footermenus as $menu)
								<li><a href="{{ url($menu->menu_url) }}" class="hvr-shrink">{{ $menu->name }}</a></li>
								@endforeach
								@if(isset($isValidAuthentication) && $isValidAuthentication == false)
								<li><a href="{{ url('customer/auth/login') }}" class="hvr-shrink">Member Login</a></li>
								@endif
							@endif
						</ul>
					</div>
				</div>
				<div class="col-lg-4 col-md-12 column-3">
					<img src="{{ url('assets/images/footer-logo.png') }}" class="img-fluid" />
					<div class="footer-center"><a href="{{ url('products/sell') }}" class="btn btn-warning btn-md btn-footer hvr-shrink">Sell Your Phone</a></div>
				</div>
				<div class="col-lg-2 col-md-6 column-4">
					<div class="footer-title"><h4 class="footer-subtitle">Sell Your Device</h4></div>
					<div class="top-margin">
						<ul class="list-footer">
							<li><a href="#" class="hvr-shrink">Apple</a></li>
							<li><a href="#" class="hvr-shrink">Samsung</a></li>
							<li><a href="#" class="hvr-shrink">LG</a></li>
							<li><a href="#" class="hvr-shrink">HTC</a></li>
							<li><a href="#" class="hvr-shrink">Nokia</a></li>
							<li><a href="#" class="hvr-shrink">Sony</a></li>
							<li><a href="#" class="hvr-shrink">Smart Watches</a></li>
							<li><a href="#" class="hvr-shrink">Tablets</a></li>
							<li><a href="#" class="hvr-shrink">Ipods</a></li>
							<li><a href="#" class="hvr-shrink">Gaming Devices</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-2 col-md-6 column-5">
					<div class="footer-title"><h4 class="footer-subtitle">Stay Connected</h4></div>
					<div class="top-margin social-column">
						<a href="#" class="hvr-shrink"><img src="{{ url('assets/images/social/facebook.png') }}" class="img-fluid icon-social" /></a><a href="#" class="hvr-shrink"><img src="{{ url('assets/images/social/twitter.png') }}" class="img-fluid icon-social" /></a>
						<a href="#" class="hvr-shrink"><img src="{{ url('assets/images/social/instagram.png') }}" class="img-fluid icon-social" /></a><a href="#" class="hvr-shrink"><img src="{{ url('assets/images/social/youtube.png') }}" class="img-fluid icon-social" /></a>
						<p style="padding-top: 15px;"><img src="{{ url('assets/images/reviews/1.png') }}" class="img-fluid" /><img src="{{ url('assets/images/reviews/8.png') }}" class="img-fluid" /></p>
					</div>
				</div>
			</div>
			<div class="row pt-50">
				<div class="col-lg-12">
					<div class="text-center">
						<p class="footer-bottom">© 2020 TronicsPay, All Rights Reserved | FAQ | Contact</p>
						<p class="footer-bottom">Copyrights, trademarks, and branding are the property of their respective manufacturers.</p>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://getbootstrap.com/docs/4.5/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
	<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
	<script src="{{ url('assets/dist/js/functions.js') }}"></script>
	<script type="text/javascript" src="{{ url('library/plugins/sweetalert/sweetalert_old.min.js')}}"></script>
	<script>
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	</script>
	@yield('page-js')
	<script>
		$(document).ready(function () {
			$('#cart-counter').html('<i class="fas fa-shopping-cart fa-fw"></i> <span>'+JSON.parse(decryptData(localStorage.getItem("sessionCart"))).length+'</span>');
			// $.ajax({
			// 	type: "GET",
			// 	url: "{{ url('products/cart') }}",
			// 	dataType: "json",
			// 	success: function (response) {
			// 		$('#cart-counter').html('<i class="fas fa-shopping-cart fa-fw"></i> <span>'+response+'</span>');
			// 	}
			// });
		});
	</script>
</body>
</html>