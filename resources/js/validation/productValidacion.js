document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form[action*='admin/products']");
    if (!form) return;

    form.addEventListener("submit", (e) => {
        const name = form.querySelector("input[name='name']");
        const price = form.querySelector("input[name='price']");
        const stock = form.querySelector("input[name='stock']");
        const category = form.querySelector("select[name='category_id']");
        const image = form.querySelector("input[name='image']");

        let valid = true;
        let errors = [];

        // Име
        if (name.value.trim() === "") {
            valid = false;
            errors.push("Моля, въведете име на продукта.");
        } else if (name.value.length < 3) {
            valid = false;
            errors.push("Името трябва да е поне 3 символа.");
        }

        // Цена
        if (price.value.trim() === "") {
            valid = false;
            errors.push("Моля, въведете цена на продукта.");
        } else if (parseFloat(price.value) <= 0) {
            valid = false;
            errors.push("Цената трябва да бъде положително число.");
        }

        // Количество
        if (stock.value.trim() === "") {
            valid = false;
            errors.push("Моля, въведете количество.");
        } else if (parseInt(stock.value) < 0) {
            valid = false;
            errors.push("Количеството не може да бъде отрицателно.");
        }

        // Категория
        if (category.value === "") {
            valid = false;
            errors.push("Моля, изберете категория.");
        }

        // Изображение (ако желаеш задължително)
        if (!image.files.length) {
            valid = false;
            errors.push("Моля, качете изображение на продукта.");
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
