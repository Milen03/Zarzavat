document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const name = document.getElementById("name");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("password_confirmation");

    form.addEventListener("submit", function (e) {
        let valid = true;
        clearErrors();

        
        if (name.value.trim().length < 3) {
            showError(name, "Името трябва да съдържа поне 3 символа");
            valid = false;
        }

       
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email.value.trim())) {
            showError(email, "Моля, въведете валиден имейл адрес");
            valid = false;
        }

        if (password.value.length < 6) {
            showError(password, "Паролата трябва да е поне 6 символа");
            valid = false;
        }

        
        if (password.value !== confirmPassword.value) {
            showError(confirmPassword, "Паролите не съвпадат");
            valid = false;
        }

        
        if (!valid) {
            e.preventDefault();
        }
    });

    // Clear previous errors
    function clearErrors() {
        document.querySelectorAll(".text-red-500").forEach(el => el.remove());
        [name, email, password, confirmPassword].forEach(input => {
            input.classList.remove("border-red-500");
            input.classList.add("border-gray-300");
        });
    }

    // Show error message
    function showError(input, message) {
        input.classList.remove("border-gray-300");
        input.classList.add("border-red-500");

        const error = document.createElement("p");
        error.classList.add("text-red-500", "text-sm", "mt-1");
        error.innerText = message;
        input.insertAdjacentElement("afterend", error);
    }
});
