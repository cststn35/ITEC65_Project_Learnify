console.log("hello world");
const profilePic = document.getElementById("profilePic");
const profilePreview = document.getElementById("profilePreview");
const profilePicForm = document.getElementById("profilePicForm");
const updateProfilePicBtn = document.getElementById("updateProfilePicBtn");

profilePic.addEventListener("change", function () {
  const file = this.files[0];

  if (!file) return;

  profilePreview.src = URL.createObjectURL(file);
});

profilePicForm.addEventListener("submit", async function (e) {
  e.preventDefault();

  updateProfilePicBtn.disabled = true;
  updateProfilePicBtn.textContent = "Updating...";

  try {
    const formData = new FormData(this);

    const response = await fetch("../components/update-profile-picture.php", {
      method: "POST",
      body: formData,
    });

    const data = await response.json();

    Swal.fire({
      icon: "success",
      title: "Created!",
      text: "The profile picture has been successfully changed",
    });
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Failed!",
      text: "There was an error changing the profile picture",
    });
  } finally {
    updateProfilePicBtn.disabled = false;
    updateProfilePicBtn.textContent = "Update Picture";
  }
});
