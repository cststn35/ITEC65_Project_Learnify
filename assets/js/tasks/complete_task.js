async function completeTask(taskID, userID) {
  const result = await Swal.fire({
    title: "Complete Task?",
    text: "Do you want to mark this task as done?",
    icon: "question",

    showCancelButton: true,

    confirmButtonText: "Yes",
    cancelButtonText: "Cancel",
  });

  if (!result.isConfirmed) return;

  const response = await fetch(
    `/${BASE_URL}/actions/tasks/complete_task.php?userID=${userID}&semesterID=${semesterID}&taskID=${taskID}`,
  );
  const data = await response.json();
  if (data.success) {
    Swal.fire({
      icon: "success",
      title: "Marked As Done!",
      text: "The task has been successfully marked as done",
    });
    fetch_task("from swal of complete task");
  }
}
