document.addEventListener('DOMContentLoaded', () => {
    const signupForm = document.getElementById('signupForm');
    const username = document.getElementById('username');
    const password = document.getElementById('password');
    const confirm_password = document.getElementById('confirm_password');
    const email = document.getElementById('email');
    const terms = document.getElementById('terms');
    const signupButton = document.querySelector('.signup-button');
    const passwordMismatch = document.getElementById('passwordMismatch');
    const capsLockWarning = document.getElementById('capsLockWarning');

    function validatePasswords() {
        if (password.value !== confirm_password.value) {
            passwordMismatch.style.display = 'block';
            return false;
        } else {
            passwordMismatch.style.display = 'none';
            return true;
        }
    }

    function validateForm() {
        let passwordsAreValid = validatePasswords();
        let isFormValid = username.value && email.value &&
                          password.value && passwordsAreValid &&
                          (document.getElementById('female').checked || document.getElementById('male').checked) &&
                          terms.checked;

        signupButton.disabled = !isFormValid;
    }

    function showCapsLockWarning(e) {
        // keyCode 20 is the Caps Lock key
        var isCapsLock = e.getModifierState('CapsLock');
        capsLockWarning.style.display = isCapsLock ? 'block' : 'none';
    }

    password.addEventListener('keyup', showCapsLockWarning);
    confirm_password.addEventListener('keyup', showCapsLockWarning);
    password.addEventListener('input', validatePasswords);
    confirm_password.addEventListener('input', validatePasswords);
    signupForm.addEventListener('input', validateForm);
});
