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
	// width_circle=$('.circle-div').width();
	// $('.circle-div').height(width_circle);
	// $('.circle-div').css('line-height',width_circle+"px");
	// $(window).resize(function(){
	// 	width_circle=$('.circle-div').width();
	// 	$('.circle-div').height(width_circle);
	// 	$('.circle-div').css('line-height',width_circle+"px");
	// });

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

	//select file
	$('.file').change(function(e){
		$("#file_name").val($(this).val().split("\\").pop());
	});

	$('#info').popover();

	//print extractor
	$('#print').on('click',function(){
		print();
	});


/******************************************LOGIN********************************************/
	$('#login_form').submit(function(e){
		e.preventDefault();
		check=true;
		if(!$("#username").val().trim()){
			check=false;
			assignError($('#username'),'يجب إدخال أسم المستخدم');
		}
		if(!$("#password").val().trim()){
			check=false;
			assignError($('#password'),'يجب إدخال كلمة المرور');
		}
		if (check) {
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});


/******************************************ORGANIZATION********************************************/
	//add many phone numbers
	$("#add_another_phone").click(function(e){
		e.preventDefault();
		if ($(".phone_input").length>=10) {
			alert("عذراً لا يمكنك وضع اكثر من 10 أرقام!");
			return false;
		}
		$('<div class="form-group" id="del_phone'+$(".phone_input").length+'"><label for="phone'+$(".phone_input").length+'" class="control-label col-sm-2 col-md-2 col-lg-2">تليفون <span data-phone="'+$(".phone_input").length+'" class="glyphicon glyphicon-trash delete_phone"></span></label><div class="col-sm-8 col-md-8 col-lg-8"><input type="text" id="phone'+$(".phone_input").length+'" name="phone['+$(".phone_input").length+']" value="" class="form-control phone_input number" placeholder="أدخل التليفون"></div></div></div>').insertAfter("#phone_template");
	});
	//delete phone numbers input
	$(document).on('click', 'span.delete_phone', function(e) {
		$("#del_phone"+$(this).attr('data-phone')).remove();
	});

	/**
	**
	** Insertion Validation of a Customer
	**
	*/
	$('#addOrganization').submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check = true;
		var name=$("#name");
		var address=$('#address');
		var center=$('#center');
		var city=$('#city');
		if(!name.val().trim() || !name.val().trim().match(/^[a-zA-Z\u0600-\u06FF\s0-9]+$/)){
			check=false;
			assignError(name,'الاسم يجب ان يتكون من حروف و ارقام و مسافات فقط');
		}
		if(!address.val().trim() || !address.val().trim().match(/^[a-zA-Z\u0600-\u06FF\s0-9]+$/)){
			check=false;
			assignError(address,'العنوان يجب ان يتكون من حروف و ارقام و مسافات فقط');
		}
		if(!center.val().trim() || !center.val().trim().match(/^[a-zA-Z\u0600-\u06FF\s0-9]+$/)){
			check=false;
			assignError(center,'المركز يجب ان يتكون من حروف و ارقام و مسافات فقط');
		}
		if(!city.val().trim() || !city.val().trim().match(/^[a-zA-Z\u0600-\u06FF\s0-9]+$/)){
			check=false;
			assignError(city,'المدينة يجب ان تتكون من حروف و ارقام و مسافات فقط');
		}
		$(".phone_input").each(function(){
			if(!$(this).val().trim().match(/^\+?[0-9]{8,}$/)){
				check=false;
				assignError($(this),'الهاتف يجب ان يتكون من ارقام و + فقط');
			}
		});
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});



/******************************************Projects********************************************/
	// switch nav in project profile
	$(".navigate_to_div").click(function(e){
		e.preventDefault();
		$("section#navigation>div").addClass('hide').removeClass('show');
		$(".nav-tabs li.active").removeClass('active');
		var path=$(this).attr('data-nav-path');
		$(path+'_nav_item').addClass('active');
		$(path).removeClass('hide').addClass('show');
	});
	//Validation of Project Creation
	$("#add_project").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check = true;
		if (!$('#name').val().trim() || !$('#name').val().trim().match(/^[a-zA-Z\u0600-\u06FF\s0-9]+$/)) {
			check=false;
			assignError($('#name'),'يجب ان يتكون من حروف وارقام فقط');
		}
		if (!$('#def_num').val().trim() || !$('#def_num').val().trim().match(/^[0-9]+$/)) {
			check=false;
			assignError($('#def_num'),'يجب ان يتكون من ارقام فقط');
		}
		if (!$('#address').val().trim() || !$('#address').val().trim().match(/^[a-zA-Z\u0600-\u06FF\s0-9]+$/)) {
			check=false;
			assignError($('#address'),'يجب ان يتكون من حروف وارقام فقط');
		}
		if ($('#village').val().trim() && !$('#village').val().trim().match(/^[a-zA-Z\u0600-\u06FF\s0-9]+$/)) {
			check=false;
			assignError($('#village'),'يجب ان يتكون من حروف وارقام فقط');
		}
		if ($('#center').val().trim() && !$('#center').val().trim().match(/^[a-zA-Z\u0600-\u06FF\s0-9]+$/)) {
			check=false;
			assignError($('#center'),'يجب ان يتكون من حروف وارقام فقط');
		}
		if (!$('#city').val().trim() || !$('#city').val().trim().match(/^[a-zA-Z\u0600-\u06FF\s0-9]+$/)) {
			check=false;
			assignError($('#city'),'يجب ان يتكون من حروف وارقام فقط');
		}
		if ($('#model_used').val().trim() && !$('#model_used').val().trim().match(/^[a-zA-Z\u0600-\u06FF\s0-9]+$/)) {
			check=false;
			assignError($('#model_used'),'يجب ان يتكون من حروف وارقام فقط');
		}
		if ($('#extra_data').val().trim() && !$('#extra_data').val().trim().match(/^[a-zA-Z\u0600-\u06FF\s0-9\(\)]+$/)) {
			check=false;
			assignError($('#extra_data'),'يجب ان يتكون من حروف وارقام () فقط');
		}
		if (!$('#implementing_period').val().trim() || !$('#implementing_period').val().trim().match(/^[0-9]{1,3}$/)) {
			check=false;
			assignError($('#implementing_period'),'يجب ان يتكون من من ارقام فقط');
		}
		if (!$('#floor_num').val().trim() || !$('#floor_num').val().trim().match(/^[a-zA-Z\u0600-\u06FF\s0-9]+$/)) {
			check=false;
			assignError($('#floor_num'),'يجب ان يتكون من حروف وارقام فقط');
		}
		if ($('#approximate_price').val().trim() && !$('#approximate_price').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)) {
			check=false;
			assignError($('#approximate_price'),'يجب ان يتكون من ارقام فقط');
		}
		try {
			if ($('#cash_box').val().trim() && !$('#cash_box').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)) {
				check=false;
				assignError($('#cash_box'),'يجب ان يتكون من ارقام فقط');
			}
			if ($('#loan').val().trim() && !$('#loan').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)) {
				check=false;
				assignError($('#loan'),'يجب ان يتكون من ارقام فقط');
			}
			if ($('#loan_interest_rate').val().trim() && !$('#loan_interest_rate').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)) {
				check=false;
				assignError($('#loan_interest_rate'),'يجب ان يتكون من ارقام فقط');
			}
			if ($('#bank').val().trim() && !$('#bank').val().trim().match(/^[a-zA-Z\u0600-\u06FF\s0-9]+$/)) {
				check=false;
				assignError($('#bank'),'يجب ان يتكون من حروف وارقام فقط');
			}
		} catch (e) {
			console.log(e);
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});
	//Float form in projects profile
	$(".open_float_div").click(function(e){
		e.preventDefault();
		$($(this).attr('href')).show();
		$('#float_container').fadeIn(200,function(){
			$('#float_form_container').slideDown(100);
		});
	});

	$('#float_container, span.close').click(function(){
		$('#float_container').fadeOut(200);
		$('#float_form_container').hide();
		$('.float_form').hide();
	});
	$('#float_form_container').click(function(e){
		e.stopPropagation();
	});

/******************************************Terms********************************************/
	//validate term creation
	$("#add_term").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check = true;
		if(!$("#project_id").val().trim()||$("#project_id").val().trim()==0){
			assignError($("#project_id"),'من فضلك أختار مشروع اتابع له هذا البند');
			check=false;
		}else if(!$("#project_id").val().trim().match(/^[0-9]+$/)){
			assignError($("#project_id"),'من فضلك لا تغير قيمة المشروع');
			check=false;
		}
		if (!$('#type').val().trim()||$('#type').val().trim()==0) {
			assignError($("#type"),'من فضلك أدخل نوع البند');
			check=false;
		}
		if(!$('#code').val().trim()){
			assignError($("#code"),'من فضلك أدخل كود البند');
			check=false;
		}else if(!$('#code').val().trim().match(/^[0-9\/]+$/)){
			assignError($("#code"),'كود البند يجب أن يتكون من أرقام و / فقط');
			check=false;
		}
		if(!$("#statement").val().trim()){
			assignError($("#statement"),'يجب ادخال بيان الاعمال');
			check=false;
		}
		if(!$("#unit").val().trim()){
			assignError($("#unit"),'يجب ان تتكون من حروف و ارقام و مسافات فقط');
			check=false;
		}
		if(!$("#amount").val().trim() || !$("#amount").val().trim().match(/^[0-9]+(\.[0-9]+)?$/)){
			assignError($("#amount"),'الكمية يجب أن تتكون من أرقام فقط');
			check=false;
		}
		if(!$("#value").val().trim() || !$("#value").val().trim().match(/^[0-9]+(\.[0-9]+)?$/)){
			assignError($("#value"),'القيمة يجب أن تتكون من أرقام فقط');
			check=false;
		}
		if ($("#started_at").val().trim()) {
			if(!Date.parse($("#started_at").val().trim())){
				assignError($("#started_at"),'يجب ادخال تاريخ صحيح');
				check=false;
			}
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});






	/******************************************Projects********************************************/
	/******************************************Projects********************************************/

});
