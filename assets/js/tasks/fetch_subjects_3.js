async function fetchSubjects(userID) {
  console.log(userID);
  const subjectInput = document.getElementById("subject");
  try {
    const response = await fetch(
      `/${BASE_URL}/actions/tasks/fetch_subjects.php?userID=${userID}&semester_id=${semesterID}`,
    );

    // check HTTP status first
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();

    if (data.success) {
      subjectInput.innerHTML =
        '<option value="" disabled hidden selected>Select a subject</option>';

      data.data.forEach((subject) => {
        subjectInput.innerHTML += `<option value="${subject.subject_id}">${subject.name}</option>`;
      });
    } else {
      console.log("Server returned error:", data.error || "Unknown error");
    }
  } catch (error) {
    console.error("Fetch failed:", error);
  }
}

fetchSubjects(userID);
