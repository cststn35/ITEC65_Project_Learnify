async function invalidateSession(sessionID) {
  const confirmation = await Swal.fire({
    title: "Are you sure?",
    text: "This will invalidate the session and cannot be undone.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, invalidate it!",
  });
  if (!confirmation.isConfirmed) {
    return;
  }
  const response = await fetch(
    `/${BASE_URL}/actions/sessions/invalidate-session.php?sessionID=${sessionID}`,
  );
  const data = await response.json();
  if (data.success) {
    Swal.fire({
      icon: "success",
      title: "Session Invalidated",
      text: "The session has been invalidated successfully.",
    });
    setTimeout(() => {
      location.reload();
    }, 1500);
  } else {
    Swal.fire({
      icon: "error",
      title: "Failed to Invalidate Session",
      text: "Failed to invalidate session: " + (data.error || "Unknown error"),
    });
  }
}
