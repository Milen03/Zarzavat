document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form[action*='checkout']");
    if (!form) return;

    form.addEventListener("submit", (e) => {
        const name = form.querySelector("input[name='name']");
        const email = form.querySelector("input[name='email']");
        const phone = form.querySelector("input[name='phone']");
        const address = form.querySelector("textarea[name='address']");

        let valid = true;
        let errors = [];

        // Име
        if (name.value.trim() === "") {
            valid = false;
            errors.push("Моля, въведете име.");
        }

        // Имейл
        if (email.value.trim() === "") {
            valid = false;
            errors.push("Моля, въведете имейл адрес.");
        } else if (!/\S+@\S+\.\S+/.test(email.value)) {
            valid = false;
            errors.push("Моля, въведете валиден имейл адрес.");
        }

        // Телефон
        if (phone.value.trim() === "") {
            valid = false;
            errors.push("Моля, въведете телефон.");
        } else if (!/^\d{10}$/.test(phone.value)) {
            valid = false;
            errors.push("Моля, въведете валиден телефонен номер с 10 цифри.");
        }

        // Адрес
        if (address.value.trim() === "") {
            valid = false;
            errors.push("Моля, въведете адрес.");
        }

        // Премахваме стари грешки
        const oldErrors = document.getElementById("frontend-errors");
        if (oldErrors) oldErrors.remove();

        if (!valid) {
            e.preventDefault();

            const errorBox = document.createElement("div");
            errorBox.id = "frontend-errors";
            errorBox.className = "bg-red-50 border-l-4 border-red-500 p-4 rounded mb-4";
            errorBox.innerHTML = `
                <div class="text-red-800 font-semibold mb-2">
                    Възникнаха следните проблеми:
                </div>
                <ul class="list-disc list-inside text-red-700 text-sm">
                    ${errors.map(msg => `<li>${msg}</li>`).join("")}
                </ul>
            `;

            form.prepend(errorBox);
            window.scrollTo({ top: form.offsetTop - 50, behavior: "smooth" });
        }
    });
});
