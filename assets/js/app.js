/**
 * Proyecto: Centro Educativo ISW-306 - Etapa 2: Interactividad
 * Estudiante: Pedro Starlin Ureña Cruz
 *
 *  JavaScript del Lado del Cliente:
 *  Validaciones en tiempo real: required, email, minLength, pattern, match
 *  Manipulación DOM: querySelector, classList, innerHTML, createElement
 *  Persistencia: localStorage para recuperar email de sesión anterior
 *  Eventos: submit (validación), blur/input (feedback), click (toggle password)
 *  Código limpio: funciones modulares, nombres descriptivos, sin duplicación
 *  Accesibilidad: aria-invalid, role="alert", focus management
 *
 * @format
 */

(function () {
  'use strict';

  // Esperar DOM listo
  document.addEventListener('DOMContentLoaded', init);

  function init() {
    // Inicializar validaciones en formularios con class="form-validate"
    const forms = document.querySelectorAll('form.form-validate');
    forms.forEach(initFormValidation);

    // Toggle mostrar/ocultar contraseña
    initPasswordToggle();

    // Validación en tiempo real de confirmación de contraseña
    initPasswordMatch();

    // Persistencia: recuperar email en login
    restoreSavedEmail();

    // Mensajes de alerta con auto-cierre
    initAutoCloseAlerts();
  }

  // ========== VALIDACIÓN DE FORMULARIOS ==========

  function initFormValidation(form) {
    const inputs = form.querySelectorAll(
      'input[required], select[required], textarea[required]',
    );

    // Validar al perder foco (blur)
    inputs.forEach((input) => {
      input.addEventListener('blur', () => validateField(input));
      input.addEventListener('input', () => clearError(input));
    });

    // Validar al enviar
    form.addEventListener('submit', function (e) {
      let isValid = true;

      inputs.forEach((input) => {
        if (!validateField(input)) {
          isValid = false;
        }
      });

      // Validaciones específicas
      const email = form.querySelector('input[type="email"]');
      if (email && !isValidEmail(email.value)) {
        setError(email, 'Ingresa un correo electrónico válido');
        isValid = false;
      }

      const password = form.querySelector('input[name="password"]');
      if (password && password.value.length < 6) {
        setError(password, 'Mínimo 6 caracteres requeridos');
        isValid = false;
      }

      if (!isValid) {
        e.preventDefault();
        showAlert('Por favor corrige los errores marcados', 'error');
        // Focus al primer campo con error
        const firstError = form.querySelector('.input-error');
        if (firstError) firstError.focus();
        return;
      }

      // Guardar datos para persistencia (solo si es válido)
      saveFormData(form);
    });
  }

  function validateField(input) {
    const value = input.value.trim();

    // Campo requerido vacío
    if (input.hasAttribute('required') && !value) {
      setError(input, 'Este campo es obligatorio');
      return false;
    }

    // Longitud mínima
    const minLength = input.getAttribute('minlength');
    if (minLength && value.length < parseInt(minLength)) {
      setError(input, `Mínimo ${minLength} caracteres requeridos`);
      return false;
    }

    // Email válido
    if (input.type === 'email' && value && !isValidEmail(value)) {
      setError(input, 'Formato de correo no válido');
      return false;
    }

    // Teléfono válido (si tiene pattern)
    if (input.type === 'tel' && input.hasAttribute('pattern') && value) {
      const pattern = new RegExp(input.getAttribute('pattern'));
      if (!pattern.test(value)) {
        setError(input, 'Formato de teléfono no válido');
        return false;
      }
    }

    clearError(input);
    return true;
  }

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function setError(input, message) {
    input.classList.add('input-error');
    input.setAttribute('aria-invalid', 'true');

    // Crear o actualizar mensaje de error
    let errorEl = input.parentElement.querySelector('.error-message');
    if (!errorEl) {
      errorEl = document.createElement('small');
      errorEl.className = 'error-message';
      errorEl.setAttribute('role', 'alert');
      input.parentElement.appendChild(errorEl);
    }
    errorEl.textContent = message;
  }

  function clearError(input) {
    input.classList.remove('input-error');
    input.removeAttribute('aria-invalid');
    const errorEl = input.parentElement.querySelector('.error-message');
    if (errorEl) errorEl.remove();
  }

  // ========== TOGGLE CONTRASEÑA ==========

  function initPasswordToggle() {
    document
      .querySelectorAll('input[type="password"]')
      .forEach((passwordField) => {
        // Crear botón toggle
        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.className = 'password-toggle';
        toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
        toggleBtn.setAttribute('aria-label', 'Mostrar contraseña');
        toggleBtn.setAttribute('tabindex', '-1');

        // Insertar después del campo
        passwordField.parentNode.style.position = 'relative';
        passwordField.style.paddingRight = '40px';
        toggleBtn.style.cssText =
          'position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#666;cursor:pointer;padding:4px;z-index:2;';

        passwordField.parentNode.insertBefore(
          toggleBtn,
          passwordField.nextSibling,
        );

        // Evento click
        toggleBtn.addEventListener('click', function () {
          const isPassword = passwordField.type === 'password';
          passwordField.type = isPassword ? 'text' : 'password';
          this.innerHTML = isPassword
            ? '<i class="fas fa-eye-slash"></i>'
            : '<i class="fas fa-eye"></i>';
          this.setAttribute(
            'aria-label',
            isPassword ? 'Ocultar contraseña' : 'Mostrar contraseña',
          );
        });
      });
  }

  // ========== CONFIRMACIÓN DE CONTRASEÑA ==========

  function initPasswordMatch() {
    const password = document.getElementById('password');
    const confirm = document.getElementById('password_confirm');
    const matchMsg = document.getElementById('password-match');

    if (!password || !confirm) return;

    function checkMatch() {
      if (!confirm.value) {
        if (matchMsg) {
          matchMsg.textContent = '';
          matchMsg.className = 'form-help';
        }
        return;
      }

      if (password.value === confirm.value) {
        if (matchMsg) {
          matchMsg.textContent = 'Las contraseñas coinciden';
          matchMsg.className = 'form-help success';
        }
        confirm.setCustomValidity('');
      } else {
        if (matchMsg) {
          matchMsg.textContent = 'Las contraseñas no coinciden';
          matchMsg.className = 'form-help error';
        }
        confirm.setCustomValidity('Las contraseñas no coinciden');
      }
    }

    password.addEventListener('input', checkMatch);
    confirm.addEventListener('input', checkMatch);
  }

  // ========== PERSISTENCIA CON LOCALSTORAGE ==========

  function saveFormData(form) {
    const email = form.querySelector('input[name="email"]');
    if (email && email.value) {
      const data = {
        email: email.value,
        savedAt: Date.now(),
      };
      localStorage.setItem('centroedu_last_email', JSON.stringify(data));
    }
  }

  function restoreSavedEmail() {
    // Solo en página de login
    if (!window.location.href.includes('page=login')) return;

    try {
      const saved = localStorage.getItem('centroedu_last_email');
      if (saved) {
        const data = JSON.parse(saved);
        // Recuperar si fue guardado en las últimas 7 días
        if (Date.now() - data.savedAt < 7 * 24 * 60 * 60 * 1000) {
          const emailField = document.getElementById('email');
          if (emailField && !emailField.value) {
            emailField.value = data.email;
          }
        }
      }
    } catch (e) {
      console.warn('No se pudo recuperar email guardado:', e);
    }
  }

  // ========== ALERTAS DINÁMICAS ==========

  function showAlert(message, type = 'info') {
    // Verificar si ya existe una alerta del mismo tipo
    const existing = document.querySelector(`.alert-${type}`);
    if (existing) existing.remove();

    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible`;
    alert.setAttribute('role', 'alert');
    alert.innerHTML = `
            <i class="fas ${getAlertIcon(type)}"></i>
            <span>${message}</span>
            <button type="button" class="alert-close" aria-label="Cerrar">&times;</button>
        `;

    // Insertar al inicio del main
    const main = document.querySelector('main.container');
    if (main && main.firstChild) {
      main.insertBefore(alert, main.firstChild);
    }

    // Evento cerrar
    alert.querySelector('.alert-close').addEventListener('click', () => {
      alert.style.opacity = '0';
      alert.style.transform = 'translateY(-10px)';
      setTimeout(() => alert.remove(), 300);
    });

    return alert;
  }

  function getAlertIcon(type) {
    const icons = {
      success: 'fa-check-circle',
      error: 'fa-exclamation-triangle',
      warning: 'fa-exclamation-circle',
      info: 'fa-info-circle',
    };
    return icons[type] || icons.info;
  }

  function initAutoCloseAlerts() {
    // Cerrar alertas existentes después de 5 segundos
    document
      .querySelectorAll('.alert:not(.alert-dismissible)')
      .forEach((alert) => {
        setTimeout(() => {
          alert.style.opacity = '0';
          setTimeout(() => alert.remove(), 300);
        }, 6000);
      });
  }
})();
