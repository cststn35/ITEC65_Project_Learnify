const deadlineInput = document.getElementById("deadline");

//prevents user from picking past dates for deadline
const today = new Date().toISOString().split("T")[0];
deadlineInput.min = today;
