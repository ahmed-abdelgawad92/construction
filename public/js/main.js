$(document).ready(function() {
	$('#side-bar-icon').click(function(){
		if($('#side-bar').css('display')=='none'){
			$('#side-bar').stop().fadeIn(300);
		}else{
			$('#side-bar').stop().fadeOut(300);
		}
	});
	$('.side-link').click(function(){
		if($(this).siblings().css('display')!='none'){
			$(this).siblings().slideUp(500);
		}else{
			$('.child-options').stop().slideUp(500);
			$(this).siblings().stop().slideDown(500);
		}
		$(this).blur();
		return false;
	});
	//change date format
	$( "#started_at" ).datepicker({
		dateFormat : "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		firstDay:6
	});
	//disable button on submit
	$('#save_btn').click(function(){
		$(this).addClass("disabled");
	});

	//show note when rate is less than 8
	if(!$('#proNote').hasClass('display')){
		$('#proNote').hide();
	}
	$('#rate_prod').change(function() {
		if($(this).val()<8){
			$('#proNote').stop().slideDown(1000);
		}
		else{
			$('#proNote').stop().slideUp(1000);
		}
	});

	//circle make height equal width
	var width_circle=$('.circle-div').width();
	$('.circle-div').height(width_circle);
	$('.circle-div').css('line-height',width_circle+"px");
	$(window).resize(function(){
		width_circle=$('.circle-div').width();
		$('.circle-div').height(width_circle);
		$('.circle-div').css('line-height',width_circle+"px");
	});

	//show unit in adding consumption
	if(!$('#amount_cons').hasClass('display')){
		$('#amount_cons').hide();
	}
	var cons_type;
	$('#type_consumption').change(function(){
		if($(this).val()!=0){
			cons_type=$(this).val();
			$('#basic-addon1').text($('input[name="'+cons_type+'"]').val());
			$('#amount_cons').stop().slideDown(1000);
		}else{
			$('#amount_cons').stop().slideUp(1000);
		}
	});
	//show only supplier type based on supplier id
	var supplier;
	$('#supplier_id_choose').change(function(){
		if($(this).val()!=0){
			supplier=$(this).val();
			supplier_type=$('input[name="'+supplier+'"]').val().split(',');
			$('#type_supplier').html('<option val="0">أختار نوع الخام</option>');
			for(i=0;i<supplier_type.length;i++){
				$('#type_supplier').append('<option val="'+supplier_type[i]+'">'+supplier_type[i]+'</option>');
			}
		}else{
			$('#type_supplier').html('<option val="0">أختار نوع الخام</option>');
			$('input[name="store_type[]"]').each(function(){
				$('#type_supplier').append('<option val="'+$(this).val()+'">'+$(this).val()+'</option>');
			});
		}
	});
	//change amount unit
	$('#type_supplier').change(function(){
		$('#basic-addon1').text($('input[name="'+$(this).val()+'"]').val());
	});

	//icon change opacity
	$('.icon').mouseover(function(){
		$(this).css('opacity',0.7);
	});
	$('.icon').mouseout(function(){
		$(this).css('opacity',1);
	});

	//show inputs in adding employee if they have display class
	if(!$('#div-salary-company').hasClass('display')){
		$('#div-salary-company').hide();
	}
	if(!$('#div-assign-job').hasClass('display')){
		$('#div-assign-job').hide();
	}
	if(!$('#assign_job_form').hasClass('display')){
		$('#assign_job_form').hide();
	}
	$('#type_employee').change(function(){
		if($(this).val()==1){
			$('#assign_job_form').stop().slideUp(1000,function(){
				$('#div-assign-job').stop().slideUp(1000,function(){
					$('#div-salary-company').stop().slideDown(1000);
				});
			});
		}
		else if($(this).val()==2){
			$('#assign_job_form').stop().slideUp(1000,function(){
				$('#div-salary-company').slideUp(1000,function(){
					$('#div-assign-job').stop().slideDown(1000);
				});
			});
		}
		else{
			$('#assign_job_form').slideUp(1000);
			$('#div-assign-job').slideUp(1000);
			$('#div-salary-company').slideUp(1000);
		}
	});
	$('input[name="assign_job"]').change(function(){
		if($(this).val()==0){
			$('#assign_job_form').stop().slideUp(1000);
		}
		else if($(this).val()==1){
			$('#assign_job_form').stop().slideDown(1000);
		}
	});

	//Extractor Change Current Amount Value
	$('.current_amount').keyup(function(){
		parent=$(this).parent();
		total=parseInt($(this).val())+parseInt(parent.siblings('.prev_amount').text());
		parent.next('.total_production').text(total);
	});

	//checkall
	$('#checkall').change(function(){
		$('.term').prop('checked',$(this).prop('checked'));
	});
	//set extractor
	$('#set_extractor').click(function(){
		$('.current_amount').each(function(){
			if($(this).val()<0){
				$(this).val(0);
				parent=$(this).parent();
				total=parseInt($(this).val())+parseInt(parent.siblings('.prev_amount').text());
				parent.next('.total_production').text(total);
			}
		});
	});

	$('#info').popover();

	//print extractor
	$('#print').on('click',function(){
		print();
	});
});
