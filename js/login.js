function goto(path){
    window.location.href = path + '.php';
}

document.addEventListener('DOMContentLoaded', function() {
    var message = document.getElementById('message');
    if (message) {
        message.style.display = 'block';
        message.style.opacity = 1;
        setTimeout(function() {
            // Fokozatos eltűnés
            var fadeEffect = setInterval(function () {
                if (!message.style.opacity) {
                    message.style.opacity = 1;
                }
                if (message.style.opacity > 0) {
                    message.style.opacity -= 0.1;
                } else {
                    clearInterval(fadeEffect);
                    message.style.display = 'none';
                }
            }, 50);
        }, 5000); // 5 másodperc után elkezd eltűnni
    }
});