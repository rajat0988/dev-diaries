<head>
    @vite('resources/css/login.css')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/login.css') }}" /> --}}
</head>

<h2>Dev Diaries By JIMS</h2>
<div class="container" id="container">
	<div class="form-container sign-up-container">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <h1>Create Account</h1>
            <span>using your assigned JIMS ID for registration</span>
            <input type="text" name="name" placeholder="Name" />
            <input type="email" name="email" placeholder="Email" />
            <input type="password" name="password" placeholder="Password" />
            <button type="submit">Sign Up</button>
        </form>
	</div>
	<div class="form-container sign-in-container">
		<form method="POST" action="{{ route('login') }}">
            @csrf
			<h1>Sign in</h1>
			<span>using your assigned Dev Diaries account</span>
			<input type="email" name="email" placeholder="Email" />
			<input type="password" name="password" placeholder="Password" />
			<label style="display: flex; align-items: center; font-size: 14px; cursor: pointer;">
				<input type="checkbox" name="remember" style="margin-right: 5px;"> Remember Me
			</label>
			<a href="{{ route('password.request') }}"">Forgot your password?</a>
			<button>Sign In</button>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Welcome Back!</h1>
				<p>To keep connected with us please login with your personal information.</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Hi, there!</h1>
				<p>Enter your personal details and come onboard with us.</p>
				<button class="ghost" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div>

<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
    	container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
    	container.classList.remove("right-panel-active");
    });
</script>