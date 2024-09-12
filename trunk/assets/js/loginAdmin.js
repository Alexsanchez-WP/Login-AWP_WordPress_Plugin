/**
 *
 * @param {string} login_text title tooltip
 */

window.addEventListener("load", () => {
  const formLogin = document.querySelector("#form-login");
  if (formLogin) {
    load_images(formLogin);
  }
});

const load_images = (formLogin) => {
  formLogin.addEventListener("click", (event) => {
    if (event.target.className.includes("upload-img")) {
      let mediaUploader;

      if (mediaUploader) {
        mediaUploader.open();
        return;
      }

      mediaUploader = wp.media.frames.file_frame = wp.media({
        title: login_text.text,
        button: {
          text: login_text.text,
        },
        multiple: false,
      });

      mediaUploader.on("select", function () {
        console.log(`#${event.target.classList[0]}`);
        attachment = mediaUploader.state().get("selection").first().toJSON();

        document.querySelector(`#${event.target.classList[0]}`).value =
          attachment.url;

        document.querySelector(
          `#${event.target.classList[0]}-container`
        ).innerHTML = `<img src="${attachment.url}" style="max-width: 250px; height: auto;" alt="">`;
      });

      mediaUploader.open();
    }
  });
};
