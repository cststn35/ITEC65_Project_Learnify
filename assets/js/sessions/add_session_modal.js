let selectedAnswer = "";
let selectedNumber = "";
function changeBorder(choice) {
  console.log(choice);
  const allChoices = document.querySelectorAll(".answer");
  const uploadInput = document.querySelector(".uploadInput");

  //reset all borders
  allChoices.forEach((choice) => {
    choice.classList.remove("border-blue-400", "bg-blue-100");
    choice.classList.add("border-slate-300");
    const innerDiv = choice.querySelector("div");
    const circle = innerDiv.querySelector("div");
    innerDiv.classList.remove("border");
    innerDiv.classList.add("bg-black");
    circle.classList.add("hidden");
    uploadInput.classList.add("hidden");
  });

  const choiceItem = document.querySelector(choice);

  choiceItem.classList.add("border-blue-400", "bg-blue-100");
  choiceItem.classList.remove("border-slate-300");
  const innerDiv = choiceItem.querySelector("div");
  const circle = innerDiv.querySelector("div");
  innerDiv.classList.add("border");
  innerDiv.classList.remove("bg-black");
  circle.classList.remove("hidden");

  if (choice == ".yes-answer") {
    uploadInput.classList.remove("hidden");
  }

  selectedAnswer = choice; //database purposes
}

const fileInput = document.querySelector(".file-input");
const uploadText = document.querySelector(".upload-text");
const selectedFile = document.querySelector(".selected-file");
const uploadStatus = document.querySelector(".upload-status");

function triggerUpload() {
  if (!selectedNumber == "") {
    fileInput.click();
  } else {
    Swal.fire({
      icon: "error",
      title: "Missing field!",
      text: "Don't forget to select number of questions",
    });
  }
}

const generationStatus = document.querySelector(".generation-status");

fileInput.addEventListener("change", () => {
  if (fileInput.files.length > 0) {
    console.log("File uploaded");
    uploadText.textContent = "Change file";
    generationStatus.textContent = "Loading...";
    const fileName = fileInput.files[0].name;
    uploadStatus.classList.remove("hidden");
    selectedFile.textContent = fileName;
    uploadFilePHP();
  }
});

function chooseNumber(number) {
  const allNumbers = document.querySelectorAll(".number");

  allNumbers.forEach((number) => {
    number.classList.remove("bg-blue-500", "border-blue-500", "text-white");
    number.classList.add("border-slate-300", "bg-white");
  });

  const chosenNumber = document.querySelector("." + number);
  chosenNumber.classList.remove("border-slate-300", "bg-white");
  chosenNumber.classList.add("bg-blue-500", "border-blue-500", "text-white");

  selectedNumber = number;
}

async function uploadFilePHP() {
  const file = fileInput.files[0];
  const formData = new FormData();
  formData.append("file", file);
  formData.append("count", selectedNumber);

  try {
    const response = await fetch(
      `/${BASE_URL}/actions/sessions/quiz-generate.php`,
      {
        method: "POST",
        body: formData,
      },
    );
    const data = await response.json();
    console.log(data);
    if (data.success) {
      generationStatus.textContent = "Successful!";
    } else {
      generationStatus.textContent = "Failed! Try again";
    }
  } catch (error) {
    console.error("Upload failed:", error);
    generationStatus.textContent = "Failed! Try again";
  }
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
    const focusable = dialog.querySelectorAll(
      "button, [href], input, select, textarea, [tabindex]:not([tabindex='-1'])",
    );
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

// //js for adding course
// const courseForm = document.getElementById("course-form-container");

// courseForm.addEventListener("submit",submitCreatedCourse);

// async function submitCreatedCourse(e){
//     // stop normal form submission first
//     e.preventDefault();
//     if(selectedColor==""){
//         Swal.fire({
//             icon: "error",
//             title: "Missing field!",
//             text: "Don't forget to select icon color"
//         });
//         return;
//     }

//     // confirmation alert
//     const result = await Swal.fire({
//         title: "Create Subject",
//         text: "Do you want to save this subject?",
//         icon: "question",

//         showCancelButton: true,

//         confirmButtonText: "Yes",
//         cancelButtonText: "Cancel"
//     });

//     // if user clicks yes
//     if (result.isConfirmed) {
//         const formData = new FormData(courseForm);
//         formData.append('color',selectedColor);
//         const response = await fetch(`/${BASE_URL}/actions/courses/create_courses.php?userID=${userID}&semesterID=${semesterID}`,{
//             method: "POST",
//             body: formData
//         });
//         const data = await response.json();
//         if(data.success){
//             Swal.fire({
//                 icon: "success",
//                 title: "Created!",
//                 text: "The subject has been successfully created"
//             });

//             fetch_courses("from swal");
//             courseForm.reset();
//         } else {
//             Swal.fire({
//                 icon: "error",
//                 title: "Failed",
//                 text: data.error
//             });
//         }
//     }
// }
