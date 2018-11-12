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

	//select file
	$('.file').change(function(e){
		$("#file_name").val($(this).val().split("\\").pop());
		$("#file_name").removeClass("drag");
	});

	$('[data-toggle="popover"]').popover();

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
	//validate non organization payments
	$("#add_non_organization_payment").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check=true;
		if (!$("#non_organization_payment").val().trim() || !$('#non_organization_payment').val().trim().match(/^[0-9]+(\.[0-9]+)?$/) || $('#non_organization_payment').val().trim()>100) {
			check=false;
			assignError($('#non_organization_payment'),'النسبة يجب أن تتكون من أرقام فقط , ولا يمكن أن تكون أكثر من 100%');
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
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
	//validate adding new or old term types
	$("#add_term_type").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check=true;
		if (!$("#type").val().trim() || !$('#type').val().trim().match(/^[A-Za-z\u0600-\u06FF\s]+$/)) {
			check=false;
			assignError($('#type'),'من فضلك أدخل نوع البند , نوع البند يجب أن يتكون من حروف ومسافات فقط');
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
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
	//validation of consumption insertion
	$("#add_consumption").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check=true;
		$(".type_consumption").each(function() {
			if (!$(this).val().trim() || !$(this).val().trim().match(/^[A-Za-z\u0600-\u06FF\s]+?$/)) {
				check=false;
				assignError($(this),'من فضلك أختار نوع الخام المستهلك');
			}
		});
		$(".amount").each(function() {
			if (!$(this).val().trim() || !$(this).val().trim().match(/^[0-9]+(\.[0-9]+)?$/)) {
				check=false;
				assignError($(this).parent(".amount_group"),'من فضلك أدخل كمية الخام المستهلكة, يجب أن تتكون من أرقام فقط');
			}
		});
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});
	// add_new_consumption_input_group
	var optionsConsumption =$("#type_consumption1").html();
	$("#add_new_consumption_input_group").click(function(){
		var count = $(".type_consumption").length +1;
		if (count>10) {
			alert("لا يمكن إدخال أكثر من 10 خامات فى المرة الواحدة");
			return false;
		}
		var newConsumptionInput='<div class="form-group row" id="choose_raw_to_consume'+count+'">\
			<label for="type_consumption'+count+'" class="control-label col-sm-2 col-md-2 col-lg-2">نوع الخام *</label>\
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
			<label for="amount'+count+'" class="control-label col-sm-2 col-md-2 col-lg-2">الكمية *</label>\
			<div class="col-sm-8 col-md-8 col-lg-8">\
				<div class="input-group amount_group">\
					<input type="text" name="amount[]" id="amount'+count+'" value="" class="form-control amount" placeholder="أدخل الكمية" aria-describedby="basic-addon1">\
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
	//validation of store insertion
	$("#add_store").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check=true;
		if($('#project_id').length){
			if (!$("#project_id").val().trim() || !$('#project_id').val().trim().match(/^[0-9]+?$/)) {
				check=false;
				assignError($('#project_id'),'من فضلك أختار المشروع');
			}
		}
		if($('#type_supplier').length){
			if (!$("#type_supplier").val().trim()) {
				check=false;
				assignError($('#type_supplier'),'من فضلك أدخل نوع الخام');
			}
		}
		if (!$("#amount").val().trim() || !$('#amount').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)) {
			check=false;
			assignError($('#amount_group'),'من فضلك أدخل الكمية الموردة');
		}
		if($('#supplier_id').length){
			if (!$("#supplier_id").val().trim() || !$('#supplier_id').val().trim().match(/^[0-9]+?$/)) {
				check=false;
				assignError($('#supplier_id_group'),'من فضلك أختار المورد');
			}
		}
		if (!$("#value").val().trim() || !$('#value').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)) {
			check=false;
			assignError($('#value_group'),'من فضلك أدخل قيمة الوحدة بالجنيه, يجب أن تتكون من أرقام فقط');
		}
		if($('#amount_paid').length){
			if (!$("#amount_paid").val().trim() || !$('#amount_paid').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)) {
				check=false;
				assignError($('#amount_paid_group'),'من فضلك أدخل المبلغ المدفوع, يجب أن يتكون من أرقام فقط');
			}
		}
		if ($("#paymen_type").length) {
			if (!$("#paymen_type").val().trim() || !$('#paymen_type').val().trim().match(/^(0|1)$/)) {
				check=false;
				assignError($('#'),'من فضلك أختار نوع الدفع ');
			}
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});
	//validation of store type insertion
	$("#add_store_type").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check=true;
		if (!$("#type").val().trim() || !$('#type').val().trim().match(/^[A-Za-z\u0600-\u06FF\s]+?$/)) {
			check=false;
			assignError($('#type'),'من فضلك أدخل نوع الخام , نوع الخام يجب أن يتكون من حروف و مسافات فقط');
		}
		if (!$("#unit").val().trim() || !$('#unit').val().trim().match(/^[A-Za-z\u0600-\u06FF0-9]+?$/)) {
			check=false;
			assignError($('#unit'),'من فضلك أدخل الوحدة, الوحدة يجب أن تتكون من حروف وأرقام ومسافات فقط');
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});
	/******************************************Graphs********************************************/
	//Validation of graph insertion
	$("#add_graph").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check=true;
		if ($("#project_id").length) {
			if(!$("#project_id").val().trim()||!$("#project_id").val().trim().match(/^[0-9]+$/)){
				check=false;
				assignError($('#project_id'),'من فضلك أختار المشروع');
			}
		}
		if ($("#graph").length) {
			if(!$("#graph").val().trim()||$('#graph').val().split('.').pop().toLowerCase()!='pdf'){
				check=false;
				assignError($('#graph_group'),'من فضلك ملف الرسم يجب أن يكون من نوع PDF فقط');
			}
		}
		if(!$("#name").val().trim()||!$("#name").val().trim().match(/^[0-9A-Za-z\u0600-\u06FF\s]+$/)){
			check=false;
			assignError($('#name'),'من فضلك أدخل أسم الرسم ,يجب أن يتكون من حروف و أرقام و مسافات فقط');
		}
		if(!$("#type").val().trim()||!$("#type").val().trim().match(/^(0|1)$/)){
			check=false;
			assignError($('#type'),'من فضلك أختار نوع الرسم');
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});
	//validate graph type on change (must be PDF)
	$('#graph').change(function(e){
		$('#graph_group').removeClass("is-invalid");
		$('#graph_group + .invalid-feedback').remove();
		if($(this).val().split('.').pop().toLowerCase()!='pdf'){
			assignError($('#graph_group'),'من فضلك ملف الرسم يجب أن يكون من نوع PDF فقط');
		}
	});
	/******************************************Expenses أكرامية ********************************************/
	//Expenses validation
	$("#add_expense").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check=true;
		if($('#project_id').length){
			if(!$('#project_id').val().trim()||!$("#project_id").val().trim().match(/^[0-9]+$/)){
				check=false;
				assignError($('#project_id'),'من فضلك أختار المشروع');
			}
		}
		if(!$('#whom').val().trim()){
			check=false;
			assignError($('#whom'),'من فضلك أدخل وصف لهذه الأكرامية , حتى تستطيع تذكر لمن دفعت ولماذا');
		}
		if(!$('#expense').val().trim()||!$("#expense").val().trim().match(/^[0-9]+(\.[0-9]+)?$/)){
			check=false;
			assignError($('#expense_group'),'من فضلك أدخل قيمة الأكرامية');
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});

	/******************************************Productions********************************************/
	$("#add_production").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check=true;
		if (!$("#amount").val().trim() || !$('#amount').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)) {
			check=false;
			assignError($('#amount_group'),'من فضلك أدخل الكمية التى أُنتجت , الكمية يجب أن تتكون من أرقام فقط');
		}
		if (!$("#contract_id").val().trim() || !$('#contract_id').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)) {
			check=false;
			assignError($('#contract_id'),'من فضلك أختار المقاول الذى قام بكمية الأنتاج');
		}
		if (!$("#rate_prod").val().trim() || !$('#rate_prod').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)) {
			check=false;
			assignError($('#rate_prod'),'من فضلك أختار التقييم الذى تراه مناسباً لما قام به المقاول');
		}
		if ($("#rate_prod").val().trim()<8) {
			if (!$("#note").val().trim()) {
				check=false;
				assignError($('#note'),'يجب كتابة ملوحظة على أداء المقاول أذا كان أقل من 8');
			}
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});

	/******************************************Papers********************************************/
	$("#add_paper").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check=true;
		if(!$('#name').val().trim()){
			check=false;
			assignError($("#name"),'من فضلك أدخل أسم الورقية');
		}
		if($("#path").length){
			var ext =$("#path").val().split('.').pop().toLowerCase();
			if(ext!='pdf'&&ext!='jpg'&&ext!='jpeg'&&ext!='png'&&ext!='bmp'&&ext!='gif'){
				check=false;
				assignError($('#path_group'),'من فضلك ملف الورقية يجب أن يكون من نوع PDF, PNG, JPG, JPEG, BMP أو GIF فقط');
			}
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});
	//validate paper type on change (must be PDF)
	$('#path').change(function(e){
		$('#path_group').removeClass("is-invalid");
		$('#path_group + .invalid-feedback').remove();
		var ext =$(this).val().split('.').pop().toLowerCase();
		if(ext!='pdf'&&ext!='jpg'&&ext!='jpeg'&&ext!='png'&&ext!='bmp'&&ext!='gif'){
			assignError($('#path_group'),'من فضلك ملف الورقية يجب أن يكون من نوع PDF, PNG, JPG, JPEG, BMP أو GIF فقط');
		}
	});
	/******************************************Employees********************************************/
	//validate edit salary of assigned jobs
	$("#edit_salary").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check=true;
		if(!$('#salary').val().trim() || !$('#salary').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)){
			check=false;
			assignError($("#salary"),'من فضلك أدخل الراتب , يجب أن يتكون من أرقام فقط');
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});
	//validate assigning job
	$("#assign_job").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check=true;
		if(!$('#project_id').val().trim() || !$('#project_id').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)){
			check=false;
			assignError($("#project_id"),'من فضلك أدخل الراتب , يجب أن يتكون من أرقام فقط');
		}
		if(!$('#salary').val().trim() || !$('#salary').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)){
			check=false;
			assignError($("#salary"),'من فضلك أدخل الراتب , يجب أن يتكون من أرقام فقط');
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});
	//validate employee creation
	$("#add_employee").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check=true;
		if(!$('#name').val().trim() || ! $("#name").val().trim().match(/^[0-9A-Za-z\u0600-\u06FF\s]+$/)){
			check=false;
			assignError($("#name"),'من فضلك أدخل أسم الموظف , يجب أن يتكون من حروف و أرقام ومسافات فقط');
		}
		if(!$('#job').val().trim() || ! $("#job").val().trim().match(/^[0-9A-Za-z\u0600-\u06FF\s]+$/)){
			check=false;
			assignError($("#job"),'من فضلك أدخل المسمى الوظيفى , يجب أن يتكون من حروف و أرقام ومسافات فقط');
		}
		if($('#type_employee').length){
			if(!$('#type_employee').val().trim() || ! $("#type_employee").val().trim().match(/^(1|2)$/)){
				check=false;
				assignError($("#type_employee"),'من فضلك أختار نوع الموظف');
			}else{
				if($('#type_employee').val()==1){
					if(!$('#salary_company').val().trim() || !$('#salary_company').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)){
						check=false;
						assignError($("#salary_company"),'من فضلك أدخل الراتب , يجب أن يتكون من أرقام فقط');
					}
				}else{
					if($("input[name=assign_job]:checked").val()==1){
						if(!$('#project_id').val().trim() || !$('#project_id').val().trim().match(/^[0-9]+$/)){
							check=false;
							assignError($("#project_id"),'من فضلك أختار المشروع');
						}
						if(!$('#salary').val().trim() || !$('#salary').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)){
							check=false;
							assignError($("#salary"),'من فضلك أدخل الراتب , يجب أن يتكون من أرقام فقط');
						}
					}
				}
			}
		}else{
			if($('#salary').length){
				if(!$('#salary').val().trim() || !$('#salary').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)){
					check=false;
					assignError($("#salary"),'من فضلك أدخل الراتب , يجب أن يتكون من أرقام فقط');
				}
			}
		}
		$('.phone_input').each(function() {
			if(!$(this).val().trim().match(/^\+?[0-9]{8,}$/)){
				check=false;
				assignError($(this),'الهاتف يجب ان يتكون من ارقام و + فقط ولا يقل عن 8 أرقام');
			}
		});
		if($('#address').val().trim() && ! $("#address").val().trim().match(/^[0-9A-Za-z\u0600-\u06FF\s]+$/)){
			check=false;
			assignError($("#address"),'يجب أن يتكون من حروف و أرقام ومسافات فقط');
		}
		if($('#village').val().trim() && ! $("#village").val().trim().match(/^[0-9A-Za-z\u0600-\u06FF\s]+$/)){
			check=false;
			assignError($("#village"),'يجب أن يتكون من حروف و أرقام ومسافات فقط');
		}
		if(!$('#city').val().trim() || ! $("#city").val().trim().match(/^[0-9A-Za-z\u0600-\u06FF\s]+$/)){
			check=false;
			assignError($("#city"),'يجب أن يتكون من حروف و أرقام ومسافات فقط');
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});

	/******************************************Advances********************************************/
	$("#add_advance").submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check=true;
		if($('#employee_id').length){
			if(!$('#employee_id').val().trim() || !$('#employee_id').val().trim().match(/^[0-9]+$/)){
				check=false;
				assignError($("#employee_id"),'من فضلك أختار الموظف صاحب السلفة');
			}
		}
		if(!$('#advance').val().trim() || !$('#advance').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)){
			check=false;
			assignError($("#advance").parent(),'من فضلك أدخل قيمة السلفة , يجب أن يتكون من أرقام فقط');
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});

	/******************************************Transactions********************************************/
	//toLocaleString options
	var strOptions={
		minimumFractionDigits: 0,
		maximumFractionDigits: 2
	};
	//Extractor Change Current Amount Value of production
	$('.current_amount').on('keyup blur',function(){
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		if (!$(this).val().trim().match(/^\-?[0-9]+(\.[0-9]+)?$/)) {
			assignError($(this),'الكمية الحالية تتكون من أرقام فقط');
			return false;
		}
		parent=$(this).parent();
		value_per_unit=parseFloat(parent.prevAll(".value_per_unit").text());
		deduction_percent=parseFloat(parent.nextAll(".deduction_percent").attr('data-value'));
		total_production=parseFloat($(this).val())+parseFloat(parent.siblings('.prev_amount').attr('data-amount'));
		total_price = value_per_unit * total_production;
		deduction_value = total_price * (deduction_percent/100);
		price_after_deduction = total_price-deduction_value;
		parent.next('.total_production').text(parseFloat(total_production).toLocaleString('en',strOptions));
		parent.next('.total_production').attr('data-total-price',parseFloat(total_price));
		if (parent.nextAll('.deduction_value').children('.input-group').length) {
			id=parent.nextAll('.deduction_value').attr('data-value-id');
			$('#deduction_value_'+id).val(parseFloat(deduction_value).toFixed(2)*1);
		}else{
			parent.nextAll('.deduction_value').text(parseFloat(deduction_value).toLocaleString('en',strOptions)+" جنيه");
		}
		parent.nextAll('.deduction_value').attr('data-value',parseFloat(deduction_value).toFixed(2)*1);
		parent.nextAll('.price_after_deduction').text(parseFloat(price_after_deduction).toLocaleString('en',strOptions)+" جنيه");
	});

	//checkall terms the user want to change
	$('#checkall').change(function(){
		$('.term').prop('checked',$(this).prop('checked'));
	});
	//set extractor
	$('#set_extractor').click(function(){
		$('.current_amount').each(function(){
			if($(this).val()<0){
				$(this).val(0);
				parent=$(this).parent();
				total=parseInt($(this).val())+parseInt(parent.siblings('.prev_amount').attr('data-amount'));
				parent.next('.total_production').text(total);
			}
		});
	});
	//change percent or value of deduction to an input field
	$('.deduction_value').click(function(e){
		let id = $(this).attr('data-value-id');
		if ($('#deduction_value_'+id).length) {
			return false;
		}
		if ($('#deduction_percent_'+id).length) {
			parent=$('#deduction_percent_'+id).parent().parent();
			deduction_percent = parent.attr('data-value');
			parent.html(parseFloat(deduction_percent).toLocaleString('en',strOptions) +' %');
		}
		let name = $(this).attr('data-term');
		let value = parseFloat($(this).attr('data-value')).toFixed(2)*1;
		let input_group ='<div class="input-group">\
			<input type="text" name="'+name+'" autocomplete="off" class="form-control number deduction_input_value" value="'+value+'" id="deduction_value_'+id+'">\
			<span class="input-group-addon" style="font-size:20px; font-weight:100; ">جنيه</span>\
		</div>\
		<div class="center mt-3">\
		<span data-post="جنيه" data-id="deduction_value_'+id+'" style="color:#dc250c" class="delete_deduction_value glyphicon glyphicon-minus-sign"></span>\
		</div>';
		$(this).html(input_group);
	});
	$('.deduction_percent').click(function(e){
		let id = $(this).attr('data-value-id');
		if ($('#deduction_percent_'+id).length) {
			return false;
		}
		if ($('#deduction_value_'+id).length) {
			parent=$('#deduction_value_'+id).parent().parent();
			deduction_value=parent.attr('data-value');
			parent.html(parseFloat(deduction_value).toLocaleString('en',strOptions)  +' جنيه');
		}
		let name = $(this).attr('data-term');
		let value = parseFloat($(this).attr('data-value')).toFixed(2)*1;
		let input_group ='<div class="input-group">\
			<input type="text" name="'+name+'" autocomplete="off" class="form-control number deduction_input_percent" value="'+value+'" id="deduction_percent_'+id+'">\
			<span class="input-group-addon" style="font-size:20px; font-weight:100; ">&#37;</span>\
		</div>\
		<div class="center mt-3">\
		<span data-post="%" data-id="deduction_percent_'+id+'" style="color:#dc250c" class="delete_deduction_percent glyphicon glyphicon-minus-sign"></span>\
		</div>';
		$(this).html(input_group);
	});
	//change deduction value on keyup on deduction percent
	$(document).on('keyup change blur','.deduction_input_percent',function(){
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		deduction_percent = parseFloat($(this).val());
		if (!$(this).val().match(/^[0-9]+(\.[0-9]+)?$/)) {
			assignError($(this).parent(),'نسبة الأستقطاع تتكون من أرقام فقط');
			return false;
		}
		if (deduction_percent<0) {
			assignError($(this).parent(),'نسبة الأستقطاع لا يمكن أن تكون سالبة');
			return false;
		}
		if (deduction_percent>99) {
			assignError($(this).parent(),'نسبة الأستقطاع لا يمكن أن تتكون من أكثر من رقمين');
			return false;
		}
		th = $(this).parent().parent();
		th.attr('data-value',deduction_percent.toFixed(2)*1);
		total_price = th.prev('.total_production').attr('data-total-price');
		deduction_value = total_price * (deduction_percent/100);
		price_after_deduction = total_price-deduction_value;
		th.next('.deduction_value').text(parseFloat(deduction_value).toLocaleString('en',strOptions)+" جنيه");
		th.next('.deduction_value').attr('data-value',parseFloat(deduction_value).toFixed(2)*1);
		th.nextAll('.price_after_deduction').text(parseFloat(price_after_deduction).toLocaleString('en',strOptions)+" جنيه");
	});
	//change deduction percent on keyup on deduction value
	$(document).on('keyup change blur','.deduction_input_value',function(){
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		deduction_value = parseFloat($(this).val());
		if (!$(this).val().match(/^[0-9]+(\.[0-9]+)?$/)) {
			assignError($(this).parent(),'قيمة الأستقطاع تتكون من أرقام فقط');
			return false;
		}
		if (deduction_value<0) {
			assignError($(this).parent(),'قيمة الأستقطاع لا يمكن أن تكون سالبة');
			return false;
		}
		th = $(this).parent().parent();
		th.attr('data-value',deduction_value.toFixed(2)*1);
		total_price = th.prevAll('.total_production').attr('data-total-price');
		deduction_percent = (deduction_value/total_price)*100;
		price_after_deduction = total_price-deduction_value;
		if (deduction_percent>99) {
			assignError($(this).parent(),'هذه القيمة ستجعل النسبة تساوى أكثر من 99%');
			return false;
		}
		th.prev('.deduction_percent').text(parseFloat(deduction_percent).toLocaleString('en',strOptions)+" %");
		th.prev('.deduction_percent').attr('data-value',parseFloat(deduction_percent).toFixed(2)*1);
		th.next('.price_after_deduction').text(parseFloat(price_after_deduction).toLocaleString('en',strOptions)+" جنيه");
	});
	$(document).on('click','.deduction_input_value , .deduction_input_percent',function(e){
		e.stopPropagation();
	});
	//delete deduction input
	$('tr th').on('click','span.delete_deduction_value, span.delete_deduction_percent',function(e){
		e.stopPropagation();
		let input_id = $(this).attr('data-id');
		let old_value = $(this).parent().parent().attr('data-old-value');
		let postfix = $(this).attr('data-post');
		$('#'+input_id).val(old_value);
		$('#'+input_id).trigger('change');
		$('#'+input_id).parent().parent().text(old_value+" "+postfix);
	});
	//Transaction Validation
	$('#add_transaction').submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		$('.alert-danger').remove();
		var check = true;
		if ($('.term:checked').length) {
			$('.term:checked').each(function() {
				var id = $(this).val();
				var current_amount = $('#current_amount_'+id);
				var deduction_percent = $('#deduction_percent_'+id);
				var deduction_value = $('#deduction_value_'+id);
				if (!current_amount.val().trim() || !current_amount.val().trim().match(/^\-?[0-9]+(\.[0-9]+)?$/)) {
					check = false;
					assignError(current_amount,'يجب أدخال الكمية الحالية و يجب أن تتكون من أرقام فقط')
				}
				if (deduction_value.length) {
					if (!deduction_value.val().trim() || !deduction_value.val().trim().match(/^[0-9]+(\.[0-9]+)?$/)) {
						check = false;
						assignError(deduction_value.parent(),'يجب أدخال قيمة الأستقطاع وتكون مكونة من أرقام فقط');
					}else {
						value_per_unit=parseFloat(current_amount.parent().prevAll(".value_per_unit").text());
						total_production=parseFloat(current_amount.val())+parseFloat(current_amount.parent().siblings('.prev_amount').attr('data-amount'));
						total_price = value_per_unit * total_production;
						if ((parseFloat(deduction_value.val().trim())/total_price)*100 > 99) {
							check = false;
							assignError(deduction_value.parent(),'هذه القيمة ستجعل النسبة تساوى أكثر من 99%');
						}
					}
				}
				if (deduction_percent.length) {
					if (!deduction_percent.val().trim() || !deduction_percent.val().trim().match(/^[0-9]{0,2}(\.[0-9]+)?$/) || deduction_percent.val().trim() > 99 || deduction_percent.val().trim() < 0) {
						check = false;
						assignError(deduction_percent.parent(),'يجب أدخال نسبة الأستقطاع و تكون أرقام فقط ولا أن تكون أكثر من 99%');
					}
				}
			});
		}else{
			check = false;
			$(this).before('<div class="alert alert-danger">يجب أختيار صف واحد على الأقل</div>');
		}
		if(check){
			this.submit();
		}
		$('div#save').modal('hide');
		$("#save_btn").removeClass('disabled');
		return false;
	});
	// ADD Transaction To a specific Term
	$('#add_term_transaction #current_production').on('keyup change',function(e){
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		let current_production = $(this).val().trim();
		if (!current_production.match(/^\-?[0-9]+(\.[0-9]+)?$/)) {
			assignError($(this).parent(),'يجب أن تتكون من أرقام فقط');
			return false;
		}
		let term_value = parseFloat($(this).attr('data-term-value'));
		let prev_value = parseFloat($('#prev_production').attr('data-value'));
		let prev_amount = parseFloat($('#prev_production').attr('data-prev-amount'));
		let deduction_percent = parseFloat($('#add_term_transaction #deduction_percent').val())/100;
		let total_value = prev_value;
		let total_production = prev_amount;
		if (current_production>0) {
			total_value = total_value+(parseFloat(current_production)*term_value);
		}
		total_production = total_production+parseFloat(current_production);
		let deduction_value = parseFloat(total_value*deduction_percent);
		let net_value = total_value-deduction_value;
		$('#add_term_transaction #deduction_value').val((deduction_value.toFixed(2)*1));
		$('#net_value').text((net_value.toFixed(2)*1) + ' جنيه');
		$('#total_production').text(total_production);
		$('#total_value').text(total_value + ' جنيه');
	});
	$('#add_term_transaction #deduction_percent').on('keyup change',function(e){
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		let deduction_percent = $(this).val().trim();
		if (!deduction_percent.match(/^[0-9]{1,2}(\.[0-9]+)?$/)) {
			assignError($(this).parent(),'يجب أن تتكون من أرقام فقط ,ولا تكون أكبر من 99%');
			return false;
		}
		console.log('triggered');
		$('#current_production').trigger('change');
	});
	$('#add_term_transaction #deduction_value').on('keyup change',function(e){
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		let deduction_value = $(this).val().trim();
		if (!deduction_value.match(/^[0-9]+(\.[0-9]+)?$/)) {
			assignError($(this).parent(),'يجب أن تتكون من أرقام فقط');
			return false;
		}
		let current_production = parseFloat($('#current_production').val());
		let term_value = parseFloat($('#current_production').attr('data-term-value'));
		let prev_value = parseFloat($('#prev_production').attr('data-value'));
		let total_value = prev_value;
		if (current_production>0) {
			total_value = total_value+(parseFloat(current_production)*term_value);
		}
		let deduction_percent = (deduction_value/total_value)*100;
		if (deduction_percent>99 || deduction_percent<0) {
			assignError($(this).parent(),'هذه القيمة تجعل النسبة أكثر من 99% أو أقل من الصفر');
			return false;
		}
		$('#deduction_percent').val(deduction_percent.toFixed(2));
		let net_value = total_value-deduction_value;
		$('#net_value').text(net_value.toFixed(2)*1 + ' جنيه');
	});
	// Validation of Adding transaction to a specific Term
	$('#add_term_transaction , #add_contract_transaction').submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check = true;
		if($('#deduction_percent').length){
			if (!$('#deduction_percent').val().trim()||!$('#deduction_percent').val().trim().match(/^[0-9]{1,2}(\.[0-9]+)?$/)) {
				check=false;
				assignError($('#deduction_percent').parent(),'يجب أدخاله و قيمته تتكون من أرقام فقط وما بين 0 و 99%');
			}
		}
		if (!$('#current_production').val().trim()||!$('#current_production').val().trim().match(/^\-?[0-9]+(\.[0-9]+)?$/)) {
			check=false;
			assignError($('#current_production').parent(),'يجب أدخاله و قيمته تتكون من أرقام فقط');
		}
		if ($(this).attr('id')=='add_contract_transaction') {
			if ($('#current_production').val().trim()<0) {
				check=false;
				assignError($('#current_production').parent(),'لا يجوز بقيمة أقل من الصفر');
			}
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});

	/*
	**
	** ADD New Transaction to A specific Contract
	** Change Total Production on current production change
	****/
	$('#add_contract_transaction #current_production').on('keyup change',function(){
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		let current_production = $(this).val().trim();
		if (!current_production.match(/^\-?[0-9]+(\.[0-9]+)?$/)) {
			assignError($(this).parent(),'يجب أن تتكون من أرقام فقط');
			return false;
		}
		let unit_price = parseFloat($('#unit_price').attr('data-value'));
		let prev_value = parseFloat($('#prev_production').attr('data-prev-amount'));
		let total_production = prev_value;
		total_production = total_production+parseFloat(current_production);
		$('#total_production').text(total_production);
		$('#current_value').text((current_production*unit_price) + ' جنيه');
	});


	/******************************************Taxes********************************************/
	$('#add_tax').submit(function(e){
		e.preventDefault();
		$(".is-invalid").removeClass('is-invalid');
		$('.invalid-feedback').remove();
		var check = true;
		if($('#project_id').length){
			if (!$('#project_id').val().trim() || !$('#project_id').val().trim().match(/^[0-9]+$/)) {
				check=false;
				assignError($('#project_id'),'من فضلك أختار المشروع');
			}
		}
		if (!$('#name').val().trim()) {
			check=false;
			assignError($('#name'),'من فضلك أدخل أسم الأستقطاع');
		}
		if (!$('#value').val().trim() || !$('#value').val().trim().match(/^[0-9]+(\.[0-9]+)?$/)) {
			check=false;
			assignError($('#value').parent(),'من فضلك أدخل قيمة الأستقطاع , يجب أنت تتكون من أرقام فقط');
		}
		if (!$('#type').val().trim() || !$('#type').val().trim().match(/^(1|2)$/)) {
			check=false;
			assignError($('#type').parent(),'من فضلك أختار نوع الأستقطاع , سواء بالجنيه أو بالنسبة');
		}
		if(check){
			this.submit();
		}
		$("#save_btn").removeClass('disabled');
		return false;
	});

	/******************************************Users********************************************/


	/******************************************Productions********************************************/
	/******************************************Productions********************************************/
	/******************************************Productions********************************************/
});
