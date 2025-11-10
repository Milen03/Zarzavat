document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form[action*='login']");
    if (!form) return;

    form.addEventListener("submit", (e) => {
        const email = form.querySelector("input[name='email']");
        const password = form.querySelector("input[name='password']");
        let valid = true;
        let messages = [];

        // Валидирай имейл
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email.value.trim() === "") {
            valid = false;
            messages.push("Моля, въведете имейл адрес.");
        } else if (!emailRegex.test(email.value.trim())) {
            valid = false;
            messages.push("Въведеният имейл адрес е невалиден.");
        }

        // Валидирай парола
        if (password.value.trim() === "") {
            valid = false;
            messages.push("Моля, въведете парола.");
        } else if (password.value.length < 6) {
            valid = false;
            messages.push("Паролата трябва да бъде поне 6 символа.");
        }

        // Ако има грешки
        const errorContainer = document.querySelector("#frontend-errors");
        if (errorContainer) errorContainer.remove();

        if (!valid) {
            e.preventDefault();

            const errorBox = document.createElement("div");
            errorBox.id = "frontend-errors";
            errorBox.className = "bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200";
            errorBox.innerHTML = `
                <ul class="list-disc pl-5">
                    ${messages.map(msg => `<li>${msg}</li>`).join("")}
                </ul>
            `;
            form.prepend(errorBox);
        }
    });
});
