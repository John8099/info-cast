const windowLocation = window.location;
const origin = windowLocation.origin;

let host = "";

if (windowLocation.host === "localhost") {
  host = `${origin}/${windowLocation.pathname.split("/")[1]}`;
} else {
  host = `${origin}`;
}

// if ($(".js-example-basic-single").length) {
//   $(".js-example-basic-single").select2();
// }

// if ($(".js-example-basic-multiple").length) {
//   $(".js-example-basic-multiple").select2();
// }

window.createBackendUrl = (action) => `${host}/backend/nodes?action=${action}`;

window.goBack = () => window.history.back();

window.handleOpenModalImg = (el, modalId, modalImgId, captionId) => {
  $(`#${modalImgId}`).attr("src", el[0].src);
  $(`#${captionId}`).html(el[0].alt);
  $("html").css({
    overflow: "hidden",
  });
  $(`#${modalId}`).show();
};

window.handleClose = (modalId, modalImgId, captionId) => {
  $(`#${modalId}`).fadeOut("slow", function () {
    $(`#${modalImgId}`).attr("src", "");
    $(`#${captionId}`).html("");
    $("html").css({
      overflow: "visible",
    });
  });
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

window.changeImage = function (inputId, inputIsCleared) {
  $(inputId).click();

  $(inputIsCleared).val("no");
};

window.clearImg = function (
  imgDisplayId,
  divClearId,
  divBrowseId,
  inputIsCleared,
  imagePath = ""
) {
  $("input[type=file]").val("");
  if (!imagePath) {
    $(imgDisplayId).attr("src", `${host}/public/default.png`);
  } else {
    $(imgDisplayId).attr("src", imagePath);
  }

  $(divClearId).addClass("d-none").removeClass("d-flex");
  $(divBrowseId).addClass("d-flex").removeClass("d-none");

  // $(divClearId).hide();
  // $(divBrowseId).show();

  $(inputIsCleared).val("yes");
};

window.previewFile = function (input, imgDisplayId, divClearId, divBrowseId) {
  let file = $(input).get(0).files[0];

  if (file) {
    let reader = new FileReader();

    reader.onload = function () {
      $(imgDisplayId).attr("src", reader.result);
    };

    reader.readAsDataURL(file);

    $(divClearId).addClass("d-flex").removeClass("d-none");
    $(divBrowseId).addClass("d-none").removeClass("d-flex");

    // $(divBrowseId).hide();
    // $(divClearId).show();
  }
};

$("[required]")
  .parent()
  .each(function () {
    const asterisk = ` <span class="text-danger">*</span>`;
    $(this).closest(".form-group").find("label").append(asterisk);
  });
  
$("#search_field").hideseek({
  nodata: "No results found",
});

// const target = $('img[src="https://cdn.000webhost.com/000webhost/logo/footer-powered-by-000webhost-white2.png"]');
// // if($target.length>0){
// //     var $div=$target.parent().closest('div').remove();
// // }
// console.log(target)

/* global PullToRefresh */
PullToRefresh.init({
  mainElement: '.content-wrapper',
  onRefresh: function () {
    window.location.reload();
  },
});
