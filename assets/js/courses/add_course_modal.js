let selectedColor = "";
function changeBorder(color){
    const allIcons = document.querySelectorAll(".icon");

    //reset all borders
    allIcons.forEach(icon => {
        icon.style.backgroundColor = "";
        icon.style.borderColor = "#E2E8F0";
    });

    const iconItem = document.getElementById(color);

    iconItem.style.backgroundColor = "#EFF6FF";
    iconItem.style.borderColor = "#3b82f6";

    selectedColor = color //database purposes
}

//code for modal functionalities (open, close, create) from readymadeui
const openBtn = document.getElementById("openModal");
const closeBtn = document.getElementById("closeModal");
const cancelBtn = document.getElementById("cancelBtn");
const overlay = document.getElementById("modalOverlay");
const dialog = overlay.querySelector("[role='dialog']");

// Open modal and lock body scroll
openBtn.onclick = () => {
    overlay.classList.remove("opacity-0");
    overlay.classList.remove("pointer-events-none");
    overlay.classList.remove("scale-95");
    document.body.style.overflow = "hidden";
    dialog.focus();
};

// Close modal and restore focus/scroll
function closeModal() {
    overlay.classList.add("opacity-0");
    overlay.classList.add("pointer-events-none");
    overlay.classList.add("scale-95");
    document.body.style.overflow = "";
    openBtn.focus();
    resetFormAppearance();
}

closeBtn.onclick = cancelBtn.onclick = closeModal;

// Close when clicking outside the dialog
overlay.onclick = (e) => {
    if (e.target === overlay) closeModal();
};

// Keyboard accessibility
document.addEventListener("keydown", (e) => {
    if (overlay.classList.contains("hidden")) return;

    // Close on ESC
    if (e.key === "Escape") closeModal();

    // Focus trapping logic
    if (e.key === "Tab") {
        const focusable = dialog.querySelectorAll("button, [href], input, select, textarea, [tabindex]:not([tabindex='-1'])");
        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (e.shiftKey && document.activeElement === first) {
        e.preventDefault();
        last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault();
        first.focus();
        }
    }
});

//js for adding course
const courseForm = document.getElementById("course-form-container");

courseForm.addEventListener("submit",submitCreatedCourse);

async function submitCreatedCourse(e){
    // stop normal form submission first
    e.preventDefault();
    if(selectedColor==""){
        Swal.fire({
            icon: "error",
            title: "Missing field!",
            text: "Don't forget to select icon color"
        });
        return;
    }

    // confirmation alert
    const result = await Swal.fire({
        title: "Create Subject",
        text: "Do you want to save this subject?",
        icon: "question",

        showCancelButton: true,

        confirmButtonText: "Yes",
        cancelButtonText: "Cancel"
    });

    // if user clicks yes
    if (result.isConfirmed) {
        const formData = new FormData(courseForm);
        formData.append('color',selectedColor);
        const response = await fetch(`/${BASE_URL}/actions/courses/create_courses.php?userID=${userID}&semesterID=${semesterID}`,{
            method: "POST",
            body: formData
        });
        const data = await response.json();
        if(data.success){
            Swal.fire({
                icon: "success",
                title: "Created!",
                text: "The subject has been successfully created"
            });

            fetch_courses("from swal");
            courseForm.reset();
        } else {
            Swal.fire({
                icon: "error",
                title: "Failed",
                text: data.error
            });
        }
    }
}