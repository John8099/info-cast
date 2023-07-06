const windowLocation = window.location;
const origin = windowLocation.origin;

let host = "";

if (windowLocation.host === "localhost") {
  host = `${origin}/${windowLocation.pathname.split("/")[1]}`;
} else {
  host = `${origin}`;
}

window.createBackendUrl = (action) => `${host}/backend/nodes?action=${action}`;

window.goBack = () => window.history.back();

window.deleteData = function (table, col, val) {
  swal
    .fire({
      title: "Are you sure you want to remove this item?",
      text: "You can't undo this action after successful deletion.",
      icon: "warning",
      confirmButtonText: "Delete",
      confirmButtonColor: "#dc3545",
      showCancelButton: true,
    })
    .then((d) => {
      if (d.isConfirmed) {
        swal.showLoading();
        $.post(
          `${host}/backend/nodes.php?action=delete_item`,
          {
            table: table,
            column: col,
            val: val,
          },
          (data, status) => {
            const resp = JSON.parse(data);
            if (!resp.success) {
              swal.fire({
                title: "Error!",
                html: resp.message,
                icon: "error",
              });
            } else {
              window.location.reload();
            }
          }
        ).fail(function (e) {
          swal.fire({
            title: "Error!",
            html: e.statusText,
            icon: "error",
          });
        });
      }
    });
};

window.changeImage = function (inputId, medId) {
  $(inputId).click();
  $(`#isCleared${medId}`).val("No");
};

window.clearImg = function (imgDisplayId, divClearId, divBrowseId, medId) {
  $("input[type=file]").val("");
  $(imgDisplayId).attr("src", `${host}/public/default.png`);

  $(divClearId).hide();
  $(divBrowseId).show();
  // $(`#isCleared${medId}`).val("Yes");
};

window.previewFile = function (input, imgDisplayId, divClearId, divBrowseId) {
  let file = $(input).get(0).files[0];

  if (file) {
    let reader = new FileReader();

    reader.onload = function () {
      $(imgDisplayId).attr("src", reader.result);
    };

    reader.readAsDataURL(file);

    $(divBrowseId).hide();
    $(divClearId).show();
  }
};
