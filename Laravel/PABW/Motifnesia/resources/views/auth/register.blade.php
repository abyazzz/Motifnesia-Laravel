<!DOCTYPE html>
<html lang="en">
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: sans-serif;
    font-weight: 500;
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
    background: white;
    backdrop-filter: blur(4px);
    box-shadow: 0 0 20px 1px gray;
    padding: 20px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
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
  }

  .input-box option {
    color: black;
  }

  .input-box input,
  .input-box option {
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

  .remember-forget label {
    color: black;
  }

  .remember-forget a {
    color: black;
    text-decoration: none;
    transition: 300ms;
  }

  .remember-forget a:hover {
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
  }

  .btn a {
    font-size: 24px;
    color: black;
    text-decoration: none;
  }

  .btn:hover {
    transform: scale(0.97);
  }

  .register {
    display: flex;
    justify-content: space-between;
  }

  .register a {
    text-decoration: none;
    color: black;
    margin-bottom: 15px;
  }

  .register p {
    color: black;
  }

  .register a:hover {
    text-decoration: underline;
  }

  .icon-app {
    width: 100%;
    display: flex;
    justify-content: center;
    gap: 1rem;
  }

  .texxx {
    color: black;
    margin: 50px auto;
  }

  .icon-app i {
    font-size: 28px;
    color: black;
    padding: 2px;
    transition: 200ms;
  }

  .icon-app i:hover {
    transform: scale(1.1);
  }
</style>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up</title>
  <link rel="stylesheet" href="../asstes/css/halamanRegister.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
    crossorigin="anonymous" />
</head>

<body>
  <div class="container">
    <h1>Sign Up</h1>

    <!-- FORM MULAI DI SINI -->
    <form action="{{ route('doRegister') }}" method="POST">
      @csrf
      <div class="input-box">
        <input type="text" name="username" placeholder="Username" required />
      </div>
      <div class="input-box">
        <input type="text" name="full_name" placeholder="Nama Lengkap (opsional)" />
      </div>
      <div class="input-box">
        <input type="email" name="email" placeholder="Email" required />
      </div>
      <div class="input-box">
        <input type="password" name="password" id="password" placeholder="Password" required />
      </div>
      <div class="input-box">
        <input type="password" name="password_confirmation" id="confirm_password" placeholder="Confirm Password"
          required />
      </div>
      <select class="input-box" name="secret_question" required>
        <option value="">-- Pilih Pertanyaan Rahasia --</option>
        <option value="makanan">Apa makanan favoritmu?</option>
        <option value="hewan">Apa hewan peliharaan pertamamu?</option>
        <option value="hobi">Apa hobimu?</option>
      </select>
      <div class="input-box">
        <input type="text" name="secret_answer" placeholder="Jawaban" required>
      </div>
      <button class="btn" type="submit">Sign Up</button>
    </form>


    <p class="texxx">Sign Up With</p>
    <div class="icon-app">
      <a href="#"><i class="fa-brands fa-google"></i></a>
      <a href="#"><i class="fa-brands fa-facebook"></i></a>
      <a href="#"><i class="fa-brands fa-github"></i></a>
    </div>
  </div>
  <script>
    const form = document.querySelector("form");
    form.addEventListener("submit", function (e) {
      const pw = document.getElementById("password").value;
      const cpw = document.getElementById("confirm_password").value;

      if (pw.length < 6) {
        alert("Password minimal 6 karakter!");
        e.preventDefault();
      } else if (pw !== cpw) {
        alert("Konfirmasi password tidak cocok!");
        e.preventDefault();
      }
    });
  </script>
</body>

</html>