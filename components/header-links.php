<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>InfoCast</title>

<link rel="stylesheet" href="<?= $SERVER_NAME ?>/assets/vendors/mdi/css/materialdesignicons.min.css">
<link rel="stylesheet" href="<?= $SERVER_NAME ?>/assets/vendors/base/vendor.bundle.base.css">
<link rel="stylesheet" href="<?= $SERVER_NAME ?>/assets/css/style.css">

<link rel="shortcut icon" href="<?= $SERVER_NAME ?>/public/favicon.png" />

<link href="<?= $SERVER_NAME ?>/assets/vendors/select2/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?= $SERVER_NAME ?>/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">

<?php if (is_connected()) : ?>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

  <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.4.2/css/searchBuilder.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.4.1/css/dataTables.dateTime.min.css">

  <link rel=" stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css">

  <link rel=" stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">
<?php else : ?>

  <link rel="stylesheet" href="<?= $SERVER_NAME ?>/assets/vendors/dataTables/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= $SERVER_NAME ?>/assets/vendors/dataTables/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= $SERVER_NAME ?>/assets/vendors/dataTables/css/buttons.dataTables.min.css">

  <link rel="stylesheet" href="<?= $SERVER_NAME ?>/assets/vendors/dataTables/css/searchBuilder.dataTables.min.css">
  <link rel="stylesheet" href="<?= $SERVER_NAME ?>/assets/vendors/dataTables/css/dataTables.dateTime.min.css">

  <link rel=" stylesheet" href="<?= $SERVER_NAME ?>/assets/vendors/dataTables/css/select.dataTables.min.css">

  <link rel=" stylesheet" href="<?= $SERVER_NAME ?>/assets/vendors/dataTables/css/responsive.bootstrap4.min.css">
<?php endif; ?>

<link rel="stylesheet" href="<?= $SERVER_NAME ?>/assets/css/custom.css">