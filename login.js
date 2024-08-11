document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('login-form');
  const emailInput = document.getElementById('email');
  const emailError = document.getElementById('email-error');   

  const continueButton = document.querySelector('.custom-button');

  continueButton.addEventListener('click', (event) => {
    event.preventDefault(); // Evita el comportamiento por defecto del enlace

    const email = emailInput.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === '') {
      emailError.textContent = 'Por favor, ingresa una dirección de correo electrónico.';
    } else if (!emailRegex.test(email)) {
      emailError.textContent = 'Por favor, ingresa una dirección de correo electrónico válida.';
    } else {
      // Validación adicional (opcional)
      // ...

      // Redirigir solo si la validación pasa
      window.location.href = 'crearcuenta.html';
    }
  });
});