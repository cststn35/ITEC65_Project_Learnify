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

const passToggle = document.getElementById("passToggle");
const logpass = document.getElementById("loginpassword"); //input

const regpass1 = document.getElementById("regpassword1"); //input
const regpass2 = document.getElementById("regpassword2"); //input
const passToggle2 = document.getElementById("passToggle2");
const passToggle3 = document.getElementById("passToggle3");


passToggle.addEventListener("click",()=>{
    if(passToggle.classList.contains("fa-eye-slash")){
        passToggle.classList.remove("fa-eye-slash");
        passToggle.classList.add("fa-eye");
        logpass.type = "text";
    } else {
        passToggle.classList.add("fa-eye-slash");
        passToggle.classList.remove("fa-eye");
        logpass.type = "password";
    }
});

passToggle2.addEventListener("click",()=>{
    if(passToggle2.classList.contains("fa-eye-slash")){
        passToggle2.classList.remove("fa-eye-slash");
        passToggle2.classList.add("fa-eye");
        regpass1.type = "text";
    } else {
        passToggle2.classList.add("fa-eye-slash");
        passToggle2.classList.remove("fa-eye");
        regpass1.type = "password";
    }
});

passToggle3.addEventListener("click",()=>{
    if(passToggle3.classList.contains("fa-eye-slash")){
        passToggle3.classList.remove("fa-eye-slash");
        passToggle3.classList.add("fa-eye");
        regpass2.type = "text";
    } else {
        passToggle3.classList.add("fa-eye-slash");
        passToggle3.classList.remove("fa-eye");
        regpass2.type = "password";
    }
});
