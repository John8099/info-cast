<script src="<?= $SERVER_NAME ?>/assets/vendors/base/vendor.bundle.base.js"></script>
<script src="<?= $SERVER_NAME ?>/assets/js/template.js"></script>
<script src="<?= $SERVER_NAME ?>/assets/vendors/chart.js/Chart.min.js"></script>
<script src="<?= $SERVER_NAME ?>/assets/vendors/progressbar.js/progressbar.min.js"></script>
<script src="<?= $SERVER_NAME ?>/assets/vendors/chartjs-plugin-datalabels/chartjs-plugin-datalabels.js"></script>
<script src="<?= $SERVER_NAME ?>/assets/vendors/justgage/raphael-2.1.4.min.js"></script>
<script src="<?= $SERVER_NAME ?>/assets/vendors/justgage/justgage.js"></script>
<script src="<?= $SERVER_NAME ?>/assets/js/jquery.cookie.js"></script>
<script src="<?= $SERVER_NAME ?>/assets/js/dashboard.js"></script>

<script src="<?= $SERVER_NAME ?>/assets/vendors/sweetalert/sweetalert2.all.min.js"></script>

<script src="<?= $SERVER_NAME ?>/assets/vendors/select2/select2.min.js"></script>

<script src="<?= $SERVER_NAME ?>/assets/vendors/pull-to-refresh/index.umd.min.js"></script>
<?php if (is_connected()) : ?>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>

  <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>

  <script src="https://cdn.datatables.net/searchbuilder/1.4.2/js/dataTables.searchBuilder.min.js"></script>
  <script src="https://cdn.datatables.net/datetime/1.4.1/js/dataTables.dateTime.min.js"></script>

  <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/hideseek/0.8.0/jquery.hideseek.min.js"></script>

<?php else : ?>
  <script src="<?= $SERVER_NAME ?>/assets/vendors/dataTables/js/jquery.dataTables.min.js"></script>
  <script src="<?= $SERVER_NAME ?>/assets/vendors/dataTables/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?= $SERVER_NAME ?>/assets/vendors/dataTables/js/dataTables.buttons.min.js"></script>
  <script src="<?= $SERVER_NAME ?>/assets/vendors/dataTables/js/jszip.min.js"></script>
  <script src="<?= $SERVER_NAME ?>/assets/vendors/dataTables/js/pdfmake.min.js"></script>
  <script src="<?= $SERVER_NAME ?>/assets/vendors/dataTables/js/vfs_fonts.js"></script>
  <script src="<?= $SERVER_NAME ?>/assets/vendors/dataTables/js/buttons.html5.min.js"></script>
  <script src="<?= $SERVER_NAME ?>/assets/vendors/dataTables/js/buttons.print.min.js"></script>
  <script src="<?= $SERVER_NAME ?>/assets/vendors/dataTables/js/buttons.colVis.min.js"></script>

  <script src="<?= $SERVER_NAME ?>/assets/vendors/dataTables/js/dataTables.responsive.min.js"></script>
  <script src="<?= $SERVER_NAME ?>/assets/vendors/dataTables/js/responsive.bootstrap4.min.js"></script>

  <script src="<?= $SERVER_NAME ?>/assets/vendors/dataTables/js/dataTables.searchBuilder.min.js"></script>
  <script src="<?= $SERVER_NAME ?>/assets/vendors/dataTables/js/dataTables.dateTime.min.js"></script>

  <script src="<?= $SERVER_NAME ?>/assets/vendors/dataTables/js/dataTables.select.min.js"></script>
<?php endif; ?>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/datetime-moment.js"></script> -->

<script src="<?= $SERVER_NAME ?>/assets/js/custom.js"></script>
