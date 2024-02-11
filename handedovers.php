<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>قائمة الطلبات التي تم تسليمها</b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_handover">
					<i class="fa fa-plus"></i> اضافة  طلب جديد
				</a></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">التسلسل</th>
									<th class="">تاريخ الستليم</th>
									<th class="">الكود الخاص بلطلب</th>
									<th class="">اسم المريض</th>
									<th class="">نوع الدم</th>
									<th class="">معلومات اضافية</th>
									<th class="text-center">الحالة</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$handovers = $conn->query("SELECT hr.*,r.patient,r.blood_group, r.volume,r.ref_code FROM handedover_request hr inner join requests r on r.id = hr.request_id order by date(hr.date_created) desc ");
								while($row=$handovers->fetch_assoc()):
									
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td>
										<?php echo date('M d, Y',strtotime($row['date_created'])) ?>
									</td>
									<td class="">
										 <p> <b><?php echo $row['ref_code'] ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo ucwords($row['patient']) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo $row['blood_group'] ?></b></p>
									</td>
									<td class="">
										 <p>الكمية المسلمة: <b><?php echo  ($row['volume'] / 1000).' L' ?></b></p>
										 <p>تم الاستلام بواسطة: <b><?php echo ucwords($row['picked_up_by']) ?></b></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-outline-primary edit_handover" type="button" data-id="<?php echo $row['id'] ?>" >تعديل</button>
										<button class="btn btn-sm btn-outline-danger delete_handover" type="button" data-id="<?php echo $row['id'] ?>">مسح</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: :150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	
	$('#new_handover').click(function(){
		uni_modal("New handover","manage_handover.php","mid-large")
		
	})
	$('.edit_handover').click(function(){
		uni_modal("Manage handover Details","manage_handover.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_handover').click(function(){
		_conf("Are you sure to delete this handover?","delete_handover",[$(this).attr('data-id')])
	})
	
	function delete_handover($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_handover',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>