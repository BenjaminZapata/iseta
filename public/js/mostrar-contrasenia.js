const passInput = document.getElementById("pw-input");
const toggleBtn = document.querySelector(".toggle-password i");

toggleBtn.parentElement.addEventListener("click", () => {
    const isPassword = passInput.type === "password";
    passInput.type = isPassword ? "text" : "password";
    toggleBtn.classList.toggle("ti-eye", !isPassword);
    toggleBtn.classList.toggle("ti-eye-off", isPassword);
});
