async function deleteTask(taskID,userID){
    const result = await Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    });

    if(result.isConfirmed){
        delete_task_db(taskID,userID);
    }
}

async function delete_task_db(taskID,userID){
    const response = await fetch(`/${BASE_URL}/actions/tasks/delete_task.php?userID=${userID}&tasks_id=${taskID}&semesterID=${semesterID}`);
    const data = await response.json();
    if(data.success){
        Swal.fire({
            icon: "success",
            title: "Deleted!",
            text: "The task has been successfully deleted"
        });
        fetch_task("from swal of delete");
    } else {
        Swal.fire({
            icon: "error",
            title: "Failed",
            text: data.error || data.message
        });
    }
}