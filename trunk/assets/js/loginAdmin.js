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
        const attachment = mediaUploader
          .state()
          .get("selection")
          .first()
          .toJSON();

        document.querySelector(`#${event.target.classList[0]}`).value =
          attachment.url;
        document.querySelector(
          `#${event.target.classList[0]}-container`
        ).innerHTML = `
        <button name="delete-${event.target.classList[0]}-button" class="delete-img-logo button mg-b20">
                ${login_text.delete_button}
        </button>
        <img src="${attachment.url}" class="login-awp-img" alt="">`;
      });

      mediaUploader.open();
    }
  });
};
