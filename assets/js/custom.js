const windowLocation = window.location;
const origin = windowLocation.origin;

window.createBackendUrl = (action) => {
  let backendUrl = "";
  if (windowLocation.host === "localhost") {
    backendUrl = `${origin}/${
      windowLocation.pathname.split("/")[1]
    }/backend/nodes?action=${action}`;
  } else {
    backendUrl = `${origin}/backend/nodes?action=${action}`;
  }

  return backendUrl;
};

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
        const host = window.location.host === "localhost" ? "/pharma" : "";
        swal.showLoading();
        $.post(
          `${window.location.origin}${host}/backend/nodes.php?action=delete_item`,
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
  const host = window.location.host === "localhost" ? "/pharma" : "";
  $("input[type=file]").val("");
  $(imgDisplayId).attr("src", `${host}/public/medicine.png`);

  $(divClearId).hide();
  $(divBrowseId).show();
  $(`#isCleared${medId}`).val("Yes");
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