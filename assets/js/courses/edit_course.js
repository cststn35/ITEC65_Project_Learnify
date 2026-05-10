let course_id;
let user_id;

function editCourse(courseID,userID,semesterID){
    course_id = courseID;
    user_id = userID;
    fetch_course_edit(courseID,userID,semesterID);
}

async function fetch_course_edit(courseID,userID,semesterID){
    const response = await fetch(`/${BASE_URL}/actions/courses/fetch_courses.php?userID=${userID}&course_id=${courseID}&semesterID=${semesterID}`);
    const data = await response.json();
    if(data.success){
        openEditModal(data.data[0]);
    }
}

const modalOverlay = document.querySelector("#modalOverlay");
const courseFormContainer = document.querySelector(".course-form-container");
const formLogo = courseFormContainer.querySelector("#modal-title i");
const formHeading = courseFormContainer.querySelector("#modal-title span");
const button = courseFormContainer.querySelector("#course-submit");

const formContainer = courseFormContainer.querySelector("form");
const titleInput = courseFormContainer.querySelector(".courseInput input");
const descriptionInput = courseFormContainer.querySelector(".descriptionInput input");

function openEditModal(data){
    //obtaining the values needed for the form
    console.log("trigger");
    const title = data.name;
    const desc = data.description;
    const color = data.color;

    //edit the contents first, setting create course modal into edit course modal
    modalOverlay.classList.remove("opacity-0");
    modalOverlay.classList.remove("pointer-events-none");
    modalOverlay.classList.remove("scale-95");
    formHeading.textContent = "Edit Subject";
    formLogo.classList.replace('bxs-plus-square','bx-edit');
    button.textContent = "Edit Subject";
    formContainer.removeEventListener("submit",submitCreatedCourse) //to prevent the create task event listener from being called
    formContainer.addEventListener("submit",submitEditedCourse);

    //injecting the obtained values
    titleInput.value = title;
    descriptionInput.value = desc;
    changeBorder(color); //for color selection
}

async function submitEditedCourse(e){
    // stop normal form submission first
    e.preventDefault();

    //confirmation alert
    const result = await Swal.fire({
        title: "Edit Subject",
        text: "Do you want to edit this subject?",
        icon: "question",

        showCancelButton: true,

        confirmButtonText: "Yes",
        cancelButtonText: "Cancel"
    });

    //if user clicks yes
    if (result.isConfirmed) {
        const formData = new FormData(formContainer);
        formData.append('color',selectedColor);
        const response = await fetch(`/${BASE_URL}/actions/courses/edit_course.php?userID=${user_id}&course_id=${course_id}&semester_id=${semesterID}`,{
            method: "POST",
            body: formData
        });
        const data = await response.json();
        if(data.success){
            Swal.fire({
                icon: "success",
                title: "Edited!",
                text: "The task has been successfully edited"
            });
            fetch_courses("from swal of edit");
                modalOverlay.classList.add("opacity-0");
                modalOverlay.classList.add("pointer-events-none");
                modalOverlay.classList.add("scale-95");
            resetFormAppearance();
        } else {
            Swal.fire({
                icon: "error",
                title: "Failed",
                text: data.error || data.message
            });
        }
    }
}

function resetFormAppearance(){
    formContainer.reset();
    formHeading.textContent = "Add New Subject";
    formLogo.classList.replace('bx-edit','bxs-plus-square');
    button.textContent = "Create Subject";
    formContainer.removeEventListener("submit",submitEditedCourse);
    formContainer.addEventListener("submit",submitCreatedCourse); //add the listener back to create course
}