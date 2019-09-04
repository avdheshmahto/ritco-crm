
var base_url = "ritco-crm";

//=============================Contact Form===============================
  
  $("#ContactForm").validate({
      rules: {
        contact_name: "required",
       designation: "required"
      },
      submitHandler: function(form) {
       // alert($('#UserForm').serialize());
          ur = "insert_contact";
          var editortext = $('#summernote').code();          
          var formData = new FormData(form);
          formData.append('summernote',  editortext);
          //alert(formData);

          $.ajax({
              type : "POST",
              url  :  ur,
              data :  formData, // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  
                  if(data == 1 || data == 2){
                      if(data == 1)
                        var msg = "Data Successfully Add !";
                      else
                        var msg = "Data Successfully Updated !";

                      $("#resultarea").text(msg); 
                      //setTimeout(function() {   //calls click event after a certain time
                      $("#contactModal .close").click();
                      $("#resultarea").text(" "); 
                      $('#ContactForm')[0].reset(); 
                      $("#contact_id").val("");
                     //}, 1000);
                   }else{
                      $("#resultarea").text(data);
                   }
                      ajex_contactListData();
                 },
                 error: function(data){
                    alert("error");
                   },
                cache: false,
                contentType: false,
                processData: false
            });
          return false;
        //e.preventDefault();
      }
  });

function ajex_contactListData(){
  ur = "ajax_ListContactData";
    $.ajax({
      url: ur,
      type: "POST",
      success: function(data){
       $("#listingData").empty().append(data).fadeIn();
        //console.log(data);
     }
  });

}


function editContact(ths) {
  //console.log('edit ready !');
  $('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.contact_id);
    if(button_property != 'view')
      
      $('#contact_id').val(editVal.contact_id);
      $('#contact_name').val(editVal.contact_name);
      $('#org_name').val(editVal.org_name).trigger('chosen:updated');
      $('#designation').val(editVal.designation);
      $('#email_id').val(editVal.email);
      $('#phone_no').val(editVal.phone);
      $('#address').val(editVal.address);
      $('#city').val(editVal.city_name);
      //$('#state_id').val(editVal.state_id).prop('selected', true);
      $('#state_id').val(editVal.state_id).trigger('chosen:updated');
      $('#pin_code').val(editVal.pincode);
      $('#country_id').val(editVal.country).trigger('chosen:updated');
      $("#summernote").code(editVal.description);
      //$("#optionsRadios").code(editVal.visibility);
      

      if(button_property == 'view'){
	    $('.top_title').html('View');
        //$('#button').css('display','none');
        $("#ContactForm :input").prop("disabled", true);
      }else{
	    $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#ContactForm :input").prop("disabled", false);
      }
};

//==============================Lead From====================================

 $("#LeadForm").validate({
    rules: {
       //leadno: "required",
       //customer:"required"
    },
      submitHandler: function(form) {
         alert($('#LeadForm').serialize());
         //var formData = new FormData(form);
         ur = "insert_lead";

         var editortext = $('#summernote').code();          
         var formData = new FormData(form);
         formData.append('summernote',  editortext);

          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    //alert(data); // show response from the php script.
                    
                    if(data == 1 || data == 2){
                      if(data == 1)
                        var msg = "Data Successfully Add !";
                      else
                        var msg = "Data Successfully Updated !";

                     $("#resultarea").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#leadModal .close").click();
                      $("#resultarea").text(" "); 
                      $('#LeadForm')[0].reset(); 
                      $("#lead_id").val("");
                    }, 1000);
                 }else{
                    $("#resultarea").text(data);
                 }
                 ajex_leadListData();
               },
                error: function(data){
                    alert("error");
                },
                cache: false,
                contentType: false,
                processData: false
            });
          return false;
        //e.preventDefault();
      }
  });

function ajex_leadListData(){
  ur = "ajax_ListLeadData";
    $.ajax({
      url: ur,
      type: "POST",
      success: function(data){

        $("#listingData").empty().append(data).fadeIn();
        //console.log(data);
     }
  });

}

function editLead(ths) {
  //console.log('edit ready !');
  $('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.contact_id);
    if(button_property != 'view')
      
      $('#lead_id').val(editVal.lead_id);
      $('#leadno').val(editVal.lead_number);
      $('#org_name').val(editVal.org_name).prop('selected', true);
      $('#customer').val(editVal.contact_id).prop('selected', true);
      $('#salesperson').val(editVal.sales_person_id).prop('selected', true);
      $('#cntperson').val(editVal.contact_person);
      $('#email_id').val(editVal.email_id);
      $('#phone_no').val(editVal.phone);
      $('#priority').val(editVal.priority).prop('selected', true);
      $('#source').val(editVal.source).prop('selected', true);
      $('#price_list').val(editVal.price_list).prop('selected', true);
      $('#closuredate').val(editVal.exptd_closer_date);
      $('#stage').val(editVal.lead_status).prop('selected', true);
      $('#industry').val(editVal.industry).prop('selected', true);
      $('#subject').val(editVal.subject);
      $('#remarks').val(editVal.remarks);
      
      if(button_property == 'view'){
    $('.top_title').html('View');
       //$('#button').css('display','none');
       $("#LeadForm :input").prop("disabled", true);
      }else{
    $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#LeadForm :input").prop("disabled", false);
      }
};


//=============================Task Form========================================

 $("#TaskForm").validate({
      rules: {
       task_name: "required",
       due_date: "required"
      },
      submitHandler: function(form) {
        //alert($('#TaskForm').serialize());
        var formData = new FormData(form);
        ur = "insert_task";

          $.ajax({
              type : "POST",
			  url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#TaskForm').serialize(), // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  if(data == 1 || data == 2){
                    if(data == 1)
                      var msg = "Data Successfully Add !";
                    else
                      var msg = "Data Successfully Updated !";
                  $("#resultarea").text(msg); 
                  //setTimeout(function() {   //calls click event after a certain time
                  $("#taskModal .close").click();
                  $("#resultarea").text(" "); 
                  $('#TaskForm')[0].reset(); 
                  $("#task_id").val("");
                 // }, 1000);
                }else{
                    $("#resultarea").text(data);
                 }
                 ajex_taskListData();
               },
                error: function(data){
                    alert("error");
                },
                cache: false,
                contentType: false,
                processData: false
            });
          return false;
        //e.preventDefault();
      }
  });

function ajex_taskListData(){
  ur = "ajax_ListTaskData";
    $.ajax({
      url: ur,
      type: "POST",
      success: function(data){

        $("#listingData").empty().append(data).fadeIn();
        //console.log(data);
     }
  });

}


function editTask(ths) {
  //console.log('edit ready !');
  $('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.task_id);
    if(button_property != 'view')
      
      $('#task_id').val(editVal.task_id);
      $('#leadid').val(editVal.lead_id).trigger('chosen:updated');
      $('#task_name').val(editVal.task_name);
      $('#due_date').val(editVal.date_due);
      $('#reminder_date').val(editVal.reminder_date);
      $('#priority').val(editVal.priority).prop('selected', true);
      $('#progress').val(editVal.progress_percnt);
      $('#status').val(editVal.task_status).prop('selected', true);      
      $('#user_resp').val(editVal.user_resp)..trigger('chosen:updated');
      $('#contact_person').val(editVal.contact_person);
      $('#org_name').val(editVal.org_name);
      //$('#optionsRadios').val(editVal.visibility);
      
      
      if(button_property == 'view'){
       $('.top_title').html('View');
       //$('#button').css('display','none');
       $("#TaskForm :input").prop("disabled", true);
      }else{
       $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#TaskForm :input").prop("disabled", false);
      }
};

//=======================================Organization Form================================

$("#OrganizationForm").validate({
      rules: {
       org_name: "required",
       phone_no:"required"
      },
      submitHandler: function(form) {
        //alert($('#TaskForm').serialize());
        //var formData = new FormData(form);
        ur = "insert_organization";
		//var textareaValue = $("#summernote").code();
         var editortext = $('#summernote').code();  
         //alert(editortext);        
         var formData = new FormData(form);
         formData.append('snotes',  editortext);
        
          $.ajax({
              type : "POST",
              url  :  ur,
              //dataType : 'json', // Notice json here
              data : formData, // serializes the form's elements.
              //data : $('#OrganizationForm').serialize(), // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  if(data == 1 || data == 2){
                    if(data == 1)
                      var msg = "Data Successfully Add !";
                    else
                      var msg = "Data Successfully Updated !";
                 
                    $("#resultarea").text(msg); 
                    setTimeout(function() {   //calls click event after a certain time
                    $("#orgModal .close").click();
                    $("#resultarea").text(" "); 
                    $('#OrganizationForm')[0].reset(); 
                    $("#org_id").val("");
                  }, 1000);
                }else{
                    $("#OrganizationForm").text(data);
                 }
                 ajex_orgListData();
               },
                error: function(data){
                    alert("error");
                },
                cache: false,
                contentType: false,
                processData: false
            });
          return false;
        //e.preventDefault();
      }
  });

function ajex_orgListData(){
  ur = "ajax_ListOrgData";
    $.ajax({
      url: ur,
      type: "POST",
      success: function(data){
        $("#listingData").empty().append(data).fadeIn();
        //console.log(data);
     }
  });

}


function editOrganization(ths) {
  //console.log('edit ready !');
  $('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
     ///alert(editVal.description);
    if(button_property != 'view')
      
      $('#org_id').val(editVal.org_id);
      $('#org_name').val(editVal.org_name);
      $('#phone_no').val(editVal.phone_no);
      $('#email_id').val(editVal.email);
      $('#website').val(editVal.website);
      $('#address').val(editVal.address);
      $('#city').val(editVal.city);

      //$('#state_id').val(editVal.state_id).prop('selected', true);
      $('#state_id').val(editVal.state_id).trigger('chosen:updated');
      $('#pin_code').val(editVal.pin_code);
       //$('#country_id').val(editVal.country).prop('selected', true);
      $('#country_id').val(editVal.country).trigger('chosen:updated');
      $("#summernote").code(editVal.description); //// editVal.description
       //$("#optionsRadios").code(editVal.visibility);
      
      
      if(button_property == 'view'){
       $('.top_title').html('View');
       //$('#button').css('display','none');
       $("#OrganizationForm :input").prop("disabled", true);
      }else{
      $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#OrganizationForm :input").prop("disabled", false);
      }
};


//================================End===============================

 /////////////////////////////   user form start ////////////////////////////

  $("#UserForm").validate({
      rules: {
        name:'required',
        email:'required',
      },
      submitHandler: function(form) {
          alert($('#UserForm').serialize());
          ur = "insertuserform";
          var formData = new FormData(form);
          $.ajax({
              type : "POST",
              url  :  ur,
              data :  formData, // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  if(data == 1 || data == 2){
                      if(data == 1)
                        var msg = "Data Successfully Add !";
                      else
                        var msg = "Data Successfully Updated !";

                      $("#resultarea").text(msg); 
                       setTimeout(function() {   //calls click event after a certain time
                        $("#myModal .close").click();
                        $("#resultarea").text(" "); 
                        $('#UserForm')[0].reset(); 
                        $('#organizations').val('').trigger('chosen:updated');
                        $('#branch').val('').trigger('chosen:updated');
                        
                     }, 1000);
                   }else{
                      $("#resultarea").text(data);
                   }
                    getsettingpage();
                },
                 error: function(data){
                    alert("error");
                 },
                cache: false,
                contentType: false,
                processData: false
            });
          
          return false;
        //e.preventDefault();
      }
  });


  ////////////////////////////// Master form  ///////////////////////////

  
  $("#MasterForm").validate({
      rules: {
        //key_name:"required",
       key_value:"required"
      },
      submitHandler: function(form) {
        //alert($('#MasterForm').serialize());
          ur = "insert_master_data";
          var formData = new FormData(form);
          $.ajax({
              type : "POST",
              url  :  ur,
              data :  formData, // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  if(data == 1 || data == 2){
                      if(data == 1)
                        var msg = "Data Successfully Add !";
                      else
                        var msg = "Data Successfully Updated !";

                      $("#resultarea").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#masterModal .close").click();
                      $("#resultarea").text(" "); 
                      $('#MasterForm')[0].reset(); 
                      $("#serial_number").val("");
                     }, 1000);
                   }else{
                      $("#resultarea").text(data);
                   }
                      ajex_masterListData();
                 },
                 error: function(data){
                    alert("error");
                   },
                cache: false,
                contentType: false,
                processData: false
            });
          return false;
        //e.preventDefault();
      }
  });

function ajex_masterListData(){
  ur = "ajax_ListMasterData";
    $.ajax({
      url: ur,
      type: "POST",
      success: function(data){
        //alert(data);
       $("#listingData").empty().append(data).fadeIn();
        console.log(data);
     }
  });

}


function editMaster(ths) {
  //console.log('edit ready !');
  $('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.contact_id);
    if(button_property != 'view')
      
      $('#serial_number').val(editVal.serial_number);
      $('#key_name').val(editVal.param_id).prop('selected', true);
      $('#paramid').val(editVal.param_id);
      $('#key_value').val(editVal.keyvalue);
      $('#desc').val(editVal.description);
      
      if(button_property == 'view'){
      $('.top_title').html('View');
       //$('#button').css('display','none');
       $("#MasterForm :input").prop("disabled", true);
      }else{
      $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#key_name").prop("disabled", true);
       $("#key_value").prop("disabled", false);
       $("#desc").prop("disabled", false);
      }
};

//========================end=======================




$(document).ready(function(){

   $(document).delegate("#formreset","click",function(){
       //alert('ssdfsdf');
    //var url = "/dapper/"+'assets/images/no_image.png';
    var formid =  $('#formreset').attr('formid');
    $(formid)[0].reset();
    $(".hiddenField").val('');
    $(".top_title").html('Add');
    $(formid+" :input").prop("disabled", false);
    $("#button").css("display", "block");
    //$('#summernote').code("");
    //CKEDITOR.instances['tem'].setData("");
    //$('#image').attr('src',url);

    });



   });





//////////////////////////////////////  getsettingpage    ///////////////////////////////


    function getsettingpage(){
       // alert('sdfsdf');
        // if(url == false)
        window.history.pushState("CRM", "CRM", "/"+base_url+"/master/Userdetails/setting_user");

          var ur = "setting_user";
          $.ajax({
              type:"POST",
              url:ur,
              data:{'page_details':'ajex_load'},
              success:function(data){
               //alert(data);
               $('#ajax_content').empty().append(data);
            }
          });
    }

   

//////////////////////////////////////  getsettingpage    ///////////////////////////////


    function getviewsettingpage(thsid){
      //alert('sdfsdf');
      window.history.pushState("CRM", "CRM", "/"+base_url+"/master/Userdetails/userview/"+thsid);
      //window.history.pushState("CRM", "CRM", "useredit/"+thsid);
        var ur = "useredit";
          $.ajax({
           type:"POST",
           url:ur,
           data:{'id':thsid},
          success:function(data){
           // console.log(data);
          $('#ajax_content').empty().append(data);
          }
      });
    }


  function getEditpage(thsid){
    //alert('sdfsdf');
   window.history.pushState("CRM", "CRM", "/"+base_url+"/master/Userdetails/useredit/"+thsid);
   var ur = "useredit";
    $.ajax({
      type:"POST",
      url:ur,
      data:{'id':thsid},
        success:function(data){
        console.log(data);
        $('#ajax_content').empty().append(data);
        //$("#organizations").val("ggg").trigger('chosen:updated');
        //$('#organizations').trigger("chosen:updated");
      }
    });
  }