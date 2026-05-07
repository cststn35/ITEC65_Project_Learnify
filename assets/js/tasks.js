const deadlineInput = document.getElementById("deadline");

//prevents user from picking past dates for deadline
const today = new Date().toISOString().split("T")[0];
deadlineInput.min = today;

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

//dropdown menu logic

document.addEventListener('click', function (e) {

    const btn = e.target.closest('[data-action="dropdown-toggle"]'); //gets the button that was clicked. e.target could be the icon or the button itself. we should grab hold the button

    // If clicking the button
    if (btn) {
        const card = btn.closest('.one-card'); //get the parent html of the button, a.k.a the card; returns an html
        const dropdown = card.querySelector('[data-dropdown]'); //from the card itself only, find the dropdown; returns an html

        // close others first
        document.querySelectorAll('[data-dropdown]').forEach(d => {
            if (d !== dropdown) d.classList.add('hidden');
            //in each dropdown, we close that doesnt match the current dropdown to be opened. dropd
        });

        dropdown.classList.toggle('hidden');
        return;
    }

    // If clicking outside
    document.querySelectorAll('[data-dropdown]').forEach(d => {
        d.classList.add('hidden');
    });

});
