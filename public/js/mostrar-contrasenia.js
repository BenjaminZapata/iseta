document.addEventListener("DOMContentLoaded", () => {
    // Busca cualquier contenedor de password, sin importar el sufijo
    const passwordWrappers = document.querySelectorAll(
        '[class*="password-wrapper"]'
    );

    passwordWrappers.forEach(wrapper => {
        const input = wrapper.querySelector('input[type="password"], input[type="text"]');
        const toggleBtn =
            wrapper.querySelector('[class*="toggle-password"] i') ||
            wrapper.querySelector('button[class*="toggle-password"] i');

        if (!input || !toggleBtn) return;

        toggleBtn.parentElement.addEventListener("click", () => {
            const isPassword = input.type === "password";
            input.type = isPassword ? "text" : "password";

            // Cambiar íconos (Tabler Icons)
            toggleBtn.classList.toggle("ti-eye", !isPassword);
            toggleBtn.classList.toggle("ti-eye-off", isPassword);
        });
    });
});
