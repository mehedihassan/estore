<style type="text/css">
  .table-bordered.table-fixed td, .table-bordered.table-fixed th {
    padding: 5px 12px;
}
</style>

  
<?php 
$db->transfer('transfer');
$staff_id = '';
if($db->getm('staff_id')){
    $staff_id = $db->getm('staff_id');
}
?>
    
<style>
.transfer h4 {
    font-size: 20px;
    font-weight: 600;
}
.stat_info th{
    text-align:left !important;
}
</style>
  
  <div class="row transfer">
    <div class="col-md-12">
        
        
        <div class="d-item-wrapper">
        	<div class="d-item-title">
        		<h3>কর্মচারীর শাখা পরিবর্তন</h3>
        		
        	</div>
        	<div class="d-item-content">
        		<?php if($staff_id == ''){?>
        		<div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>কর্মচারী নির্বাচন করুন *</label>
                      <select name="employee" id="employee" class="form-control select2" required>
                        <option value="0">কর্মচারী নির্বাচন করুন </option>
                        <?php foreach($db->getdata('select * from staff where status = 1') as $key => $value) : ?>
                        <option 
                        <?php if($staff_id == $value['id']){
                            echo ' selected ';
                        }?>
                        value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>
                
                <?php 
                }
                foreach($db->getdata('select * from staff where id ='.$staff_id) as $v){
                $designation_name = $db->getonecol('name','designation','id',$v['designation_id']);
                ?>
                  <div class="row">
                    <div class="col-sm-3">
                      <div class="emp-profile-image">
                        <?php if ($v['img']) : ?>
                        <img src="<?php echo 'image/staff/'.$v['img']; ?>" width="130" height="130" class="rounded-circle border">
                        <?php else : ?>
                        <img src="<?php echo 'image/logo.jpg'?>" width="130" height="130" class="rounded-circle">
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="col-sm-9">
                      <h4 style="color: #5066e1;"><?php echo $v['name']; ?></h4>
                      <div class="pt-1 h5" style="font-size:1.25rem"><?php echo $designation_name; ?></div>
                      <div class="pt-1"><?php echo $v['number']; ?></div>
                    </div>
                  </div>
             
        	</div>
        </div>
        
    <div class="d-item-wrapper">
    	<div class="d-item-title">
    		<h3>কর্মচারীর তথ্য</h3>
    	</div>
        <div class="d-item-content">    
          <div class="row">
            <div class="col-sm-6">
              <table class="table table-bordered table-fixed stat_info">
                <tr>
                  <th>নাম : </th>
                  <td><?php echo $v['name']; ?></td>
                </tr>
                <tr>
                  <th>আইডি নং :</th>
                  <td><?php echo $v['id_no']; ?></td>
                </tr>
                <tr>
                  <th>মোবাইল নাম্বার :</th>
                  <td><?php echo $v['number']; ?></td>
                </tr>
                <tr>
                  <th>ইমেইল :</th>
                  <td><?php echo $v['email']; ?></td>
                </tr>
                <tr>
                  <th>ঠিকানা :</th>
                  <td><?php echo $v['address']; ?></td>
                </tr>
              </table>
            </div>
            <div class="col-sm-6">
              <table class="table table-bordered table-fixed stat_info">
                <tr>
                  <th>শাখা :</th>
                  <td><?php echo $section_name = $db->getonecol('name','section','id',$v['section_id']); ?></td>
                </tr>
                <tr>
                  <th>পদবি :</th>
                  <td><?php echo $db->getonecol('name','designation','id',$v['designation_id']); ?></td>
                </tr>
                <tr>
                  <th>কক্ষ নং :</th>
                  <td><?php echo $room_no =$db->getonecol('room_no','room','id',$v['room_id']); ?></td>
                </tr>
                <tr>
                  <th>টাইপ :</th>
                  <td><?php echo $db->getonecol('name','type','id',$v['type_id']); ?></td>
                </tr>
                <tr>
                  <th>তারিখ :</th>
                  <td><?php echo $db->setdate($v['date']); ?></td>
                </tr>
              
              </table>
            </div>
          </div>
        </div>
    </div>
        
        
    <div class="d-item-wrapper">
        <div class="d-item-content">    
          <div class="row">
            <div class="col-sm-12">
              <form method="POST">
                  <input type="hidden" name="staff_id" value="<?php echo $staff_id;?>" >
                 <table class="table table-bordered table-fixed">
                     <tr>
                         <th style="width:50%" colspan="2">বর্তমানে কর্মরত শাখা</th>
                         <th style="width:50%" colspan="2">বদলিকৃত শাখা</th>
                     </tr>
                     <tr>
                         <th>শাখার নাম</th>
                         <th>কক্ষ নং</th>
                         <th>শাখার নাম</th>
                         <th>কক্ষ নং</th>
                     </tr>
                     <tr>
                         <td>
                                <?php 
                                echo $section_name;
                                ?>
                         </td>
                         <td class="text-center"><?php echo $room_no;?></td>
                         
                         <td>
                             <select name="current_section_id" class="form-control select2" required onchange="get_desig_room(this.value)">
                                <?php 
                                $sql = 'select * from section where id > 0 and id != '.$v['section_id'];
                                echo '<option value="">শাখা নির্বাচন করুন</option>';
                                foreach($db->getdata($sql) as $va){
                                    echo '<option value="'.$va['id'].'">'.$va['name'].'</option>';
                                }
                                ?>
                            </select>
                         </td>
                         <td class="text-center">
                         <select name="room_id" id="trans_room_id" class="form-control " required>
                            <option value="">কক্ষ  নির্বাচন করুন</option>
                         </select></td>
                     </tr>
                     
                 </table>
                  
                  
                  
            <div class="row">
              <div class="col-md-12 text-center">
                <button type="submit" name="btn" class="btn btn-success" value="">Submit</button>
              </div>
            </div>
            </form>
            </div>
          </div>
        </div>
    </div>    
        
     <?php }?>   
        
        
        
        
    </div>
</div>
<script>
function get_desig_room(section){
    $.post("control.php",{get_desig_room: section},function(data, status){
        obj = JSON.parse(data)
        $('#trans_room_id').html(obj.room)
    });
}
</script>
<script type="text/javascript">
  $('#employee').on('change', function() {
    var e_val = $(this).val();
    if (e_val != '0') {
      window.location.href = "transfer?staff_id="+e_val;
    }
  });
</script>



