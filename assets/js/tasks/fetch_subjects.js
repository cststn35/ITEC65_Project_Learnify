const BASE_URL = window.location.pathname.split("/")[1];

async function fetchSubjects(userID) {
    console.log(userID);
    const subjectInput = document.getElementById("subject");
    try {
        const response = await fetch(`/${BASE_URL}/actions/tasks/fetch_subjects.php?userID=${userID}`);

        // check HTTP status first
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            console.log(data.data);

            data.data.forEach(subject=>{
                subjectInput.innerHTML += 
                `<option value="${subject.subject_id}">${subject.name}</option>`;
            });


        } else {
            console.log("Server returned error:", data.error || "Unknown error");
        }

    } catch (error) {
        console.error("Fetch failed:", error);
    }
}

fetchSubjects(userID);