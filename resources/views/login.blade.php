<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>

    <form method="POST" action="{{ route('login.process') }}">
        @csrf

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            @error('email')
            <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            @error('password')
            <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Remember me?</label>
        </div>

        <div>
            <button type="submit">Login</button>
        </div>

        <div>
            <a href="#">Forget password?</a>
        </div>
    </form>
</body>

</html>