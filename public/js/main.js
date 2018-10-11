$(document).ready(function() {
	//activate tooltips
	$('[data-toggle="tooltip"]').tooltip();
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
	//make circle width = height
	setTimeout(function(){
		$(".circle-div").each(function() {
			var divWidth=$(this).width();
			$(this).css("height",divWidth);
		});
	},1);
	$(window).resize(function(){
		$(".circle-div").each(function() {
			var divWidth=$(this).width();
			$(this).css("height",divWidth);
		});
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
		$('<div class="form-group row" id="del_phone'+$(".phone_input").length+'"><label for="phone'+$(".phone_input").length+'" class="control-label col-sm-2 col-md-2 col-lg-2">تليفون <span data-phone="'+$(".phone_input").length+'" class="glyphicon glyphicon-trash delete_phone"></span></label><div class="col-sm-8 col-md-8 col-lg-8"><input type="text" id="phone'+$(".phone_input").length+'" name="phone['+$(".phone_input").length+']" value="" class="form-control phone_input number" placeholder="أدخل التليفون"></div></div></div>').insertAfter("#phone_template");
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
				assignError($(this),'الهاتف يجب ان يتكون من ارقام و + فقط ولا يقل عن 8 أرقام');
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
		$("body").css('overflow','hidden');
		$($(this).attr('href')).show();
		$('#float_container').fadeIn(200,function(){
			$('#float_form_container').slideDown(100);
		});
	});

	$('#float_container, span.close, button.btn-close').click(function(){
		$("body").css('overflow','auto');
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
		if($("#project_id").length){ // Check if element exists
			if(!$("#project_id").val().trim()||$("#project_id").val().trim()==0){
				assignError($("#project_id"),'من فضلك أختار مشروع اتابع له هذا البند');
				check=false;
			}else if(!$("#project_id").val().trim().match(/^[0-9]+$/)){
				assignError($("#project_id"),'من فضلك لا تغير قيمة المشروع');
				check=false;
			}
		}
		if ($('#type_select').length) { // Check if element exists
			if(!$('#type_select').val().trim() && !$('#type_text').val().trim()) {
				assignError($("#type"),'من فضلك أدخل او اختار نوع البند');
				check=false;
			}
		}else if(!$('#type_text').val().trim()){
			assignError($("#type"),'من فضلك أدخل او اختار نوع البند');
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
		if($("#num_phases").val().trim()){
			if(!$("#num_phases").val().trim().match(/^[0-9]+(\.[0-9]+)?$/)){
				assignError($("#num_phases"),'عدد المراحل يجب أن يتكون من أرقام فقط');
				check=false;
			}
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

	$("#get_statement #code").blur(function(){
		var code= $(this).val().trim();
		$.ajax({
			url : "/term/get_statement/"+encodeURIComponent(code),
			method: "GET",
			dataType: "JSON"
		}).done(function(msg){
			if (msg.state=="OK") {
				$('#statement').val(msg.statement);
				$('#unit').val(msg.unit);
			}
		});
	});
	// Show Contract within term profile
	$(".show_contract").click(function(){
		$("body").css('overflow','hidden');
		$("#contract_term").html($(this).attr("data-contract"));
		$('#float_container').fadeIn(200,function(){
			$('#float_form_container').slideDown(100);
			$('#show_contract').show();
		});
	});


	/******************************************Notes********************************************/
	//validate note before creating
	$("#add_note").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check = true;
		if (!$("#note").val().trim()) {
			check=false;
			assignError($("#note"),'يجب أدخال الملحوظة حتى يتم حفظها');
		}
		if (!$("#title").val().trim()) {
			check=false;
			assignError($("#title"),'يجب أدخال العنوان حتى يتم حفظها');
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});
	$(".note").mouseover(function(e){
		$(this).children('.note_control').stop().fadeIn(200);
	});
	$(".note").mouseout(function(e){
		$(this).children('.note_control').stop().fadeOut(200);
	});
	//delete note modal
	$(".note_delete").click(function(e){
		e.preventDefault();
		$("body").css('overflow','hidden');
		var link = $(this).attr('href');
		$('#float_container').fadeIn(200,function(){
			$('#float_form_container').slideDown(100);
			$("#delete_note").show();
			$("#delete_note a.btn-danger").attr('href',link);
		});
	});
	/******************************************Contracts********************************************/
	//delete note modal
	$(".finish_contract").click(function(e){
		e.preventDefault();
		$("body").css('overflow','hidden');
		var link = $(this).attr('href');
		$('#float_container').fadeIn(200,function(){
			$('#float_form_container').slideDown(100);
			$("#finish_contract").show();
			$("#finish_contract a.btn-success").attr('href',link);
		});
	});
	//show contractors to select them for a contract (Within Create Contract)
	$("#show_contractor_details").click(function(){
		$("body").css('overflow','hidden');
		$("#float_form_container").css("overflow","auto");
		$('#float_container').fadeIn(200,function(){
			$('#float_form_container').slideDown(100);
		});
	});
	$(".contractor_select").click(function(){
		var id = $(this).attr("data-id");
		var details = $(this).attr("data-name")+" - "+$(this).attr("data-city")+" - "+$(this).attr("data-phone")+" ( "+$(this).attr("data-type")+" ) ";
		$("#show_contractor_details").val(details);
		$("#contractor_id").val(id);
		$("span.close").trigger("click");
	});
	//Validate creation of a contract
	$("#add_contract").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check = true;
		if ($("#contractor_id").length) {
			if (!$("#contractor_id").val().trim() || isNaN($("#contractor_id").val().trim())) {
				check=false;
				assignError($("#show_contractor_details").parent(),'يجب أختيار المقاول');
			}
		}
		if (!$("#unit_price").val().trim() || isNaN($("#unit_price").val().trim())) {
			check=false;
			assignError($("#unit_price").parent(),'يجب أدخال قيمة الوحدة للمقاول بالجنيه');
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});
	/******************************************Contractors********************************************/
	//add_extra_term_type within contractor form creation
	$("#add_extra_term_type").click(function(e){
		e.preventDefault();
		var count = $(".term_type_input").length;
		if (count>=10) {
			alert("عذراً لا يمكنك وضع اكثر من 10 أنواع!");
			return false;
		}
		console.log(count);
		var term_type_input='<div class="form-group row" id="del_type'+count+'">\
						<label for="type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع المقاول * <span data-type="'+count+'" class="glyphicon glyphicon-trash delete_term_type"></span></label>\
						<div class="col-sm-8 col-md-8 col-lg-8">\
							<input type="text" name="contractor_type['+count+']" id="contractor_type'+count+'" value="" class="form-control term_type_input" placeholder="أضافة نوع مقاول جديد">\
						</div>\
					</div>';
		$("#type_checkbox").after(term_type_input);
	});
	//delete term_type_input within creatio form
	$(document).on("click","span.delete_term_type",function(e){
		$("#del_type"+$(this).attr("data-type")).remove();
	});
	//validate contractor creation form
	$("#add_contractor").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check=true;
		if(!$("#name").val().trim().match(/^[a-zA-Z\u0600-\u06FF\s]+$/)) {
			check=false;
			assignError($("#name"),'أسم المقاول يجب أن يتكون من حروف و مسافات فقط');
		}
		if (!$("#name").val().trim()) {
			check=false;
			assignError($("#name"),'يجب أدخال أسم المقاول');
		}
		if ($("input[name='type[]']:checked").length < 1) {
			if ($(".term_type_input").length) {
				$(".term_type_input").each(function() {
					if(!$(this).val().trim() || !$(this).val().trim().match(/^[a-zA-Z\u0600-\u06FF\s]+$/)){
						check=false;
						assignError($(this),'من فضلك أدخل قيمة نوع المقاول, يجب أن تتكون من حروف و مسافات فقط');
					}
				});
			}else{
				check=false;
				assignError($("#type_checkbox_container"),'من فضلك أختار نوع المقاول');
			}
		}else{
			if ($(".term_type_input").length) {
				$(".term_type_input").each(function() {
					if(!$(this).val().trim() || !$(this).val().trim().match(/^[a-zA-Z\u0600-\u06FF\s]+$/)){
						check=false;
						assignError($(this),'من فضلك أدخل قيمة نوع المقاول, يجب أن تتكون من حروف و مسافات فقط');
					}
				});
			}
		}
		if (!$("#city").val().trim()) {
			check=false;
			assignError($("#city"),'يجب أدخال المدينة');
		}
		$(".phone_input").each(function(){
			if(!$(this).val().trim().match(/^\+?[0-9]{8,}$/)){
				check=false;
				assignError($(this),'الهاتف يجب ان يتكون من ارقام و + فقط ولا يقل عن 8 أرقام');
			}
		});
		if(check){
			console.log("Ich bin da");
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});
	/******************************************Suppliers********************************************/
	//add_extra_term_type within contractor form creation
	$("#add_extra_supplier_type").click(function(e){
		e.preventDefault();
		var count = $(".supplier_type_input").length;
		if (count>=10) {
			alert("عذراً لا يمكنك وضع اكثر من 10 أنواع!");
			return false;
		}
		console.log(count);
		var supplier_type_input='<div class="form-group row" id="del_type'+count+'">\
						<label for="type" class="control-label col-sm-2 col-md-2 col-lg-2">نوع المورد * <span data-type="'+count+'" class="glyphicon glyphicon-trash delete_term_type"></span></label>\
						<div class="col-sm-8 col-md-8 col-lg-8">\
							<div style="overflow:auto">\
								<input type="text" name="supplier_type['+count+']" id="supplier_type'+count+'" value="" class="form-control input-right supplier_type_input" placeholder="أضافة نوع مورد جديد">\
								<input type="text" name="supplier_type_unit['+count+']"id="supplier_type_unit'+count+'" value="" class="form-control input-left supplier_type_unit_input" placeholder="أدخل الوحدة">\
							</div>\
						</div>\
					</div>';
		$("#type_checkbox").after(supplier_type_input);
	});
	//validate contractor creation form
	$("#add_supplier").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check=true;
		if(!$("#name").val().trim().match(/^[a-zA-Z\u0600-\u06FF\s]+$/)) {
			check=false;
			assignError($("#name"),'أسم المورد يجب أن يتكون من حروف و مسافات فقط');
		}
		if (!$("#name").val().trim()) {
			check=false;
			assignError($("#name"),'يجب أدخال أسم المورد');
		}
		if ($("input[name='type[]']:checked").length < 1) {
			if ($(".supplier_type_input").length) {
				$(".supplier_type_unit_input").each(function() {
					if(!$(this).val().trim() || !$(this).val().trim().match(/^[a-zA-Z\u0600-\u06FF\s]+$/)){
						check=false;
						assignError($(this).parent(),'من فضلك أدخل قيمة الوحدة , يجب أن تتكون من حروف و مسافات فقط');
					}
				});
				$(".supplier_type_input").each(function() {
					if(!$(this).val().trim() || !$(this).val().trim().match(/^[a-zA-Z\u0600-\u06FF\s]+$/)){
						console.log($(this).val().trim());
						check=false;
						assignError($(this).parent(),'من فضلك أدخل قيمة نوع المورد , يجب أن تتكون من حروف و مسافات فقط');
					}
				});
			}else{
				check=false;
				assignError($("#type_checkbox_container"),'من فضلك أختار نوع المورد');
			}
		}else{
			if ($(".supplier_type_input").length) {
				$(".supplier_type_unit_input").each(function() {
					if(!$(this).val().trim() || !$(this).val().trim().match(/^[a-zA-Z\u0600-\u06FF\s]+$/)){
						check=false;
						assignError($(this).parent(),'من فضلك أدخل قيمة الوحدة , يجب أن تتكون من حروف و مسافات فقط');
					}
				});
				$(".supplier_type_input").each(function() {
					if(!$(this).val().trim() || !$(this).val().trim().match(/^[a-zA-Z\u0600-\u06FF\s]+$/)){
						check=false;
						assignError($(this).parent(),'من فضلك أدخل قيمة نوع المورد , يجب أن تتكون من حروف و مسافات فقط');
					}
				});
			}
		}
		if (!$("#city").val().trim()) {
			check=false;
			assignError($("#city"),'يجب أدخال المدينة');
		}
		$(".phone_input").each(function(){
			if(!$(this).val().trim().match(/^\+?[0-9]{8,}$/)){
				check=false;
				assignError($(this),'الهاتف يجب ان يتكون من ارقام و + فقط ولا يقل عن 8 أرقام');
			}
		});
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});
	/******************************************Consumption********************************************/
	// add_new_consumption_input_group
	var optionsConsumption =$("#type_consumption1").html();
	$("#add_new_consumption_input_group").click(function(){
		var count = $(".type_consumption").length +1;
		if (count>10) {
			alert("لا يمكن إدخال أكثر من 10 خامات فى المرة الواحدة");
			return false;
		}
		var newConsumptionInput='<div class="form-group row" id="choose_raw_to_consume'+count+'">\
			<label for="type_consumption'+count+'" class="control-label col-sm-2 col-md-2 col-lg-2">نوع الخام</label>\
			<div class="col-sm-8 col-md-8 col-lg-8">\
				<select id="type_consumption'+count+'" name="type[]" data-id="'+count+'" class="form-control type_consumption">\
					'+optionsConsumption+'\
				</select>\
			</div>\
			<div class="col-sm-2 col-lg-2 col-md-2">\
				<button type="button" class="btn btn-danger delete_consumption_input_group" data-id="'+count+'">حذف</button>\
			</div>\
		</div>\
		<div class="form-group row amount_cons" id="amount_cons'+count+'">\
			<label for="amount'+count+'" class="control-label col-sm-2 col-md-2 col-lg-2">الكمية</label>\
			<div class="col-sm-8 col-md-8 col-lg-8">\
				<div class="input-group">\
					<input type="text" name="amount[]" id="amount'+count+'" value="" class="form-control" placeholder="أدخل الكمية" aria-describedby="basic-addon1">\
					<span class="input-group-addon" id="basic-addon'+count+'"></span>\
				</div>\
			</div>\
		</div>';
		$("#amount_cons1").after(newConsumptionInput);
	});
	//delete_new_consumption_input_group
	$(document).on("click",".delete_consumption_input_group",function(){
		var count = $(this).attr("data-id");
		$("#choose_raw_to_consume"+count).remove();
		$("#amount_cons"+count).remove();
	});
	$(document).on("change",'.type_consumption',function(){
		var count=$(this).attr("data-id")?$(this).attr("data-id"):1;
		cons_type=$(this).val();
		var unit=$('input[name="'+cons_type+'"]').val();
		$('#basic-addon'+count).html(unit);
		console.log(unit);
		if($(this).val()!=0){
			$('#amount_cons'+count).css({opacity: 0, display: 'flex'}).animate({ opacity: 1 }, 300);
		}else{
			$('#amount_cons'+count).slideUp(300);
		}
	});

	/******************************************Stores********************************************/
	//show suppliers to select them to buy raw (Within Create store)
	$("#show_supplier_details").click(function(){
		$("body").css('overflow','hidden');
		$("#float_form_container").css("overflow","auto");
		$('#float_container').fadeIn(200,function(){
			$('#float_form_container').slideDown(100);
		});
	});
	$("#float_form_container").on("click",".supplier_select",function(){
			var id = $(this).attr("data-id");
			var details = $(this).attr("data-name")+" - "+$(this).attr("data-city")+" - "+$(this).attr("data-phone")+" ( "+$(this).attr("data-type")+" ) ";
			$("#show_supplier_details").val(details);
			$("#supplier_id").val(id);
			$("span.close").trigger("click");
	});
	//show type options on click or focus
	$("#type_supplier").on("click focus",function(e){
		$("#type_options").slideDown(200);
		e.stopPropagation();
	});
	$(document).click(function(){
		$("#type_options").slideUp(200);
	});
	//move between options with arrow keys
	$("#type_supplier").keydown(function(e){
		e.stopPropagation();
    if (e.keyCode == 38 || e.keyCode == 40) {
      $("#type_options .select_option:visible:first").focus();
    }
  });
	$("#type_options .select_option").keydown(function(e){
		e.stopPropagation();
    if (e.keyCode == 38) {
      $("#type_options .select_option:focus").prevAll(".select_option:visible:first").focus();
    }else if (e.keyCode == 40) {
			$("#type_options .select_option:focus").nextAll(".select_option:visible:first").focus();
    }else if(e.keyCode== 13){
			$(this).trigger("click");
			$("#type_supplier").trigger("blur");
		}
  });

	//get the suppliers at the beginging who have a chosen supplier type
	$(".select_option").click(function(){
		var type= $(this).text().trim();
		$("#type_supplier").val(type);
		$("#type_options").slideUp(200);
		getSuppliers(type);
		$('#basic-addon1').text($('input[name="'+type+'"]').val());
		if($("#amount_div").hasClass("hide")){
			$("#amount_div").removeClass("hide").css({height:0}).stop().animate({height:"40px"},200,function(){
				$("#amount").focus();
			});
		}
	});
	//display type hints to the user during typing
	$("#type_supplier").keyup(function(){
		$(".select_option").css("display","none").removeAttr("tabindex");
		var children = $("#type_options").find("div.select_option:contains("+$(this).val().trim()+")");
		var count = 0;
		children.each(function() {
			$(this).css("display","block");
			$(this).attr("tabindex",count++);
		});
	});
	$("#type_supplier").blur(function(){
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var type_supplier = $(this);
		setTimeout(function(){
			var type_string =type_supplier.val().trim();
			console.log(type_string);
			var children = $("div.select_option").filter(function(){
				if($(this).text().trim() == type_string){
					return $(this);
				}
			});
			console.log(children.length);
			if(children.length<1){
				if(type_string.length==0){
					assignError(type_supplier,"من فضل أدخل نوع الخام");
				}else if(!type_string.match(/^[0-9A-Za-z\u0600-\u06FF\s]+(-[0-9A-Za-z\u0600-\u06FF\s]+)$/)){
					assignError(type_supplier,"فى حالة عدم وجود نوع الخام فى الأختيارات يجب فعل الأتى <br>نوع الخام هذا لا يوجد بقاعدة البيانات , من فضلك ضع علامة شرطة - و أدخل بعدها الوحدة لهذ النوع. <br> مثال : أسمنت - كجم");
				}
			}
		},300);
	});
	//add_payment_to_store
	var maxStorePayment;
	$("a.add_payment_to_store").click(function(e){
		e.preventDefault();
		$("body").css('overflow','hidden');
		var link = $(this).attr('href');
		$('#float_container').fadeIn(200,function(){
			$('#float_form_container').slideDown(100);
			$("#add_payment_to_store").attr('action',link);
			$("#add_payment_to_store").show();
			$("#payment").focus();
		});
		maxStorePayment = parseFloat($(this).attr("data-allowed-amount"));
	});
	$("#add_payment_to_store").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		if(!$("#payment").val().trim().match(/^[0-9]+(\.[0-9]+)?$/)){
			assignError($("#payment"),"المبلغ المدفوع يجب أن يتكون من أرقام فقط");
			$("#save_btn").removeClass('disabled');
			return false;
		}
		console.log($("#payment").val().trim());
		console.log(maxStorePayment);
		if (parseFloat($("#payment").val().trim()) > maxStorePayment) {
			assignError($("#payment"),"المبلغ المدفوع يجب أن لا يكون أعلى من المبلغ المتبقى , و هو "+maxStorePayment+" جنيه");
			$("#save_btn").removeClass('disabled');
			return false;
		}
		this.submit();
	});
	/******************************************Productions********************************************/
	/******************************************Productions********************************************/

});
