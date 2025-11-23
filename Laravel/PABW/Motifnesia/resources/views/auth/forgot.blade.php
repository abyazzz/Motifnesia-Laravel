<!DOCTYPE html>
<html lang="en">
    <style>
        * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Segoe UI', sans-serif;
}

body {
  background: burlywood;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  padding: 20px;
}

.wrapper {
  background: white;
  padding: 40px 30px;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  max-width: 450px;
  width: 100%;
}

.wrapper h2 {
  text-align: center;
  margin-bottom: 25px;
  color: #333;
}

label {
  font-weight: 500;
  margin-bottom: 5px;
  display: block;
  color: #444;
}

input[type="text"],
input[type="password"],
select {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  margin-bottom: 20px;
  transition: border-color 0.3s;
}

input:focus,
select:focus {
  border-color: #4a90e2;
  outline: none;
}

button {
  width: 100%;
  padding: 12px;
  background-color: #d2a679;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s;
}

button:hover {
  background-color: #b88d6d;
}

    </style>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot</title>
    <link rel="stylesheet" href="asstes/css/reset.css" />
  </head>
  <div class="wrapper">
  <h2>Reset Password</h2>
<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Pertanyaan Rahasia:</label><br>
    <select name="secret_question" required>
        <option value="">-- Pilih Pertanyaan --</option>
        <option value="makanan">Apa makanan favoritmu?</option>
        <option value="hewan">Apa hewan peliharaan pertamamu?</option>
        <option value="hobi">Apa hobimu?</option>
    </select><br><br>

    <label>Jawaban:</label><br>
    <input type="text" name="secret_answer" required><br><br>

    <label>Password Baru:</label><br>
    <input type="password" name="new_password" required><br><br>

    <button type="submit" name="submit">Reset Password</button>
</form>
</html>