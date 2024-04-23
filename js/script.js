function togglePlayPause() {
    var playPauseIcon = document.querySelector('.play-pause i');
    if (audio.paused || audio.ended) {
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

document.addEventListener('DOMContentLoaded', function () {
    initializePlayer();
    var playPauseButton = document.querySelector('.play-pause');
    playPauseButton.addEventListener('click', togglePlayPause);
});

function initializePlayer() {
    // A zene állapotának és időjelzések frissítése
    audio.addEventListener('loadedmetadata', function () {
        document.querySelector('.total-time').textContent = formatTime(audio.duration);
    });

    audio.addEventListener('timeupdate', function () {
        document.querySelector('.current-time').textContent = formatTime(audio.currentTime);
        var progressPercentage = (audio.currentTime / audio.duration) * 100;
        document.querySelector('.progress').style.width = progressPercentage + '%';
    });
}

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

function playAudio(button) {
    var file = button.getAttribute('data-file');
    var title = button.getAttribute('data-title');
    var artist = button.getAttribute('data-artist');
    var image = button.getAttribute('data-image');

    // Frissítsd az UI elemeket
    document.querySelector('.track-artwork img').src = image;
    document.querySelector('.track-title').textContent = title;
    document.querySelector('.track-artist').textContent = artist;

    // Zene beállítása és lejátszása
    audio.src = file;
    audio.play().then(() => {
        document.querySelector('.play-pause i').classList.remove('fa-play');
        document.querySelector('.play-pause i').classList.add('fa-pause');
    }).catch(e => {
        console.error("Playback failed", e);
        alert("Playback failed. Please click the play button to start the audio.");
    });

    // A lejátszás és az idő frissítése
    audio.addEventListener('loadedmetadata', function () {
        document.querySelector('.total-time').textContent = formatTime(audio.duration);
    });

    audio.addEventListener('timeupdate', function () {
        document.querySelector('.current-time').textContent = formatTime(audio.currentTime);
        var progressPercentage = (audio.currentTime / audio.duration) * 100;
        document.querySelector('.progress').style.width = progressPercentage + '%';
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // Ellenőrzés, hogy van-e már létrehozva audio objektum
    if (!window.audio) {
        window.audio = new Audio();
        // További inicializálások...
    }
});















document.addEventListener('DOMContentLoaded', function () {
    var mobileMenuButton = document.getElementById('mobile-menu');
    var sidebar = document.getElementById('sidebar');

    mobileMenuButton.addEventListener('click', function () {
        sidebar.classList.toggle('active');
    });
});