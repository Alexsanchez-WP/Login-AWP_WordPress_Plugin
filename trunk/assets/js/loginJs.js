jQuery(function ($) {
  $("#login h1, #login form").wrapAll('<div class="grupo"></div>');
  $("#login #nav , #login #backtoblog").wrapAll(
    '<div class="grupo-dos"></div>'
  );
  $("body").vegas({
    slides: [{ src: login_imagenes.sliders }],
    overlay: login_imagenes.overlay,
    transition: ["fade", "zoomOut", "swirlLeft2"],
    delay: 8000,
    transitionDuration: 3000,
  });

  let logo = document.querySelector("body.login div#login h1 a");

  if (login_imagenes.logo) {
    logo.style.backgroundImage = `url(${login_imagenes.logo})`;
  }
  logo.style.backgroundSize = "100%";
});
