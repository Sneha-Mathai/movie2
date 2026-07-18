// scripts.js
document.addEventListener('DOMContentLoaded', function() {
    function generateCaptcha() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let captcha = '';
        for (let i = 0; i < 4; i++) {
            captcha += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('captcha-code').textContent = captcha;
    }

    document.getElementById('refresh-captcha').addEventListener('click', function() {
        generateCaptcha();
    });

    // Generate initial CAPTCHA code
    generateCaptcha();
});
