<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Account Verification</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">

  <div class="bg-white max-w-md w-full p-8 rounded-xl shadow-lg text-center" id="mainContent">
    <h1 class="text-4xl font-extrabold mb-4">Welcome, {{ $account->name }}!</h1>

    @if ($account->is_verify)
      <p class="text-green-600 text-xl font-semibold mb-4">✅ Your account is already verified.</p>
      <p class="text-gray-600">Thanks for verifying your account.</p>
    @else
      <p class="text-gray-700 mb-6">
        Please verify your account within the next <strong><span id="countdown">Loading...</span></strong> or this link will expire.
      </p>

      <button id="verifyBtn"
        class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-semibold py-3 px-6 rounded-lg transition duration-300"
        onclick="verifyAccount()"
      >
        Verify Account
      </button>
      <p id="statusMsg" class="mt-4 text-red-500 font-medium"></p>
    @endif
  </div>

  <!-- Toast -->
  <div id="toast" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-md shadow-lg opacity-0">
    ✅ Verification successful!
  </div>

  <script>
    // Simulated countdown (e.g. 30 minutes from createdAt)
    const expirationTime = new Date("{{ $account->created_at->addMinutes(30)->toISOString() }}").getTime();
    const countdownEl = document.getElementById("countdown");

    function updateCountdown() {
      const now = new Date().getTime();
      const distance = expirationTime - now;

      if (distance <= 0) {
        countdownEl.innerText = "Expired";
        document.getElementById("verifyBtn").disabled = true;
        return;
      }

      const minutes = Math.floor(distance / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);
      countdownEl.innerText = `${minutes}m ${seconds}s`;
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();

    // Verification button logic
    function verifyAccount() {
      const verifyBtn = document.getElementById("verifyBtn");
      const statusMsg = document.getElementById("statusMsg");

      verifyBtn.disabled = true;
      statusMsg.innerText = "Verifying...";

      fetch("{{ URL::signedRoute('accounts.verify', ['id' => $account->id]) }}")
        .then(res => {
          if (res.ok) return res.text();
          throw new Error("Verification failed");
        })
        .then(() => {
          document.getElementById("toast").classList.add("opacity-100");
          statusMsg.innerText = "";
          setTimeout(() => {
            window.location.reload();
          }, 2000);
        })
        .catch(() => {
          statusMsg.innerText = "Verification failed or expired.";
          verifyBtn.disabled = false;
        });
    }
  </script>
</body>
</html>
