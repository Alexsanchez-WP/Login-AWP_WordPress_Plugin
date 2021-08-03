window.addEventListener("load", () => {
  const formLogin = document.querySelector("#form-login");
  const moreImages = document.querySelector("#more-images");
  const tableBody = document.querySelector("#form-login .form-table tbody");

  // Load images wiht Media Files WordPress
  formLogin.addEventListener("click", (event) => {
    event.preventDefault();

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
        document.querySelector(`#${event.target.classList[0]}`).value = attachment.url;
      });

      mediaUploader.open();
    } else if (event.target.classList.contains("delete")) {
      console.log(event.target.dataset.delete);
      document.querySelector(`#${event.target.dataset.delete}`).remove();
    }
  });

  // Click to "more images"
  moreImages.addEventListener("click", (event) => {
    event.preventDefault();
    let count = tableBody.children.length;
    let html = document.createElement("tr");
    html.id = `item-${count}`;
    html.innerHTML = `<th scope="row">Imagen de fondo</th>
                        <td>
                            <div>
                                <input class="regular-text code" type="url" id="upload-img-${count}" name="image">
                                <button class="upload-img-${count} button">
                                    Upload image
                                </button>
                                <button class="delete button button-primary" data-delete="item-${count}">
                                    Delete
                                </button>
                            </div>
                            <p class="description">
                                Imagen para el slider de fondo en el área de login
                                <code>${count}</code>
                            </p>
                        </td>`;
    tableBody.appendChild(html);
  });
});
