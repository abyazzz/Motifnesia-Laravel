<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Motifnesia</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: sans-serif;
      font-weight: 500;
      color: white;
    }

    body {
      background-color: burlywood;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      width: 420px;
      height: 380px;
      background: white;
      backdrop-filter: blur(4px);
      box-shadow: 0 0 20px 1px gray;
      padding: 20px;
      border-radius: 12px;
    }

    .container h1 {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
      color: black;
    }

    .input-box {
      width: 100%;
      height: 50px;
      margin-bottom: 20px;
      border: 1px solid rgb(0, 0, 0);
      border-radius: 15px;
    }

    .input-box input {
      background-color: transparent;
      width: 100%;
      height: 100%;
      border: 0;
      outline: 0;
      border-radius: 10px;
      padding-left: 10px;
      color: black;
    }

    .input-box input::placeholder {
      color: rgb(0, 0, 0);
    }

    .remember-forget {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .remember-forget label,
    .remember-forget a,
    .register p,
    .register a,
    .admin a {
      color: black;
    }

    .remember-forget a:hover,
    .register a:hover,
    .admin a:hover {
      text-decoration: underline;
    }

    .btn {
      background-color: burlywood;
      width: 100%;
      height: 50px;
      border: 0;
      outline: 0;
      border-radius: 15px;
      transition: 300ms;
      cursor: pointer;
      margin-bottom: 10px;
      color: black;
      font-size: 18px;
      font-weight: 600;
    }

    .btn:hover {
      transform: scale(0.97);
    }

    .register {
      display: flex;
      justify-content: space-between;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Login</h1>

    {{-- Pesan error / success --}}
    @if (session('error'))
      <p style="color:red; text-align:center;">{{ session('error') }}</p>
    @endif
    @if (session('success'))
      <p style="color:green; text-align:center;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('auth.doLogin') }}" method="POST">
      @csrf
      <div class="input-box">
        <input type="text" name="username" placeholder="Username" required />
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Password" required />
      </div>

      <div class="remember-forget">
        <label><input type="checkbox" name="remember" /> Remember Me</label>
        <p><a href="{{ route('auth.forgot') }}">Lupa Password?</a></p>
      </div>

      <button type="submit" class="btn">Login</button>

      <div class="register">
        <p>Belum punya akun?</p>
        <a href="{{ route('auth.register') }}">Register</a>  
      </div>
    </form>
  </div>
</body>
</html>
