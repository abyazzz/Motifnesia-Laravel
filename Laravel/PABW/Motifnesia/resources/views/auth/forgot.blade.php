<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password - Motifnesia</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInLeft {
      from {
        opacity: 0;
        transform: translateX(-50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes fadeInRight {
      from {
        opacity: 0;
        transform: translateX(50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .animate-fade-in-up {
      animation: fadeInUp 0.8s ease-out forwards;
    }

    .animate-fade-in-left {
      animation: fadeInLeft 0.8s ease-out forwards;
    }

    .animate-fade-in-right {
      animation: fadeInRight 0.8s ease-out forwards;
    }

    .transition-link {
      position: relative;
      display: inline-block;
      overflow: hidden;
    }

    .transition-link::before {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background: #8B4513;
      transition: width 0.3s ease;
    }

    .transition-link:hover::before {
      width: 100%;
    }

    body {
      opacity: 0;
      animation: fadeInUp 0.5s ease-out forwards;
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center" style="background: linear-gradient(135deg, #FFF8F0 0%, #FFE4D6 100%);">
  
  <!-- Centered Container -->
  <div class="w-full max-w-5xl mx-4 bg-white rounded-3xl shadow-2xl overflow-hidden flex animate-fade-in-up">
    
    <!-- Left Side - Branding/Gradient -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden" style="background: linear-gradient(135deg, #D2691E 0%, #8B4513 100%);">
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-48 h-48 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-64 h-64 bg-white rounded-full blur-3xl"></div>
      </div>
      <div class="relative z-10 flex flex-col justify-center items-center w-full px-12 py-16 text-white text-center">
        <div class="mb-8">
          <svg class="w-24 h-24 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
          </svg>
        </div>
        <h1 class="text-5xl font-bold mb-4">Forgot Password?</h1>
        <p class="text-lg opacity-90 mb-8">Jangan khawatir! Kami akan membantu Anda mengatur ulang password dengan mudah dan aman.</p>
        <a href="{{ route('auth.login') }}" 
           onclick="handleTransition(event, '{{ route('auth.login') }}')"
           class="px-8 py-3 border-2 border-white rounded-full font-semibold hover:bg-white hover:text-orange-800 transition-all duration-300">
          BACK TO LOGIN
        </a>
      </div>
    </div>

    <!-- Right Side - Reset Password Form -->
    <div class="flex-1 flex items-center justify-center px-8 py-12 lg:px-16">
      <div class="w-full max-w-md">
        <!-- Logo for mobile -->
        <div class="lg:hidden text-center mb-8">
          <h1 class="text-4xl font-bold mb-2" style="color: #8B4513;">Motifnesia</h1>
        </div>

        <div>
          <h2 class="text-4xl font-bold mb-2" style="color: #8B4513;">Reset Password</h2>
          <p class="text-gray-500 mb-6">Masukkan informasi untuk mengatur ulang password Anda</p>

          {{-- Display Messages --}}
          @if (session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200">
              <p class="text-red-600 text-sm">{{ session('error') }}</p>
            </div>
          @endif
          @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200">
              <p class="text-green-600 text-sm">{{ session('success') }}</p>
            </div>
          @endif

          <form action="{{ route('auth.doForgot') }}" method="POST" class="space-y-4" id="resetForm">
            @csrf
            
            <div>
              <input type="text" name="username" placeholder="Username" required
                     class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all" />
            </div>

            <div>
              <select name="secret_question" required
                      class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all text-gray-700">
                <option value="">-- Pilih Pertanyaan Rahasia --</option>
                <option value="makanan">Apa makanan favoritmu?</option>
                <option value="hewan">Apa hewan peliharaan pertamamu?</option>
                <option value="hobi">Apa hobimu?</option>
              </select>
            </div>

            <div>
              <input type="text" name="secret_answer" placeholder="Jawaban Rahasia" required
                     class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all" />
            </div>

            <div class="relative">
              <input type="password" id="newPassword" name="new_password" placeholder="Password Baru" required
                     class="w-full px-4 py-3 pr-12 bg-gray-100 border-0 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all" />
              <button type="button" onclick="togglePassword('newPassword', 'newPasswordEye')" 
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors">
                <svg id="newPasswordEye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
              </button>
            </div>

            <div class="relative">
              <input type="password" id="confirmPassword" name="new_password_confirmation" placeholder="Konfirmasi Password Baru" required
                     class="w-full px-4 py-3 pr-12 bg-gray-100 border-0 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all" />
              <button type="button" onclick="togglePassword('confirmPassword', 'confirmPasswordEye')" 
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors">
                <svg id="confirmPasswordEye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
              </button>
            </div>

            <button type="submit" 
                    class="w-full py-3 rounded-full font-bold text-white transition-all hover:shadow-lg transform hover:scale-105" 
                    style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);">
              RESET PASSWORD
            </button>
          </form>

          <!-- Mobile Login Link -->
          <div class="mt-6 text-center">
            <p class="text-gray-600 text-sm">Ingat password Anda? 
              <a href="{{ route('auth.login') }}" class="font-bold transition-link" style="color: #8B4513;" onclick="handleTransition(event, '{{ route('auth.login') }}')">Login</a>
            </p>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script>
    const form = document.getElementById("resetForm");
    form.addEventListener("submit", function (e) {
      const newPw = document.getElementById("newPassword").value;
      const confirmPw = document.getElementById("confirmPassword").value;

      if (newPw.length < 6) {
        alert("Password minimal 6 karakter!");
        e.preventDefault();
      } else if (newPw !== confirmPw) {
        alert("Konfirmasi password tidak cocok!");
        e.preventDefault();
      }
    });

    function handleTransition(event, url) {
      event.preventDefault();
      document.body.style.animation = 'fadeInUp 0.4s ease-out reverse';
      setTimeout(() => {
        window.location.href = url;
      }, 400);
    }

    function togglePassword(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
        `;
      } else {
        input.type = 'password';
        icon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        `;
      }
    }
  </script>
</body>
</html>