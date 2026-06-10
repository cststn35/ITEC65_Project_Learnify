async function changeName(userID) {
  const response = await Swal.fire({
    title: "Change Name",
    text: "Are you sure you want to change your name?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, change it!",
    cancelButtonText: "No, keep it",
  });

  if (!response.isConfirmed) {
    return;
  }

  const firstName = document.getElementById("first_name").value.trim();
  const lastName = document.getElementById("last_name").value.trim();

  const formData = new FormData();
  formData.append("user_id", userID);
  formData.append("first_name", firstName);
  formData.append("last_name", lastName);

  if (!firstName || !lastName) {
    Swal.fire({
      title: "Error",
      text: "Please enter both first and last names.",
      icon: "error",
      confirmButtonText: "OK",
    });
    return;
  }

  try {
    const response = await fetch("../components/change-name.php", {
      method: "POST",
      body: formData,
    });
    const result = await response.json();
    if (result.success) {
      Swal.fire({
        title: "Success",
        text: result.message,
        icon: "success",
        confirmButtonText: "OK",
      });
      setTimeout(() => {
        location.reload();
      }, 1000);
    } else {
      Swal.fire({
        title: "Error",
        text: "Failed to update name. Please try again.",
        icon: "error",
        confirmButtonText: "OK",
      });
    }
  } catch (error) {
    console.error("Error:", error);
    Swal.fire({
      title: "Error",
      text: "An error occurred while updating the name. Please try again.",
      icon: "error",
      confirmButtonText: "OK",
    });
  }
}
