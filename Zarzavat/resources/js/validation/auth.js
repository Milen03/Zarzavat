/**
 * Валидация на формата за регистрация
 */
function setupPasswordValidation() {
    const form = document.querySelector('form');
    if (!form) return;
    
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');
    
    if (!password || !passwordConfirm) return;
    
    form.addEventListener('submit', function(event) {
        if (password.value !== passwordConfirm.value) {
            event.preventDefault();
            
            // Създаваме елемент за грешка, ако още не съществува
            let errorElement = document.getElementById('password-match-error');
            
            if (!errorElement) {
                errorElement = document.createElement('p');
                errorElement.id = 'password-match-error';
                errorElement.className = 'text-red-500 text-sm mt-1';
                errorElement.textContent = 'Паролите не съвпадат';
                
                passwordConfirm.classList.add('border-red-500');
                passwordConfirm.parentNode.appendChild(errorElement);
            }
            
            // Фокусираме полето за потвърждение на парола
            passwordConfirm.focus();
        }
    });
    
    // Премахваме съобщението за грешка при промяна на полетата
    passwordConfirm.addEventListener('input', function() {
        const errorElement = document.getElementById('password-match-error');
        if (errorElement && password.value === passwordConfirm.value) {
            errorElement.remove();
            passwordConfirm.classList.remove('border-red-500');
            passwordConfirm.classList.add('border-gray-300');
        }
    });
    
    password.addEventListener('input', function() {
        const errorElement = document.getElementById('password-match-error');
        if (errorElement && password.value === passwordConfirm.value) {
            errorElement.remove();
            passwordConfirm.classList.remove('border-red-500');
            passwordConfirm.classList.add('border-gray-300');
        }
    });
}

/**
 * Инициализиране на всички валидации
 */
document.addEventListener('DOMContentLoaded', function() {
    setupPasswordValidation();
    
  
});