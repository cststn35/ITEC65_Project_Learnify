async function deleteTask(taskID, userID) {
  const result = await Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  });

  if (result.isConfirmed) {
    delete_task_db(taskID, userID);
  }
}

async function delete_task_db(taskID, userID) {
  const response = await fetch(
    `/${BASE_URL}/actions/tasks/delete_task.php?userID=${userID}&tasks_id=${taskID}&semesterID=${semesterID}`,
  );
  const data = await response.json();
  if (data.success) {
    //log first the xp, deduction of xp
    let xp_earned = -2;
    const fd2 = new FormData();
    fd2.append("userID", userID);
    fd2.append("semesterID", semesterID);
    fd2.append("reason", "TASK_DELETED");
    fd2.append("xp", xp_earned);
    await fetch(`/${BASE_URL}/actions/log_xp.php`, {
      method: "POST",
      body: fd2,
    });

    Swal.fire({
      icon: "success",
      title: "Deleted!",
      text: "The task has been successfully deleted",
    });
    triggerFilter();
  } else {
    Swal.fire({
      icon: "error",
      title: "Failed",
      text: data.error || data.message,
    });
  }
}
