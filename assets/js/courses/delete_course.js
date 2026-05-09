async function deleteCourse(courseID,userID,semesterID){
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
        delete_course_db(courseID,userID,semesterID);
    }
}

async function delete_course_db(courseID,userID,semesterID){
    const response = await fetch(`/${BASE_URL}/actions/courses/delete_course.php?userID=${userID}&course_id=${courseID}&semester_id=${semesterID}`);
    const data = await response.json();
    if(data.success){
        Swal.fire({
            icon: "success",
            title: "Deleted!",
            text: "The subject has been successfully deleted"
        });
        fetch_courses("from swal of delete");
    } else {
        Swal.fire({
            icon: "error",
            title: "Failed",
            text: data.error || data.message
        });
    }
}