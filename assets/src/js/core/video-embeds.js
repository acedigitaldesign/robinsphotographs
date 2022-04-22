/******************************************
JS - Video Embeds
******************************************/
const videoEmbeds = {
  
  className: 'js-video-embed',
  selector: '.js-video-embed',

  initializeClickListeners() {
    var videos = document.querySelectorAll('.js-video-embed');
    for (var i = 0; i < videos.length; i++) { 
      videos[i].addEventListener( "click", videoEmbeds.initializeVideo)
    }
  },

  initializeVideo(event) {
    var video = jQuery(event.target).closest(videoEmbeds.selector)[0];
    var videoThumbnail = video.querySelector('.js-video-thumbnail');
    var playButton = videoThumbnail.querySelector('.js-video-play-btn');
    var iframe = document.createElement( "iframe" );

    // Set iframe attributes
    iframe.setAttribute( "class", "js-video-embed-iframe tutorial-video__iframe" );
    iframe.setAttribute( "style", "visibility: hidden;" );
    iframe.setAttribute( "frameborder", "0" );
    iframe.setAttribute( "allowfullscreen", "" );
    iframe.setAttribute( "scrolling", "no" );
    iframe.setAttribute( "sandbox", "allow-scripts allow-same-origin allow-presentation allow-popups allow-popups-to-escape-sandbox" );
    iframe.setAttribute( "allow", "accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" );
    iframe.setAttribute( "src", "https://www.youtube.com/embed/" + video.dataset.videoId +"?iv_load_policy=3&modestbranding=1&rel=0&autohide=1&playsinline=1&autoplay=1" );

    // Hide play button and add loader
    playButton.setAttribute("style", "display: none");
    loaders.default.append(videoThumbnail, '-youtube-video');

    // Add iframe
    video.appendChild( iframe );

    // When video loaded, sets its content to visiible and removes the video thumbnail (which includes the appended loader)
    iframe.addEventListener("load", function() {
      iframe.setAttribute( "style", "visibility: visible;" );
      videoThumbnail.parentNode.removeChild(videoThumbnail);
    });
  }

}

videoEmbeds.initializeClickListeners();