function changeBorder(color){
    const allIcons = document.querySelectorAll(".icon");

    //reset all borders
    allIcons.forEach(icon => {
        icon.style.backgroundColor = "";
        icon.style.borderColor = "#E2E8F0";
        // #3b82f6 border-blue-500
        //#E2E8F0 border-slate-200
    });

    const iconItem = document.getElementById(color);

    iconItem.style.backgroundColor = "#EFF6FF";
    iconItem.style.borderColor = "#3b82f6";

    const selectedColor = color //database purposes
}

//code for modal functionalities (open, close, create) from readymadeui
const openBtn = document.getElementById("openModal");
const closeBtn = document.getElementById("closeModal");
const cancelBtn = document.getElementById("cancelBtn");
const overlay = document.getElementById("modalOverlay");
const dialog = overlay.querySelector("[role='dialog']");

// Open modal and lock body scroll
openBtn.onclick = () => {
    overlay.classList.remove("hidden");
    document.body.style.overflow = "hidden";
    dialog.focus();
};

// Close modal and restore focus/scroll
function closeModal() {
    overlay.classList.add("hidden");
    document.body.style.overflow = "";
    openBtn.focus();
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