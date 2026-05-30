document.querySelectorAll(".toggle-password").forEach((btn) => {
  btn.addEventListener("click", function () {
    const targetId = this.getAttribute("data-target");
    const input = document.getElementById(targetId);

    if (!input) return;

    const isPassword = input.type === "password";

    input.type = isPassword ? "text" : "password";

    // optional icon switch
    this.textContent = isPassword ? "🙈" : "👁️";
  });
});

const changePasswordBtn = document.getElementById("change-pass-button");
const passwordError = document.querySelector(".password-error");

changePasswordBtn.addEventListener("click", async function () {
  const current_password = document.getElementById("current_password").value;
  const new_password = document.getElementById("new_password").value;
  const confirm_password = document.getElementById("confirm_password").value;

  passwordError.textContent = "";

  if (!current_password || !new_password || !confirm_password) {
    passwordError.textContent = "Please fill in all password fields.";
    return;
  }

  if (new_password !== confirm_password) {
    passwordError.textContent = "New passwords do not match.";
    return;
  }

  if (new_password.length < 6) {
    passwordError.textContent = "Password must be at least 6 characters.";
    return;
  }

  try {
    const res = await fetch("../components/change-password.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        current_password,
        new_password,
        confirm_password,
      }),
    });

    const data = await res.json();

    if (!data.success) {
      passwordError.textContent = data.message;
      return;
    }

    Swal.fire({
      icon: "success",
      title: "Password Changed!",
      text: "The password has been successfully changed",
    });

    document.getElementById("current_password").value = "";
    document.getElementById("new_password").value = "";
    document.getElementById("confirm_password").value = "";
  } catch (err) {
    passwordError.textContent = "Something went wrong.";
  }
});
