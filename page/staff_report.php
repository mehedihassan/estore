<style>
    td,th{
        background:#fff;
        font-size:12px !important;
    }
    @media print{
        @page {size: landscape}
        .bt {
            border-top: 2px solid #0bff615c!important;
        }
    }
    table.table td {
        padding: 1px 4px!important;
    }
    .bt {
        border-top: 2px solid #0bff615c!important;
    }
</style>
<div class="row">

    

	<div class="col-md-12">
		<div class="d-item-wrapper">
			<div class="d-item-title">
				<h3>শাখা অনুসারে কর্মচারির তালিকা</h3>
				<div class="d-item-tools" style="margin-top: -4px;">
				    <div class="d-flex">
				        <button type="button" class="btn btn-success btn-sm pb-0" onclick="window.print();">প্রিন্ট</button>
				        <button type="button" class="btn btn-primary btn-sm pb-0" style="margin-left:5px;" onclick="tableToExcel('print_area');">এক্সেল</button>
    				</div>
				</div>
			</div>
			<div class="d-item-content" id="print_area">
			    
			    <table style="width:100%">
			        <tr>
			        <td colspan="8" style="text-align:center;">
			            <div class="d-item-content">
			                <?php echo $db->head(); ?>
                            <h3 style="text-align: center;font-size: 14px;padding-top: 7px;margin-top:0;margin-bottom:5px;font-weight: bold;text-decoration: underline;">
                                শাখা অনুসারে কর্মচারির তালিকা </h3>
			            </div>
			     </td>
			    </tr>
			    </table>
			    <div class="table-responsive">
					<table class="table table-striped table-bordered text-center align-middle mb-0">
						<thead class="thead-custom">
							<tr>
								<th>শাখার নাম</th>
								<th>ক্র: নং</th>
								<th>নাম</th>
								<th>পদবী</th>
								<th>কক্ষ নম্বর</th>
								<th>মোবাইল নাম্বার</th>
								<th>জন্ম তারিখ</th>
								<th>যোগদানের তারিখ</th>
							</tr>
						</thead>
						<tbody>
						    
						    <?php 
						    
						    foreach($db->getdata('select * from section') as $sec){
						        
						        $sql = 'select a.*,b.name as desig_name,c.room_no from staff a 
						        left join designation b on b.id = a.designation_id 
						        left join room c on c.id = a.room_id 
						        where a.section_id ='.$sec['id'].' order by a.sl asc';
						        $staff_list = $db->getdata($sql);
						        $span = count($staff_list);
						        $sl = 1;
						        foreach($staff_list as $v){
						           echo '<tr>';
						            $bt = '';
						            if($sl == 1){
						                $bt = 'bt';
						               echo '<td rowspan="'.$span.'" style="text-align:left;" class="'.$bt.'">'.$sec['name'].'</td>'; 
						            }
    								
    								
    								echo '<td class="'.$bt.'">'.$db->en_bn($sl).'</td>
    								<td style="text-align:left;" class="'.$bt.'">'.$v['name'].'</td>
    								<td style="text-align:left;" class="'.$bt.'">'.$v['desig_name'].'</td>
    								<td class="'.$bt.'">'.$v['room_no'].'</td>
    								<td class="'.$bt.'">'.$v['number'].'</td>
    								<td class="'.$bt.'"></td>
    								<td class="'.$bt.'"></td>';
    								
    							    echo '</tr>'; 
    							    $sl++;
						        }
						        
						    }
						         
        						   
        					
						    ?>
						</tbody>
						
					</table>
				</div>
				
							
			</div>
		</div>
	</div>

	
	
	
	
	
</div>