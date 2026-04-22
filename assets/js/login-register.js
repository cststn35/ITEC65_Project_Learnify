const buttones = document.querySelectorAll(".clickme");
const kaliwa = document.getElementById("overlay");
const login = document.querySelector(".login-wrapper");
const reg = document.querySelector(".reg-wrapper");

buttones.forEach((button) => {
    button.addEventListener("click", () => {
        kaliwa.classList.toggle("active");
        login.classList.toggle("active");
        reg.classList.toggle("active");
    });
});