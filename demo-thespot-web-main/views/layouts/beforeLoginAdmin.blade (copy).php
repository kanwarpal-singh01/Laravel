<!DOCTYPE html>
<html lang="en">
<head>
	<title>Admin-Bagoo</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="{{ asset('fonts/fontawesome/css/fontawesome-all.min.css') }}" rel="stylesheet">
	<link href="{{ asset('plugins/animation/css/animate.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/style.css') }}" rel="stylesheet">
	@yield('customCss')
</head>
<body>
	<div class="auth-wrapper">
		<div class="auth-content">
			<div class="auth-bg">
				<span class="r"></span>
				<span class="r s"></span>
				<span class="r s"></span>
				<span class="r"></span>
			</div>
			@yield('content')
		</div>
	</div>
</body>
<script src="{{ asset('js/vendor-all.min.js') }}"></script> 
<script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script> 
<script src="{{ asset('js/developer.js') }}"></script>
@yield('customJs')
</html>
