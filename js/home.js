(function() {

    // play embed video
    var playEmbedBtn = document.querySelector('.video-play-btn.embed-play');
    if (playEmbedBtn) {
        playEmbedBtn.onclick = function openModal(e) {
            var embedVideoContainer = document.querySelector('.video-embed-content');
            embedVideoContainer.classList.add('show-video');
        };
    }
})();
