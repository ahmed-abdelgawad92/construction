function assignError(element,errorMsg) {
  element.addClass("is-invalid");
  element.after("<div style='display:block' class='invalid-feedback'>"+errorMsg+"</div>");
}
function getSuppliers(type=null) {
  var content="";
  var uri="";
  if (type!=null) {
    uri= "/store/get_suppliers/"+encodeURIComponent(type);
  }else{
    uri= "/store/get_suppliers";
  }
  $.ajax({
    url : uri,
    method: "GET",
    dataType: "JSON"
  }).done(function(msg){
    if (msg.state=="SPEC") {
      var suppliers = msg.suppliers;
      if (msg.specificSuppliers.length>0) {
        var specificSuppliers = msg.specificSuppliers;
        content+='<div class="category">\
        <h5 class="category">مورديين '+type+'</h5>\
        </div>\
        <div>';
        for (supplier of specificSuppliers) {
          content+='<div class="supplier_select" data-id="'+supplier.id+'" data-name="'+supplier.name+'" data-type="'+supplier.type.replace(/,/g,' , ')+'" data-phone="'+supplier.phone.replace(/,/g,' , ')+'" data-city="'+supplier.city+'">\
          <div class="row">\
          <div class="col-2">\
          <img src="/images/contractor.png" class="w-100" alt="">\
          </div>\
          <div class="col-10">\
          <h4>'+supplier.name+'</h4>\
          '+supplier.phone.replace(/,/g,' , ')+'&nbsp;&nbsp;&nbsp;&nbsp;\
          '+supplier.city+'&nbsp;&nbsp;&nbsp;&nbsp;\
          ('+supplier.type.replace(/,/g,' , ')+')\
          </div>\
          </div>\
          </div>';
        }
        content+="</div>";
      }
      content+='<div class="category">\
        <h5 class="category">جميع أنواع الموردين</h5>\
      </div>\
      <div>';
      for (supplier of suppliers) {
        content+='<div class="supplier_select" data-id="'+supplier.id+'" data-name="'+supplier.name+'" data-type="'+supplier.type.replace(/,/g,' , ')+'" data-phone="'+supplier.phone.replace(/,/g,' , ')+'" data-city="'+supplier.city+'">\
          <div class="row">\
            <div class="col-2">\
              <img src="/images/contractor.png" class="w-100" alt="">\
            </div>\
            <div class="col-10">\
              <h4>'+supplier.name+'</h4>\
              '+supplier.phone.replace(/,/g,' , ')+'&nbsp;&nbsp;&nbsp;&nbsp;\
              '+supplier.city+'&nbsp;&nbsp;&nbsp;&nbsp;\
              ('+supplier.type.replace(/,/g,' , ')+')\
            </div>\
          </div>\
        </div>';
      }
      content+="</div>";
      $("#supplier_container").html(content);
    }else if(msg.state=="ALL"){
      var suppliers = msg.suppliers;
      content+='<div class="category">\
        <h5 class="category">جميع أنواع الموردين</h5>\
      </div>\
      <div>';
      for (supplier of suppliers) {
        content+='<div class="supplier_select" data-id="'+supplier.id+'" data-name="'+supplier.name+'" data-type="'+supplier.type+'" data-phone="'+supplier.phone+'" data-city="'+supplier.city+'">\
          <div class="row">\
            <div class="col-2">\
              <img src="/images/contractor.png" class="w-100" alt="">\
            </div>\
            <div class="col-10">\
              <h4>'+supplier.name+'</h4>\
              '+supplier.phone+'&nbsp;&nbsp;&nbsp;&nbsp;\
              '+supplier.city+'&nbsp;&nbsp;&nbsp;&nbsp;\
              ('+supplier.type+')\
            </div>\
          </div>\
        </div>';
      }
      content+="</div>";
      $("#supplier_container").html(content);
    }
  });
}
