jQuery(function ($) {
  $("#login h1, #login form").wrapAll('<div class="grupo"></div>');
  $("#login #nav , #login #backtoblog").wrapAll('<div class="grupo-dos"></div>');
  $("body").vegas({
    slides: login_imagenes.sliders.map((item) => {
      return {
        src: item,
      };
    }),
    overlay: login_imagenes.ruta_plantilla + "/assets/img/overlays/05.png",
    transition: ["fade", "zoomOut", "swirlLeft2"],
    delay: 8000,
    transitionDuration: 3000,
  });
  console.log(login_imagenes.logo);
  if (login_imagenes.logo) {
    document.querySelector("body.login div#login h1 a").style.backgroundImage = "url('https://pruebas.test/wp-content/plugins/login_awp/assets/img/logo.png')";
  }
});
