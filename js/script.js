// Először hozzuk létre az Audio objektumot
var audio = new Audio('music/azi.mp3');
var playPauseButton = document.querySelector('.play-pause');
var progressBar = document.querySelector('.progress');
var currentTimeDisplay = document.querySelector('.current-time');
var totalTimeDisplay = document.querySelector('.total-time');

// A gomb állapotának frissítése és a zene lejátszása/szüneteltetése
function togglePlayPause() {
    var playPauseIcon = document.querySelector('.play-pause i');
    if (audio.paused) {
        audio.play().then(() => {
            playPauseIcon.classList.remove('fa-play');
            playPauseIcon.classList.add('fa-pause');
        }).catch(e => {
            console.error("Play failed", e);
            alert("A lejátszás nem sikerült. Kérjük, kattintson a lejátszás gombra az audio indításához.");
        });
    } else {
        audio.pause();
        playPauseIcon.classList.remove('fa-pause');
        playPauseIcon.classList.add('fa-play');
    }
}
// A zenelejátszó inicializálása és az eseménykezelők hozzáadása
function initializePlayer() {
    // Az összidő beállítása
    audio.addEventListener('loadedmetadata', function () {
        totalTimeDisplay.textContent = formatTime(audio.duration);
    });

    // Az aktuális idő frissítése
    audio.addEventListener('timeupdate', function () {
        currentTimeDisplay.textContent = formatTime(audio.currentTime);
        var progressPercentage = (audio.currentTime / audio.duration) * 100;
        progressBar.style.width = progressPercentage + '%';
    });
}

// Idő formátumát átalakító funkció
function formatTime(seconds) {
    var minutes = Math.floor(seconds / 60);
    var seconds = Math.floor(seconds % 60);
    return minutes + ':' + (seconds < 10 ? '0' + seconds : seconds);
}

// Hangerő beállítása
function setVolume(value) {
    var volume = value / 100; // Konvertálja az értéket százalékról a kívánt 0 és 1 közötti tartományra
    audio.volume = volume;
}

function toggleMute() {
    var muteIcon = document.querySelector('.volume-controls i');
    audio.muted = !audio.muted;
    if (audio.muted) {
        muteIcon.classList.remove('fa-volume-up');
        muteIcon.classList.add('fa-volume-mute');
    } else {
        muteIcon.classList.remove('fa-volume-mute');
        muteIcon.classList.add('fa-volume-up');
    }
}

// A lejátszó inicializálása amint a dokumentum betöltődik
document.addEventListener('DOMContentLoaded', initializePlayer);