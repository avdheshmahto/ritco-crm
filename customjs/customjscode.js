
var base_urls = "ritco-crm-demo";

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

          $("#saveload").css("display","inline-block");
          $("#formsave").attr("type","button");
          $("#formsave").css("display","none");
        
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
                    $("#saveload").css("display","none");
                    $("#formsave").css("display","inline-block");
                    $("#formsave").attr("type","submit");
                  }, 1000);
                }else{
                    $("#resultarea").text(data);
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


function editOrganization(ths) 
{

  //org_contactData();
  //console.log('edit ready !');
  $('.project_images').remove();
  //$('.error').css('display','none');

  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
     //alert(editVal.contact_id);
    if(button_property != 'view')
    {

      $('#newidorg').css("display", "none");
      $('#oldorgid').val(editVal.org_id);
      $('#org_name').val(editVal.org_name);
      $('#website').val(editVal.website);

      if(editVal.email != "")
      {
        j_email = JSON.parse(editVal.email);
           if(j_email != ""){
            for(var i=0;i<j_email.length;i++){
              if(i == 0)
                $('#email_id0').val(j_email[0]);
              else
                addRowOrgEmail(j_email[i],i);

         }
        }
      }

       if(editVal.phone_no != ""){
        j_phone = JSON.parse(editVal.phone_no);
        if(j_phone != ""){
        for(var i=0;i<j_phone.length;i++){
          if(i == 0)
           $('#phone_no0').val(j_phone[0]);
          else
           addRowOrgPhone(j_phone[i],i);

          }
        }
      }

      $('#address').val(editVal.address);
      $('#city').val(editVal.city);      
      $('#pin_code').val(editVal.pin_code);
      $('#country_id').val(editVal.country).trigger('chosen:updated');
      $('#state_id').val(editVal.state_id).trigger('chosen:updated');      
      $("#summernote").code(editVal.description); 

      $('#newid').css("display", "none");
      $('#oldcontact_id').val(editVal.contact_id);
       if(editVal.contact_id != '')
       {
         org_contactData(editVal.contact_id);
       }
       else
       {
        $("#contact_name").val('');
        $("#designation").val('');
        $("#cemail_id0").val('');
        $("#cphone_no0").val('');
       }

    }  
      
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


 function getViewOrgPage(thsid){
      //alert(url);
       window.history.pushState("CRM", "CRM", "/"+base_urls+"/organization/Organization/orgview/"+thsid);
      //window.history.pushState("CRM", "CRM", "useredit/"+thsid);
        var ur = "orgview";
          $.ajax({
           type:"POST",
           url:ur,
           data:{'id':thsid},
          success:function(data){ 
           // console.log(data);
          
          //$('#ajax_content').empty().append(data);
          $("#ajax_content").empty().append(data).fadeIn();
          
          }
      });
    }

		//=================Organization Note Form===================

  $("#OrgzNotesForm").validate({
    rules: {
       note_name: "required",
       note_date: "required"
    },
      submitHandler: function(form) {
         //alert($('#LeadForm').serialize());
         //var formData = new FormData(form);
         ur = "insert_orgz_note";
         //alert(ur);
         var editortext = $('#summernotesid').code();          
         var formData   = new FormData(form);
         formData.append('note_desc',  editortext);
          
          $("#orgnoteload").css("display","inline-block");
          $("#orgnotesave").attr("type","button");
          $("#orgnotesave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    console.log(data); // show response from the php script.
                    //alert(data);
                    if(data != ''){
                      //if(data == 1)
                        //var msg = "Data Successfully Add !";
                      //else
                        var msg = "Data Successfully Save !";

                      $("#resultnote").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#orgzNoteModal .close").click();
                      $("#resultnote").text(" "); 
                      $('#OrgzNotesForm')[0].reset(); 
                      $("#noteid").val("");
                      $("#orgnoteload").css("display","none");
                      $("#orgnotesave").css("display","inline-block");
                      $("#orgnotesave").attr("type","submit");
                    }, 1000);
                 }else{
                    $("#resultnote").text(data);
                 }
                 ajex_orgzNoteData(data);
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

function ajex_orgzNoteData(thsid){
  ur = "ajax_ListOrgzNotes";
    $.ajax({
      url: ur,
      type: "POST",
      data:{'id':thsid},
      success: function(data){

        $("#ajax_content").empty().append(data).fadeIn();
        console.log(data);
     }
  });

}
function editOrgzNote(ths) {
  //console.log('edit ready !');
  //$('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.cname);
     if(button_property != 'view')
     {
     
      //$('#leadidno').val(editVal.note_id);
      $('#noteid').val(editVal.note_id);
      $('#note_name').val(editVal.note_name);
      $('#note_date').val(editVal.note_date);
      $("#summernotesid").code(editVal.note_desc);
    }  
      
      if(button_property != 'view')
      {
       $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#OrgzNotesForm :input").prop("disabled", false);
      }
};


//=============Organization File Form======================

$("#FilesOrgzForm").validate({
    rules: {
       files_name: "required",
       files_desc: "required"
    },
      submitHandler: function(form) {
         //alert($('#LeadForm').serialize());
         var formData = new FormData(form);
         ur = "insert_orgz_file";
         //alert(ur);

          $("#fileorgzload").css("display","inline-block");
          $("#fileorgzsave").attr("type","button");
          $("#fileorgzsave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    console.log(data); // show response from the php script.
                    //alert(data);
                    if(data != ''){
                      //if(data == 1)
                        //var msg = "Data Successfully Add !";
                      //else
                        var msg = "Data Successfully Save !";

                      $("#resultfile").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#fileOrgzModal .close").click();
                      $("#resultfile").text(" "); 
                      $('#FilesOrgzForm')[0].reset(); 
                      $("#fileid").val("");
                      $("#fileorgzload").css("display","none");
                      $("#fileorgzsave").css("display","inline-block");
                      $("#fileorgzsave").attr("type","submit");
                    }, 1000);
                 }else{
                    $("#resultfile").text(data);
                 }
                 ajex_loadFileOrgzData(data);
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

function ajex_loadFileOrgzData(thsid){
  ur = "ajax_orgzFilesData";
    $.ajax({
      url: ur,
      type: "POST",
      data:{'id':thsid},
      success: function(data){

        $("#ajax_content").empty().append(data).fadeIn();
        console.log(data);
     }
  });

}

function editOrgzFiles(ths) {

  var image_url = "/"+base_urls+"/crmfiles/orgfile"+'/';
  //console.log('edit ready !');
  //$('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.cname);
     if(button_property != 'view')
     {

      $('#fileid').val(editVal.file_id);
      //$('#files_name').val(editVal.files_name);
      $('#files_desc').val(editVal.files_desc);
      $('#image').css('display','none');
      if(editVal.files_name != "")
      {
	      $('#image').attr('href',image_url+editVal.files_name);
	      $('#image').attr('title',editVal.files_name);
	      $('#image').css('display','block');
      }
     }  
      
      if(button_property != 'view')
      {
       $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#FilesOrgzForm :input").prop("disabled", false);
      }
};



//=============================Contact Form===============================
  
  $("#ContactForm").validate({
    rules: {
        contact_name: "required",
       designation: "required"
      },
      submitHandler: function(form) {
        //alert($('#ContactForm').serialize());
          ur = "insert_contact";
          var editortext = $('#summernote').code();          
          var formData = new FormData(form);
          formData.append('summernote',  editortext);
          //alert(formData);
          $("#saveload").css("display","inline-block");
          $("#formsave").attr("type","button");
          $("#formsave").css("display","none");

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

                      //alert(msg);
                      $("#resultarea").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#contactModal .close").click();
                      $("#resultarea").text(" "); 
                      $('#ContactForm')[0].reset(); 
                      $("#contact_id").val("");
                      $("#saveload").css("display","none");
                      $("#formsave").css("display","inline-block");
                      $("#formsave").attr("type","submit");
                      }, 1000);
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


function editContact(ths) 
{

  //console.log('edit ready !');
  $('.project_images').remove();
  //$('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.contact_id);
    if(button_property != 'view')
    {
      $('#newid').css("display", "none");
      $('#oldcontact_id').val(editVal.contact_id);
      $('#contact_name').val(editVal.contact_name);      
      $('#designation').val(editVal.designation);

      if(editVal.email != "")
      {
        j_email = JSON.parse(editVal.email)
        if(j_email != "")
        {
          for(i=0; i<j_email.length; i++)
          {
            if(i==0)
              $('#email_id0').val(j_email[0])
            else
              addRowCntemail(j_email[i],i);

          }
        }
      }

      if(editVal.phone != "")
      {
        j_phone = JSON.parse(editVal.phone)
        if(j_phone != "")
        {
          for(i=0; i<j_phone.length; i++)
          {
            if(i==0)
              $('#phone_no0').val(j_phone[0])
            else
              addRowCntphone(j_phone[i],i);
          }
        }
      }

      $('#address').val(editVal.address);
      $('#city').val(editVal.city_name);
      $('#pin_code').val(editVal.pincode);      
      $('#state_id').val(editVal.state_id).trigger('chosen:updated');
      $('#country_id').val(editVal.country).trigger('chosen:updated');
      $("#summernote").code(editVal.description);

      $('#newidorg').css("display", "none");
      $('#oldorgid').val(editVal.org_name);
      if(editVal.org_name !='')
      {
        cnt_orgzData(editVal.org_name);
      }
      else
      {
        $("#org_name").val('');
        $("#website").val('');
        $("#oemail_id0").val('');
        $("#ophone_no0").val('');
      }

    }

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


function getViewContactPage(thsid){
      //alert(thsid);
       window.history.pushState("CRM", "CRM", "/"+base_urls+"/contact/Contact/contactview/"+thsid);
      //window.history.pushState("CRM", "CRM", "useredit/"+thsid);
        var ur = "contactview";
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

  //=================Contact Note Form===================

  $("#ContactNotesForm").validate({
    rules: {
       note_name: "required",
       note_date: "required"
    },
      submitHandler: function(form) {
         //alert($('#LeadForm').serialize());
         //var formData = new FormData(form);
         ur = "insert_contact_note";
         //alert(ur);
         var editortext = $('#summernotesid').code();          
         var formData   = new FormData(form);
         formData.append('note_desc',  editortext);
          
          $("#cntctnoteload").css("display","inline-block");
          $("#cntctnotesave").attr("type","button");
          $("#cntctnotesave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    console.log(data); // show response from the php script.
                    //alert(data);
                    if(data != ''){
                      //if(data == 1)
                        //var msg = "Data Successfully Add !";
                      //else
                        var msg = "Data Successfully Save !";

                      $("#resultnote").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#contactNoteModal .close").click();
                      $("#resultnote").text(" "); 
                      $('#ContactNotesForm')[0].reset(); 
                      $("#noteid").val("");
                      $("#cntctnoteload").css("display","none");
                      $("#cntctnotesave").css("display","inline-block");
                      $("#cntctnotesave").attr("type","submit");
                    }, 1000);
                 }else{
                    $("#resultnote").text(data);
                 }
                 ajex_cntctNoteData(data);
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

function ajex_cntctNoteData(thsid){
  ur = "ajax_ListCntctNotes";
    $.ajax({
      url: ur,
      type: "POST",
      data:{'id':thsid},
      success: function(data){

        $("#ajax_content").empty().append(data).fadeIn();
        console.log(data);
     }
  });

}
function editCntctNote(ths) {
  //console.log('edit ready !');
  //$('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.cname);
     if(button_property != 'view')
     {
     
      //$('#leadidno').val(editVal.note_id);
      $('#noteid').val(editVal.note_id);
      $('#note_name').val(editVal.note_name);
      $('#note_date').val(editVal.note_date);
      $("#summernotesid").code(editVal.note_desc);
    }  
      
      if(button_property != 'view')
      {
       $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#ContactNotesForm :input").prop("disabled", false);
      }
};


//============= Contact File Form======================

$("#FilesContactForm").validate({
    rules: {
       files_name: "required",
       files_desc: "required"
    },
      submitHandler: function(form) {
         //alert($('#LeadForm').serialize());
         var formData = new FormData(form);
         ur = "insert_cntct_file";
         //alert(ur);

          $("#filecntctload").css("display","inline-block");
          $("#filecntctsave").attr("type","button");
          $("#filecntctsave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    console.log(data); // show response from the php script.
                    //alert(data);
                    if(data != ''){
                      //if(data == 1)
                        //var msg = "Data Successfully Add !";
                      //else
                        var msg = "Data Successfully Save !";

                      $("#resultfile").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#fileContactModal .close").click();
                      $("#resultfile").text(" "); 
                      $('#FilesContactForm')[0].reset(); 
                      $("#fileid").val("");
                      $("#filecntctload").css("display","none");
                      $("#filecntctsave").css("display","inline-block");
                      $("#filecntctsave").attr("type","submit");
                    }, 1000);
                 }else{
                    $("#resultfile").text(data);
                 }
                 ajex_loadFileCntctData(data);
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

function ajex_loadFileCntctData(thsid){
  ur = "ajax_cntctFilesData";
    $.ajax({
      url: ur,
      type: "POST",
      data:{'id':thsid},
      success: function(data){

        $("#ajax_content").empty().append(data).fadeIn();
        console.log(data);
     }
  });

}

function editCntctFiles(ths) {

  var image_url = "/"+base_urls+"/crmfiles/contactfile"+'/';
  //console.log('edit ready !');
  //$('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.cname);
     if(button_property != 'view')
     {

      $('#fileid').val(editVal.file_id);
      //$('#files_name').val(editVal.files_name);
      $('#files_desc').val(editVal.files_desc);
      $('#image').css('display','none');
      if(editVal.files_name != "")
      {
	      $('#image').attr('href',image_url+editVal.files_name);
	      $('#image').attr('title',editVal.files_name);
	      $('#image').css('display','block');
      }
     }  
      
      if(button_property != 'view')
      {
       $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#FilesContactForm :input").prop("disabled", false);
      }
};



//==============================Lead From====================================

 $("#LeadForm").validate({
    rules: {
       contact: "required",
       org_name: "required",
       user_resp: "required"
    },
      submitHandler: function(form) {
         //alert($('#LeadForm').serialize());
         //var formData = new FormData(form);
         ur = "insert_lead";

         var editortext = $('#summernote').code();          
         var formData   = new FormData(form);
         formData.append('summernote',  editortext);
          $("#saveload").css("display","inline-block");
          $("#formsave").attr("type","button");
          $("#formsave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    console.log(data); // show response from the php script.
                    
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
                      $("#saveload").css("display","none");
                      $("#formsave").css("display","inline-block");
                      $("#formsave").attr("type","submit");
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

function editLead(ths) 
{

  //console.log('edit ready !');
  $('.project_images').remove();
  $('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.contact_id);
    if(button_property != 'view')
    {  
      $('#lead_id').val(editVal.lead_id);
      $('#oldcontact').val(editVal.contact_id);
      $('#orgidcontant').val(editVal.org_id);

      $('#contacttypahead').val(editVal.contact_name);
      $('#org_name').val(editVal.oname);
      //$('#lead_number').val(editVal.contact_name+'_'+data.org_name);
      $('#lead_number').val(editVal.lead_number);
      $('#user_resp').val(editVal.user_resp).trigger('chosen:updated');


       if(editVal.cemail != "")
       {
        j_email = JSON.parse(editVal.cemail);
           if(j_email != ""){
            for(var i=0;i<j_email.length;i++){
              if(i == 0)
                $('#email_id0').val(j_email[0]);
              else
                addRowCompemail(j_email[i],i);

         }
        }
      }

    //$('#phone_no').val(data.cphone);
       if(editVal.cphone != ""){
        j_phone = JSON.parse(editVal.cphone);
        if(j_phone != ""){
        for(var i=0;i<j_phone.length;i++){
          if(i == 0)
           $('#phone_no0').val(j_phone[0]);
          else
           addRowComphone(j_phone[i],i);

          }
        }
      }

     /* if(editVal.oemail != ""){
        j_oemail = JSON.parse(editVal.oemail);
           if(j_oemail != ""){
            for(var i=0;i<j_oemail.length;i++){
              if(i == 0)
                $('#Org_email_id0').val(j_oemail[0]);
              else
                addRowOrgEmail(j_oemail[i],i);

         }
        }
      }*/

    //$('#org_phone_no').val(data.ophone);
     /* if(editVal.ophone_no != ""){
        j_ophone = JSON.parse(editVal.ophone_no);
      if(j_ophone != ""){
        for(var i=0;i<j_ophone.length;i++){
          if(i == 0)
           $('#org_phone_no0').val(j_ophone[0]);
          else
           addRowOrgPhone(j_ophone[i],i);
          
          }
        }
      }
*/
     
      $('#address').val(editVal.caddress);
      // $('#org_address').val(editVal.oaddress);    
      // $('#website').val(editVal.website);
     
      $('#industry').val(editVal.industry).trigger('chosen:updated');
      //$('#no_of_emp').val(editVal.no_of_emp);
      // $('#source').val(editVal.source).prop('selected', true);      
      $('#source').val(editVal.source).trigger('chosen:updated'); 
      //$('#stage').val(editVal.stage).prop('selected', true);
      $('#stage').val(editVal.stage).trigger('chosen:updated');     
      $('#probability').val(editVal.probability);
      $('#closuredate').val(editVal.closuredate);
      $('#opp_value').val(editVal.opp_value);


      $('#summernote').code(editVal.discription);

    }

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

function getViewLeadPage(thsid){
      //alert(thsid);
       window.history.pushState("CRM", "CRM", "/"+base_urls+"/lead/Lead/leadview/"+thsid);
      //window.history.pushState("CRM", "CRM", "useredit/"+thsid);
        var ur = "leadview";
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

    	//================= Lead Note Form===================

  $("#LeadNotesForm").validate({
    rules: {
       note_name: "required",
       note_date: "required"
    },
      submitHandler: function(form) {
         //alert($('#LeadForm').serialize());
         //var formData = new FormData(form);
         ur = "insert_lead_note";
         //alert(ur);
         var editortext = $('#summernotesid').code();          
         var formData   = new FormData(form);
         formData.append('note_desc',  editortext);
          
          $("#notesaveload").css("display","inline-block");
          $("#leadnotesave").attr("type","button");
          $("#leadnotesave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    console.log(data); // show response from the php script.
                    //alert(data);
                    if(data != ''){
                      //if(data == 1)
                        //var msg = "Data Successfully Add !";
                      //else
                        var msg = "Data Successfully Save !";

                      $("#resultnote").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#leadNoteModal .close").click();
                      $("#resultnote").text(" "); 
                      $('#LeadNotesForm')[0].reset(); 
                      $("#noteid").val("");
                      $("#notesaveload").css("display","none");
                      $("#leadnotesave").css("display","inline-block");
                      $("#leadnotesave").attr("type","submit");
                    }, 1000);
                 }else{
                    $("#resultnote").text(data);
                 }
                 ajex_loadNoteData(data);
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

function ajex_loadNoteData(thsid){
  ur = "ajax_ListNotesData";
    $.ajax({
      url: ur,
      type: "POST",
      data:{'id':thsid},
      success: function(data){

        $("#ajax_content").empty().append(data).fadeIn();
        console.log(data);
     }
  });

}
function editNote(ths) {
  //console.log('edit ready !');
  //$('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.cname);
     if(button_property != 'view')
     {
     
      //$('#leadidno').val(editVal.note_id);
      $('#noteid').val(editVal.note_id);
      $('#note_name').val(editVal.note_name);
      $('#note_date').val(editVal.note_date);
      $("#summernotesid").code(editVal.note_desc);
      $("#main_id").code(editVal.main_lead_id_note);
    }  
      
      if(button_property != 'view')
      {
       $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#LeadNotesForm :input").prop("disabled", false);
      }
};

		//=============Lead File Form======================

$("#FilesLeadForm").validate({
    rules: {
       files_name: "required",
       files_desc: "required"
    },
      submitHandler: function(form) {
         //alert($('#LeadForm').serialize());
         var formData = new FormData(form);
         ur = "insert_lead_file";
         //alert(ur);

          $("#filesaveload").css("display","inline-block");
          $("#fileformsave").attr("type","button");
          $("#fileformsave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    console.log(data); // show response from the php script.
                    //alert(data);
                    if(data != ''){
                      //if(data == 1)
                        //var msg = "Data Successfully Add !";
                      //else
                        var msg = "Data Successfully Save !";

                      $("#resultfile").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#fileLeadModal .close").click();
                      $("#resultfile").text(" "); 
                      $('#FilesLeadForm')[0].reset(); 
                      $("#fileid").val("");
                      $("#filesaveload").css("display","none");
                      $("#fileformsave").css("display","inline-block");
                      $("#fileformsave").attr("type","submit");
                    }, 1000);
                 }else{
                    $("#resultfile").text(data);
                 }
                 ajex_loadFileData(data);
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

function ajex_loadFileData(thsid){
  ur = "ajax_ListFilesData";
    $.ajax({
      url: ur,
      type: "POST",
      data:{'id':thsid},
      success: function(data){

        $("#ajax_content").empty().append(data).fadeIn();
        console.log(data);
     }
  });

}

function editFiles(ths) {

  var image_url = "/"+base_urls+"/crmfiles/leadfile"+'/';
  //console.log('edit ready !');
  //$('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.cname);
     if(button_property != 'view')
     {

      $('#fileid').val(editVal.file_id);
      //$('#files_name').val(editVal.files_name);
      $('#files_desc').val(editVal.files_desc);
      $('#image').css('display','none');
      if(editVal.files_name != "")
      {
	      $('#image').attr('href',image_url+editVal.files_name);
	      $('#image').attr('title',editVal.files_name);
	      $('#image').css('display','block');
      }
     }  
      
      if(button_property != 'view')
      {
       $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#FilesLeadForm :input").prop("disabled", false);
      }
};

	// ====== Pipeline Stage Change Form===================

$("#changeStageForm").validate({
    rules: {
       //files_name: "required",
       new_stage: "required"
    },
      submitHandler: function(form) {
         //alert($('#LeadForm').serialize());
         var formData = new FormData(form);
         ur = "update_lead_stage";
         //alert(ur);

          $("#stageload").css("display","inline-block");
          $("#stagesave").attr("type","button");
          $("#stagesave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    console.log(data); // show response from the php script.
                    //alert(data);
                    if(data != ''){
                      //if(data == 1)
                        //var msg = "Data Successfully Add !";
                      //else
                        var msg = "Data Successfully Save !";

                      $("#resultstage").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#modalStage .close").click();
                      $("#resultstage").text(" "); 
                      $('#changeStageForm')[0].reset(); 
                      $("#lead_id").val("");
                      $("#stageload").css("display","none");
                      $("#stagesave").css("display","inline-block");
                      $("#stagesave").attr("type","submit");
                    }, 1000);
                 }else{
                    $("#resultstage").text(data);
                 }
                 ajex_loadStageData(data);
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

function ajex_loadStageData(thsid){
  ur = "ajax_ListStageData";
    $.ajax({
      url: ur,
      type: "POST",
      data:{'id':thsid},
      success: function(data){

        $("#ajax_content").empty().append(data).fadeIn();
        console.log(data);
     }
  });

}	 

//===Manage Page Stage========

$("#ManagechangeStageForm").validate({
    rules: {
       //files_name: "required",
       new_stage: "required"
    },
      submitHandler: function(form) {
         //alert($('#LeadForm').serialize());
         var formData = new FormData(form);
         ur = "update_lead_stage";
         //alert(ur);

          $("#stageload").css("display","inline-block");
          $("#stagesave").attr("type","button");
          $("#stagesave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    console.log(data); // show response from the php script.
                    //alert(data);
                    if(data != ''){
                      //if(data == 1)
                        //var msg = "Data Successfully Add !";
                      //else
                        var msg = "Data Successfully Save !";

                      $("#resultstage").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#modalManageStage .close").click();
                      $("#resultstage").text(" "); 
                      $('#ManagechangeStageForm')[0].reset(); 
                      $("#lead_id").val("");
                      $("#stageload").css("display","none");
                      $("#stagesave").css("display","inline-block");
                      $("#stagesave").attr("type","submit");
                    }, 1000);
                 }else{
                    $("#resultstage").text(data);
                 }
                 ajex_leadListData(data);
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

	 // ==================== Lead State Form==================

$("#LeadStateForm").validate({
    rules: {
       //files_name: "required",
       new_state: "required"
    },
      submitHandler: function(form) {
         //alert($('#LeadForm').serialize());
         var formData = new FormData(form);
         ur = "update_lead_state";
         //alert(ur);

          $("#stateload").css("display","inline-block");
          $("#statesave").attr("type","button");
          $("#statesave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    console.log(data); // show response from the php script.
                    //alert(data);
                    if(data != ''){
                      //if(data == 1)
                        //var msg = "Data Successfully Add !";
                      //else
                        var msg = "Data Successfully Save !";

                      $("#resultstate").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#leadStateModal .close").click();
                      $("#resultstate").text(" "); 
                      $('#LeadStateForm')[0].reset(); 
                      $("#lead_id").val("");
                      $("#stateload").css("display","none");
                      $("#statesave").css("display","inline-block");
                      $("#statesave").attr("type","submit");
                    }, 1000);
                 }else{
                    $("#resultstate").text(data);
                 }
                 ajex_loadStateData(data);
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

function ajex_loadStateData(thsid){
  ur = "ajax_ListStateData";
    $.ajax({
      url: ur,
      type: "POST",
      data:{'id':thsid},
      success: function(data){

        $("#ajax_content").empty().append(data).fadeIn();
        console.log(data);
     }
  });

}	 


//=============================Task Form========================================

 $("#TaskForm").validate({
      rules: {
       task_name: "required",
       due_date: "required"
      },
      submitHandler: function(form) {
        //alert($('#TaskForm').serialize());
        //var formData = new FormData(form);
        ur = "insert_task";

        var editortext = $('#summernote').code();  
         //alert(editortext);        
         var formData = new FormData(form);
         formData.append('snotes',  editortext);

          $("#saveload").css("display","inline-block");
          $("#formsave").attr("type","button");
          $("#formsave").css("display","none");

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
                  setTimeout(function() {   //calls click event after a certain time
                  $("#taskModal .close").click();
                  $("#resultarea").text(" "); 
                  $('#TaskForm')[0].reset(); 
                  $("#task_id").val("");
                  $("#saveload").css("display","none");
                  $("#formsave").css("display","inline-block");
                  $("#formsave").attr("type","submit");
                  }, 1000);
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
  //$('.error').css('display','none');
  //myfunLeadno();
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.cname);
     if(button_property != 'view')
     {
      $('#user_resp').val(editVal.user_resp).trigger('chosen:updated');
      $('#leadid').val(editVal.lead_id).trigger('chosen:updated');
     // }      
     // else
     // {
     //   $('#user_resp').val(editVal.user_resp).prop('disabled', true).trigger('chosen:updated');
     //   $('#leadid').val(editVal.lead_id).prop('disabled', true).trigger('chosen:updated');
     // }

      $('#task_id').val(editVal.task_id);
      $('#task_name').val(editVal.task_name).trigger('chosen:updated');
      $('#due_date').val(editVal.date_due);
      $('#reminder_date').val(editVal.reminder_date);
      //$('#priority').val(editVal.priority).prop('selected', true);
      $('#priority').val(editVal.priority).trigger('chosen:updated');
      $('#progress').val(editVal.progress_percnt);
      //$('#status').val(editVal.task_status).prop('selected', true);
      $('#status').val(editVal.task_status).trigger('chosen:updated'); 

      if(editVal.lead_id != ''){
        myfunLeadno(editVal.lead_id);
        // $('#contact_person').val(editVal.cname);
        // $('#contact_personid').val(editVal.contact_id);
        // $('#org_name').val(editVal.oname);
        // $('#org_nameid').val(editVal.org_id);
      }else{
        $('#contact_person').val('');
        $('#contact_personid').val('');
        $('#org_name').val('');
        $('#org_nameid').val('');
      }      

      $("#summernote").code(editVal.description);
      //$('#optionsRadios').val(editVal.visibility);
    }  
      
      if(button_property == 'view'){
       $('.top_title').html('View');
       //$('#button').css('display','none');
       //$(".chosen-select form-control").attr('disabled', true);      
       $("#TaskForm :input").prop("disabled", true);
      }else{
       $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#TaskForm :input").prop("disabled", false);
      }
};

function getViewTaskPage(thsid){
      //alert(thsid);
       window.history.pushState("CRM", "CRM", "/"+base_urls+"/task/Task/taskview/"+thsid);
      //window.history.pushState("CRM", "CRM", "useredit/"+thsid);
        var ur = "taskview";
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


    //=================Task Note Form===================

  $("#TaskNotesForm").validate({
    rules: {
       note_name: "required",
       note_date: "required"
    },
      submitHandler: function(form) {
         //alert($('#LeadForm').serialize());
         //var formData = new FormData(form);
         ur = "insert_task_note";
         //alert(ur);
         var editortext = $('#summernotesid').code();          
         var formData   = new FormData(form);
         formData.append('note_desc',  editortext);
          
          $("#tasknoteload").css("display","inline-block");
          $("#tasknotesave").attr("type","button");
          $("#tasknotesave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    console.log(data); // show response from the php script.
                    //alert(data);
                    if(data != ''){
                      //if(data == 1)
                        //var msg = "Data Successfully Add !";
                      //else
                        var msg = "Data Successfully Save !";

                      $("#resultnote").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#taskNoteModal .close").click();
                      $("#resultnote").text(" "); 
                      $('#TaskNotesForm')[0].reset(); 
                      $("#noteid").val("");
                      $("#tasknoteload").css("display","none");
                      $("#tasknotesave").css("display","inline-block");
                      $("#tasknotesave").attr("type","submit");
                    }, 1000);
                 }else{
                    $("#resultnote").text(data);
                 }
                 ajex_taskNoteData(data);
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

function ajex_taskNoteData(thsid){
  ur = "ajax_ListTaskNotes";
    $.ajax({
      url: ur,
      type: "POST",
      data:{'id':thsid},
      success: function(data){

        $("#ajax_content").empty().append(data).fadeIn();
        console.log(data);
     }
  });

}
function editTaskNote(ths) {
  //console.log('edit ready !');
  //$('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.cname);
     if(button_property != 'view')
     {
     
      //$('#leadidno').val(editVal.note_id);
      $('#noteid').val(editVal.note_id);
      $('#note_name').val(editVal.note_name);
      $('#note_date').val(editVal.note_date);
      $("#summernotesid").code(editVal.note_desc);
    }  
      
      if(button_property != 'view')
      {
       $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#TaskNotesForm :input").prop("disabled", false);
      }
};


//=============Task File Form======================

$("#FilesTaskForm").validate({
    rules: {
       files_name: "required",
       files_desc: "required"
    },
      submitHandler: function(form) {
         //alert($('#LeadForm').serialize());
         var formData = new FormData(form);
         ur = "insert_task_file";
         //alert(ur);

          $("#filetaskload").css("display","inline-block");
          $("#filetasksave").attr("type","button");
          $("#filetasksave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    console.log(data); // show response from the php script.
                    //alert(data);
                    if(data != ''){
                      //if(data == 1)
                        //var msg = "Data Successfully Add !";
                      //else
                        var msg = "Data Successfully Save !";

                      $("#resultfile").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#fileTaskModal .close").click();
                      $("#resultfile").text(" "); 
                      $('#FilesTaskForm')[0].reset(); 
                      $("#fileid").val("");
                      $("#filetaskload").css("display","none");
                      $("#filetasksave").css("display","inline-block");
                      $("#filetasksave").attr("type","submit");
                    }, 1000);
                 }else{
                    $("#resultfile").text(data);
                 }
                 ajex_loadFileTaskData(data);
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

function ajex_loadFileTaskData(thsid){
  ur = "ajax_taskFilesData";
    $.ajax({
      url: ur,
      type: "POST",
      data:{'id':thsid},
      success: function(data){

        $("#ajax_content").empty().append(data).fadeIn();
        console.log(data);
     }
  });

}

function editTaskFiles(ths) {

  var image_url = "/"+base_urls+"/crmfiles/taskfile"+'/';
  //console.log('edit ready !');
  //$('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.cname);
     if(button_property != 'view')
     {

      $('#fileid').val(editVal.file_id);
      //$('#files_name').val(editVal.files_name);
      $('#files_desc').val(editVal.files_desc);
      $('#image').css('display','none');
      if(editVal.files_name != "")
      {
	      $('#image').attr('href',image_url+editVal.files_name);
	      $('#image').attr('title',editVal.files_name);
	      $('#image').css('display','block');
      }
     }  
      
      if(button_property != 'view')
      {
       $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#FilesTaskForm :input").prop("disabled", false);
      }
};


//==================Task Status Change===========

$("#changeTaskStatusForm").validate({
      rules: {
       new_status: "required",
       // due_date: "required"
      },
      submitHandler: function(form) {
        ur = "update_task_status";
         var formData = new FormData(form);
          $("#task_load").css("display","inline-block");
          $("#tasks_save").attr("type","button");
          $("#tasks_save").css("display","none");

          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#TaskForm').serialize(), // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  if(data != ''){
                    // if(data == 1)
                    //   var msg = "Data Successfully Add !";
                    // else
                      var msg = "Data Successfully Save !";
                  $("#resultstatus").text(msg); 
                  setTimeout(function() {   //calls click event after a certain time
                  $("#modalTaskStatus .close").click();
                  $("#resultstatus").text(" "); 
                  $('#changeTaskStatusForm')[0].reset(); 
                  $("#task_id").val("");
                  $("#task_load").css("display","none");
                  $("#tasks_save").css("display","inline-block");
                  $("#tasks_save").attr("type","submit");
                  }, 1000);
                }else{
                    $("#resultstatus").text(data);
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

//================================ Master form===============================

  
  $("#MasterForm").validate({
      rules: {
        //key_name:"required",
       key_value:"required"
      },
      submitHandler: function(form) {
        //alert($('#MasterForm').serialize());
          ur = "insert_master_data";
          var formData = new FormData(form);

          $("#mastersaveload").css("display","inline-block");
          $("#masterformsave").attr("type","button");
          $("#masterformsave").css("display","none");

          $.ajax({
              type : "POST",
              url  :  ur,
              data :  formData, // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  if(data != ''){
                      // if(data == 1)
                      //   var msg = "Data Successfully Add !";
                      // else
                        var msg = "Data Successfully Save !";

                      $("#resultareas").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#masterModal .close").click();
                      $("#resultareas").text(" "); 
                      $('#MasterForm')[0].reset(); 
                      $("#serial_number").val("");
                      $("#mastersaveload").css("display","none");
                      $("#masterformsave").css("display","inline-block");
                      $("#masterformsave").attr("type","submit");
                     }, 1000);
                   }else{
                      $("#resultareas").text(data);
                   }
                      ajex_masterListData(data);
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

function ajex_masterListData(thsid){
  ur = "ajax_ListMasterData";
    $.ajax({
      url: ur,
      type: "POST",
      data:{'id':thsid},
      success: function(data){
        //alert(data);
       $("#listingMasterData").empty().append(data).fadeIn();
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
     { 
      $('#serial_number').val(editVal.serial_number);
      $('#key_name').val(editVal.param_id).prop('selected', true);
      $('#paramid').val(editVal.param_id);
      $('#key_value').val(editVal.keyvalue);
      //$('#desc').val(editVal.description);
     } 

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



//================================ Branch form===============================

  
  $("#BranchForm").validate({
      rules: {
        //key_name:"required",
       branch_name:"required"
      },
      submitHandler: function(form) {
        //alert($('#MasterForm').serialize());
          ur = "insert_branch_data";
          var formData = new FormData(form);

          $("#branchload").css("display","inline-block");
          $("#branchsave").attr("type","button");
          $("#branchsave").css("display","none");

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
                        var msg = "Data Successfully Update !";

                      $("#resultarea").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#branchModal .close").click();
                      $("#resultarea").text(" "); 
                      $('#BranchForm')[0].reset(); 
                      $("#branch_id").val("");
                      $("#branchload").css("display","none");
                      $("#branchsave").css("display","inline-block");
                      $("#branchsave").attr("type","submit");
                     }, 1000);
                   }else{
                      $("#resultarea").text(data);
                   }
                      ajex_BranchData();
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

function ajex_BranchData(){
  ur = "ajax_ListBranchData";
    $.ajax({
      url: ur,
      type: "POST",
      //data:{'id':thsid},
      success: function(data){
        //alert(data);
       $("#listBranchData").empty().append(data).fadeIn();
        //console.log(data);
     }
  });

}


function editBranch(ths) {
  //console.log('edit ready !');
  $('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.contact_id);
    if(button_property != 'view')
     { 
      $('#branch_id').val(editVal.brnh_id);
      $('#branch_code').val(editVal.brnh_code);
      $('#branch_name').val(editVal.brnh_name);
     } 

      if(button_property == 'view'){
      $('.top_title').html('View');
       //$('#button').css('display','none');
       $("#BranchForm :input").prop("disabled", true);
      }else{
      $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#BranchForm :input").prop("disabled", false);
      }
};


//========================end=======================


/////////////////////////////   user form start ////////////////////////////
  $.validator.setDefaults({ ignore: ":hidden:not(.chosen-select)" })
  $("#UserForm").validate({
      rules: {
        name:'required',
        email:'required',
        user_pro : { required: true },
        branch : { required: true },
      },
      submitHandler: function(form) {
          //alert($('#UserForm').serialize());
          ur = "insertuserform";
          var formData = new FormData(form);

          $("#userload").css("display","inline-block");
          $("#usersave").attr("type","button");
          $("#usersave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
              data :  formData, // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  if(data == 1 || data == 2){
                      if(data == 1)
                        var msg = "Data Successfully Add ! Check Your Mail For Login Details";
                      else
                        var msg = "Data Successfully Updated !";

                      $("#resultarea").text(msg); 
                       setTimeout(function() {   //calls click event after a certain time
                        $("#myModal .close").click();
                        $("#resultarea").text(" "); 
                        $('#UserForm')[0].reset(); 
                        $("#userload").css("display","none");
                        $("#usersave").css("display","inline-block");
                        $("#usersave").attr("type","submit");
                     }, 2000);
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


function editUser(ths) {
  //console.log('edit ready !');
  //$('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.brnh_usr);
    if(button_property != 'view')
     {
      $('#user_id').val(editVal.user_id); 
      $('#name').val(editVal.user_name);
      $('#email').val(editVal.email_id).prop('readonly', true);
      $('#user_pro').val(editVal.profile_user).trigger('chosen:updated');
      $('#branch').val(editVal.brnh_id).trigger('chosen:updated');
     } 

      if(button_property != 'view'){     
       $('.top_title').html('Update');
       $("#UserForm :input").prop("disabled", false);
      }
};




//=====================Change User Password========
  $("#ChangePasswordForm").validate({
      rules: {
        usr_new_pass:'required',
        usr_cnf_pass:'required',
      },
      submitHandler: function(form) {
          //alert($('#UserForm').serialize());
          ur = "changeuserpassword";
          var formData = new FormData(form);

          $("#saveload").css("display","inline-block");
          $("#saveUser").attr("type","button");
          $("#saveUser").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
              data :  formData, // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  if(data == 1 || data == 2){
                      if(data == 1)
                        var msg = "Password Change Successfully!";
                      else
                        var msg = "Data Successfully Updated !";

                      $("#resultpassword").text(msg); 
                       setTimeout(function() {   //calls click event after a certain time
                        $("#passwordModal .close").click();
                        $("#resultpassword").text(" "); 
                        $('#ChangePasswordForm')[0].reset(); 
                        $("#saveload").css("display","none");
                        $("#saveUser").css("display","inline-block");
                        $("#saveUser").attr("type","submit");     
                     }, 1000);
                   }else{
                      $("#resultpassword").text(data);
                   }
                    //getsettingpage();
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

//////////////////////////////////////  getsettingpage    ///////////////////////////////


    function getsettingpage(){
       // alert('sdfsdf');
        // if(url == false)
        window.history.pushState("CRM", "CRM", "/"+base_urls+"/master/Userdetails/setting_user");

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
      window.history.pushState("CRM", "CRM", "/"+base_urls+"/master/Userdetails/userview/"+thsid);
      //window.history.pushState("CRM", "CRM", "useredit/"+thsid);
        var ur = "userview";
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
   window.history.pushState("CRM", "CRM", "/"+base_urls+"/master/Userdetails/useredit/"+thsid);
   var ur = "useredit";
    $.ajax({
      type:"POST",
      url:ur,
      data:{'id':thsid},
        success:function(data){
        //console.log(data);
        $('#ajax_content').empty().append(data);
        //$("#organizations").val("ggg").trigger('chosen:updated');
        //$('#organizations').trigger("chosen:updated");
      }
    });
  }



//===========================Mutiple Organization Lead========================


$("#MultiOrgzForm").validate({
      rules: {
       dorg_name: "required",
       dwebsite:  "required"
      },
      submitHandler: function(form) {
        //alert($('#MultiOrgzForm').serialize());
        //var formData = new FormData(form);
        ur = "insert_multi_orgz";
        
         var editortext = $('#multi_org_notes').code();
         var formData = new FormData(form);
         formData.append('org_notes',  editortext);

          $("#morgload").css("display","inline-block");
          $("#morgsave").attr("type","button");
          $("#morgsave").css("display","none");
        
          $.ajax({
              type : "POST",
              url  :  ur,
              //dataType : 'json', // Notice json here
              data : formData, // serializes the form's elements.
              //data : $('#OrganizationForm').serialize(), // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  if(data != ''){
                    // if(data == 1)
                    //   var msg = "Data Successfully Add !";
                    // else
                      var msg = "Data Successfully Save !";
                 
                    $("#resultmultiorg").text(msg); 
                    setTimeout(function() {   //calls click event after a certain time
                    $("#mutiOrgModal .close").click();
                    $("#resultmultiorg").text(" "); 
                    $('#MultiOrgzForm')[0].reset(); 
                    $("#omorgid").val("");
                    $("#morgload").css("display","none");
                    $("#morgsave").css("display","inline-block");
                    $("#morgsave").attr("type","submit");
                  }, 1000);
                }else{
                    $("#resultmultiorg").text(data);
                 }
                 ajex_multiOrgData(data);
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

function ajex_multiOrgData(thsid){
  ur = "ajax_ListMultiOrgData";
    $.ajax({
      url: ur,
      type: "POST",
      data:{'id':thsid},
      success: function(data){
        $("#ajax_content").empty().append(data).fadeIn();
        //console.log(data);
     }
  });

}


function editMultiOrgz(ths) 
{
  //console.log('edit ready !');
  $('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
    if(button_property != 'view')
    {
      $('#morgid').val(editVal.morg_id);
      $('#multi_org').val(editVal.orgid).trigger('chosen:updated');
      $('#org_details').val(editVal.morg_details);
    }  
      
      if(button_property == 'edit'){
      $('.top_title').html('Edit');
       $("#MultiOrgzForm :input").prop("disabled", false);
      }
};



//===========================Mutiple Contact Lead========================


$("#MultiCntForm").validate({
      rules: {
       mcontact_name: "required",
       mdesignation:"required"
      },
      submitHandler: function(form) {
        //alert($('#TaskForm').serialize());
        //var formData = new FormData(form);
        ur = "insert_multi_cntct";
        //var textareaValue = $("#summernote").code();
         var editortext = $('#multi_cnt_notes').code();  
         //alert(editortext);        
         var formData = new FormData(form);
         formData.append('cnt_notes',  editortext);

          $("#mcntload").css("display","inline-block");
          $("#mcntsave").attr("type","button");
          $("#mcntsave").css("display","none");
        
          $.ajax({
              type : "POST",
              url  :  ur,
              //dataType : 'json', // Notice json here
              data : formData, // serializes the form's elements.
              //data : $('#OrganizationForm').serialize(), // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  if(data != ''){
                    // if(data == 1)
                    //   var msg = "Data Successfully Add !";
                    // else
                      var msg = "Data Successfully Save !";
                 
                    $("#resultmulticnt").text(msg); 
                    setTimeout(function() {   //calls click event after a certain time
                    $("#mutiCntModal .close").click();
                    $("#resultmulticnt").text(" "); 
                    $('#MultiCntForm')[0].reset(); 
                    $("#cmorgid").val("");
                    $("#mcntload").css("display","none");
                    $("#mcntsave").css("display","inline-block");
                    $("#mcntsave").attr("type","submit");
                  }, 1000);
                }else{
                    $("#resultmulticnt").text(data);
                 }
                 ajex_multiCntData(data);
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

function ajex_multiCntData(thsid){
  ur = "ajax_ListMultiCntData";
    $.ajax({
      url: ur,
      type: "POST",
      data:{'id':thsid},
      success: function(data){
        $("#ajax_content").empty().append(data).fadeIn();
        //console.log(data);
     }
  });

}


function editMultiOrgzCnt(ths) 
{
  //console.log('edit ready !');
  $('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
    if(button_property != 'view')
    {
      $('#morgid').val(editVal.morg_id);
      $('#multi_org').val(editVal.orgid).trigger('chosen:updated');
      $('#org_details').val(editVal.morg_details);
    }  
      
      if(button_property == 'edit'){
      $('.top_title').html('Edit');
       $("#MultiOrgzCntForm :input").prop("disabled", false);
      }
};


//=======================Inner Page Edit Lead=================

$("#LeadEditForm").validate({
    rules: {
       contact: "required",
       org_name:"required"
    },
      submitHandler: function(form) {
         //alert($('#LeadForm').serialize());
         //var formData = new FormData(form);
         ur = "insert_lead_inner";

         var editortext = $('#summernote').code();          
         var formData   = new FormData(form);
         formData.append('summernote',  editortext);
          $("#saveload").css("display","inline-block");
          $("#formsave").attr("type","button");
          $("#formsave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    console.log(data); // show response from the php script.
                    
                    if(data != ''){

                        var msg = "Data Successfully Save !";

                      $("#resultarea").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#leadEditModal .close").click();
                      $("#resultarea").text(" "); 
                      $('#LeadEditForm')[0].reset(); 
                      $("#lead_id").val("");
                      $("#saveload").css("display","none");
                      $("#formsave").css("display","inline-block");
                      $("#formsave").attr("type","submit");
                    }, 1000);
                 }else{
                    $("#resultarea").text(data);
                 }
                 ajex_leadInnerList(data);
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

function ajex_leadInnerList(thisid)
{
  ur = "ajax_innerLeadData";
  $.ajax({
    url : ur,
    type : "POST",
    data : {'id' : thisid},
    success : function(data){
      $("#ajax_content").empty().append(data).fadeIn();
    }

    });
  
}
function editInnerLead(ths) 
{

  //console.log('edit ready !');
  //$('.error').css('display','none');
  $('.project_images').remove();
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.contact_id);
    if(button_property != 'view')
    {  
      $('#lead_idz').val(editVal.lead_id);
      $('#oldcontact').val(editVal.contact_id);
      $('#orgidcontant').val(editVal.org_id);
      $('#contacttypahead').val(editVal.contact_name);
      $('#org_name').val(editVal.org_name);
      $('#lead_number').val(editVal.lead_number);
      $('#assin_user').val(editVal.user_resp).trigger('chosen:updated');


       if(editVal.cemail != "")
       {
        j_email = JSON.parse(editVal.cemail);
           if(j_email != ""){
            for(var i=0;i<j_email.length;i++){
              if(i == 0)
                $('#email_id0').val(j_email[0]);
              else
                addRowCompemail(j_email[i],i);

         }
        }
      }

       if(editVal.cphone != ""){
        j_phone = JSON.parse(editVal.cphone);
        if(j_phone != ""){
        for(var i=0;i<j_phone.length;i++){
          if(i == 0)
           $('#phone_no0').val(j_phone[0]);
          else
           addRowComphone(j_phone[i],i);

          }
        }
      }
     
      $('#address').val(editVal.caddress);
   
      $('#industry').val(editVal.industry).trigger('chosen:updated');
      //$('#no_of_emp').val(editVal.no_of_emp);
      $('#source').val(editVal.source).trigger('chosen:updated');      
      $('#stage').val(editVal.stage).trigger('chosen:updated');
      $('#probability').val(editVal.probability);
      $('#closuredate').val(editVal.closuredate);
      $('#opp_value').val(editVal.opp_value);
      $('#summernote').code(editVal.discription);

    }

      if(button_property != 'view'){
       $('.top_title').html('Edit');
       $("#LeadEditForm :input").prop("disabled", false);
      }
};


//===================Inner Page Edit Contact================
  
  $("#ContactEditForm").validate({
    rules: {
        contact_name: "required",
       designation: "required"
      },
      submitHandler: function(form) {
        //alert($('#ContactForm').serialize());
          ur = "insert_contact_inner";
          var editortext = $('#summernote').code();          
          var formData = new FormData(form);
          formData.append('summernote',  editortext);
          //alert(formData);
          $("#saveload").css("display","inline-block");
          $("#formsave").attr("type","button");
          $("#formsave").css("display","none");

          $.ajax({
              type : "POST",
              url  :  ur,
              data :  formData, // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  
                  if(data != ''){

                      var msg = "Data Successfully Save !";

                      //alert(msg);
                      $("#resultarea").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#contactEditModal .close").click();
                      $("#resultarea").text(" "); 
                      $('#ContactEditForm')[0].reset(); 
                      $("#contact_id").val("");
                      $("#saveload").css("display","none");
                      $("#formsave").css("display","inline-block");
                      $("#formsave").attr("type","submit");
                      }, 1000);
                   }else{
                      $("#resultarea").text(data);
                   }
                      ajex_contactEditData(data);
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

function ajex_contactEditData(thisid){
  ur = "ajax_InnerContactPage";
    $.ajax({
      url: ur,
      type: "POST",
      data : {'id':thisid},
      success: function(data){
       $("#ajax_content").empty().append(data).fadeIn();
        //console.log(data);
     }
  });

}


function editContactInner(ths) 
{

  //console.log('edit ready !');
  $('.project_images').remove();
  //$('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.contact_id);
    if(button_property != 'view')
    {
      $('#newid').css("display", "none");
      $('#oldcontact_id').val(editVal.contact_id);
      $('#contact_name').val(editVal.contact_name);      
      $('#designation').val(editVal.designation);

      if(editVal.email != "")
      {
        j_email = JSON.parse(editVal.email)
        if(j_email != "")
        {
          for(i=0; i<j_email.length; i++)
          {
            if(i==0)
              $('#email_id0').val(j_email[0])
            else
              addRowCntemail(j_email[i],i);

          }
        }
      }

      if(editVal.phone != "")
      {
        j_phone = JSON.parse(editVal.phone)
        if(j_phone != "")
        {
          for(i=0; i<j_phone.length; i++)
          {
            if(i==0)
              $('#phone_no0').val(j_phone[0])
            else
              addRowCntphone(j_phone[i],i);
          }
        }
      }

      $('#address').val(editVal.address);
      $('#city').val(editVal.city_name);
      $('#pin_code').val(editVal.pincode);      
      $('#state_id').val(editVal.state_id).trigger('chosen:updated');
      $('#country_id').val(editVal.country).trigger('chosen:updated');
      $("#summernote").code(editVal.description);

      $('#newidorg').css("display", "none");
      $('#oldorgid').val(editVal.org_name);
      if(editVal.org_name !='')
      {
        cnt_orgzData(editVal.org_name);
      }
      else
      {
        $("#org_name").val('');
        $("#website").val('');
        $("#oemail_id0").val('');
        $("#ophone_no0").val('');
      }

    }

      if(button_property != 'view')
      {
        $('.top_title').html('Edit');
        $("#ContactEditForm :input").prop("disabled", false);
      }
};

//=====================Inner Page Edit Task======================

$("#TaskEditForm").validate({
      rules: {
       task_name: "required",
       due_date: "required"
      },
      submitHandler: function(form) {
        //alert($('#TaskForm').serialize());
        //var formData = new FormData(form);
        ur = "insert_task_inner";

        var editortext = $('#summernote').code();  
         //alert(editortext);        
         var formData = new FormData(form);
         formData.append('snotes',  editortext);

          $("#saveload").css("display","inline-block");
          $("#formsave").attr("type","button");
          $("#formsave").css("display","none");

          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#TaskForm').serialize(), // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  if(data != ''){
                      var msg = "Data Successfully Save !";
                  $("#resultarea").text(msg); 
                  setTimeout(function() {   //calls click event after a certain time
                  $("#taskEditModal .close").click();
                  $("#resultarea").text(" "); 
                  $('#TaskEditForm')[0].reset(); 
                  $("#task_id").val("");
                  $("#saveload").css("display","none");
                  $("#formsave").css("display","inline-block");
                  $("#formsave").attr("type","submit");
                  }, 1000);
                }else{
                    $("#resultarea").text(data);
                 }
                 ajex_taskInnerData(data);
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

function ajex_taskInnerData(thisid){
  ur = "ajax_editInnerData";
    $.ajax({
      url: ur,
      type: "POST",
      data : {'id':thisid},
      success: function(data){

        $("#ajax_content").empty().append(data).fadeIn();
        //console.log(data);
     }
  });

}


function editTaskInner(ths) 
{
  //console.log('edit ready !');
  $('.project_images').remove();
  //$('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.cname);
     if(button_property != 'view')
     {
      $('#user_resp').val(editVal.user_resp).trigger('chosen:updated');
      $('#leadid').val(editVal.lead_id).trigger('chosen:updated');
      $('#task_id').val(editVal.task_id);
      $('#task_name').val(editVal.task_name).trigger('chosen:updated');
      $('#due_date').val(editVal.date_due);
      $('#reminder_date').val(editVal.reminder_date);
      //$('#priority').val(editVal.priority).prop('selected', true);
      $('#priority').val(editVal.priority).trigger('chosen:updated');
      $('#progress').val(editVal.progress_percnt);
      //$('#status').val(editVal.task_status).prop('selected', true);      
      $('#status').val(editVal.task_status).trigger('chosen:updated');      
      $('#contact_person').val(editVal.contact_name);
      $('#contact_personid').val(editVal.contact_id);
      $('#org_name').val(editVal.org_name);
      $('#org_nameid').val(editVal.org_id);
      $("#summernote").code(editVal.description);
      //$('#optionsRadios').val(editVal.visibility);
    }  
      
      if(button_property != 'view'){
       $('.top_title').html('Edit');
       $("#TaskEditForm :input").prop("disabled", false);
      }
};


//=================Inner Page Edit Organization=============================

$("#OrgzEditForm").validate({
      rules: {
       org_name: "required",
       phone_no:"required"
      },
      submitHandler: function(form) {
        //alert($('#TaskForm').serialize());
        //var formData = new FormData(form);
        ur = "insert_orgz_inner";
        //var textareaValue = $("#summernote").code();
         var editortext = $('#summernote').code();  
         //alert(editortext);        
         var formData = new FormData(form);
         formData.append('snotes',  editortext);

          $("#saveload").css("display","inline-block");
          $("#formsave").attr("type","button");
          $("#formsave").css("display","none");
        
          $.ajax({
              type : "POST",
              url  :  ur,
              //dataType : 'json', // Notice json here
              data : formData, // serializes the form's elements.
              //data : $('#OrganizationForm').serialize(), // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  if(data != ''){

                      var msg = "Data Successfully Save !";
                 
                    $("#resultarea").text(msg); 
                    setTimeout(function() {   //calls click event after a certain time
                    $("#orgEditModal .close").click();
                    $("#resultarea").text(" "); 
                    $('#OrgzEditForm')[0].reset(); 
                    $("#org_id").val("");
                    $("#saveload").css("display","none");
                    $("#formsave").css("display","inline-block");
                    $("#formsave").attr("type","submit");
                  }, 1000);
                }else{
                    $("#resultarea").text(data);
                 }
                 ajex_orgInnerData(data);
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

function ajex_orgInnerData(thisid){
  ur = "ajax_OrgEditData";
  //alert(thisid);
    $.ajax({
      url: ur,
      type: "POST",
      data:{'id':thisid},
      success: function(data){
        $("#ajax_content").empty().append(data).fadeIn();
        //console.log(data);
     }
  });

}


function editOrgzInner(ths) 
{

  //console.log('edit ready !');
  $('.project_images').remove();
  //$('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
     ///alert(editVal.description);
    if(button_property != 'view')
    {

      $('#newidorg').css("display", "none");
      $('#oldorgid').val(editVal.org_id);
      $('#org_name').val(editVal.org_name);
      $('#website').val(editVal.website);

      if(editVal.email != "")
      {
        j_email = JSON.parse(editVal.email);
           if(j_email != ""){
            for(var i=0;i<j_email.length;i++){
              if(i == 0)
                $('#email_id0').val(j_email[0]);
              else
                addRowOrgEmail(j_email[i],i);

         }
        }
      }

       if(editVal.phone_no != ""){
        j_phone = JSON.parse(editVal.phone_no);
        if(j_phone != ""){
        for(var i=0;i<j_phone.length;i++){
          if(i == 0)
           $('#phone_no0').val(j_phone[0]);
          else
           addRowOrgPhone(j_phone[i],i);

          }
        }
      }

      $('#address').val(editVal.address);
      $('#city').val(editVal.city);      
      $('#pin_code').val(editVal.pin_code);
      $('#country_id').val(editVal.country).trigger('chosen:updated');
      $('#state_id').val(editVal.state_id).trigger('chosen:updated');      
      $("#summernote").code(editVal.description); 

      $('#newid').css("display", "none");
      $('#oldcontact_id').val(editVal.contact_id);
       if(editVal.contact_id != '')
       {
         org_contactData(editVal.contact_id);
       }
       else
       {
        $("#contact_name").val('');
        $("#designation").val('');
        $("#cemail_id0").val('');
        $("#cphone_no0").val('');
       }

    }  
      
      if(button_property != 'view'){
      $('.top_title').html('Edit');
       $("#OrgzEditForm :input").prop("disabled", false);
      }
};


///////////////////===========Lead Task Inner Page=======================////////

$("#TaskLeadForm").validate({
      rules: {
       task_name: "required",
       due_date: "required"
      },
      submitHandler: function(form) {
        //alert($('#TaskForm').serialize());
        //var formData = new FormData(form);
        ur = "insert_task_lead";

        var editortext = $('#summernotess').code();  
         //alert(editortext);        
         var formData = new FormData(form);
         formData.append('snotes',  editortext);

          $("#taskload").css("display","inline-block");
          $("#tasksave").attr("type","button");
          $("#tasksave").css("display","none");

          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#TaskForm').serialize(), // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  if(data != ''){      
                      var msg = "Data Successfully Save !";
                  $("#resultstask").text(msg); 
                  setTimeout(function() {   //calls click event after a certain time
                  $("#taskLeadModal .close").click();
                  $("#resultstask").text(" "); 
                  $('#TaskLeadForm')[0].reset(); 
                  $("#task_id").val("");
                  $("#taskload").css("display","none");
                  $("#tasksave").css("display","inline-block");
                  $("#tasksave").attr("type","submit");
                  }, 1000);
                }else{
                    $("#resultstask").text(data);
                 }
                 ajex_leadInnerTask(data);
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

function ajex_leadInnerTask(thisid)
{
  ur = "ajax_getInnerLeadTask";
  $.ajax({
    url : ur,
    type : "POST",
    data : {'id': thisid},
    success : function(data){
      $("#ajax_content").empty().append(data);
    }

  });
}

//===========================Manage page lead task=========================

$("#LeadTaskForm").validate({
      rules: {
       task_name: "required",
       due_date: "required"
      },
      submitHandler: function(form) {
        //alert($('#TaskForm').serialize());
        //var formData = new FormData(form);
        ur = "insert_task_lead";

        var editortext = $('#summernotess').code();  
         //alert(editortext);        
         var formData = new FormData(form);
         formData.append('snotes',  editortext);

          $("#taskload").css("display","inline-block");
          $("#tasksave").attr("type","button");
          $("#tasksave").css("display","none");

          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#TaskForm').serialize(), // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  if(data != ''){      
                      var msg = "Data Successfully Save !";
                  $("#resultstask").text(msg); 
                  setTimeout(function() {   //calls click event after a certain time
                  $("#leadTaskModal .close").click();
                  $("#resultstask").text(" "); 
                  $('#LeadTaskForm')[0].reset(); 
                  $("#task_id").val("");
                  $("#taskload").css("display","none");
                  $("#tasksave").css("display","inline-block");
                  $("#tasksave").attr("type","submit");
                  }, 1000);
                }else{
                    $("#resultstask").text(data);
                 }
                 //ajex_leadInnerTask(data);
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



///////////////////====================Manage Page Lead Assign To=====================///////////////

$("#LeadAssignForm").validate({
    rules: { 
      //assign_user: "required", 
     },

      submitHandler: function(form) {
         //alert($('#LeadForm').serialize());
         //var formData = new FormData(form);
         ur = "update_lead_assignto";               
         var formData   = new FormData(form);

          $("#userload").css("display","inline-block");
          $("#usersave").attr("type","button");
          $("#usersave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    console.log(data); // show response from the php script.
                    
                    if(data != ''){
      
                        var msg = "Data Successfully Save !";

                      $("#resultuser").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#userAssignModal .close").click();
                      $("#resultuser").text(" "); 
                      $('#LeadAssignForm')[0].reset(); 
                      $("#lead_id").val("");
                      $("#userload").css("display","none");
                      $("#usersave").css("display","inline-block");
                      $("#usersave").attr("type","submit");
                    }, 1000);
                 }else{
                      $("#resultuser").text(' Select User First! ');
                      $("#userload").css("display","none");
                      $("#usersave").css("display","inline-block");
                      $("#usersave").attr("type","submit");
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


////=========================Manage Page Task Assign To=========================

$("#TaskAssignForm").validate({
    rules: { 
      //assign_user: "required", 
     },

      submitHandler: function(form) {
         //alert($('#LeadForm').serialize());
         //var formData = new FormData(form);
         ur = "update_task_assignto";               
         var formData   = new FormData(form);

          $("#userload").css("display","inline-block");
          $("#usersave").attr("type","button");
          $("#usersave").css("display","none");
          
          $.ajax({
              type : "POST",
              url  :  ur,
                //dataType : 'json', // Notice json here
                data : formData, // serializes the form's elements.
                //data : $('#LeadForm').serialize(), // serializes the form's elements.
                success : function (data) {
                    console.log(data); // show response from the php script.
                    
                    if(data != ''){
      
                        var msg = "Data Successfully Save !";

                      $("#resultuser").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#taskAssignModal .close").click();
                      $("#resultuser").text(" "); 
                      $('#TaskAssignForm')[0].reset(); 
                      $("#task_id").val("");
                      $("#userload").css("display","none");
                      $("#usersave").css("display","inline-block");
                      $("#usersave").attr("type","submit");
                    }, 1000);
                 }else{
                      $("#resultuser").text(' Select User First! ');
                      $("#userload").css("display","none");
                      $("#usersave").css("display","inline-block");
                      $("#usersave").attr("type","submit");
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




//////============================= Header All Module ====================/////////////


          //===============Orgnization Header========

$("#HdrOrgzForm").validate({
      rules: {
       org_name: "required",
       phone_no:"required"
      },
      submitHandler: function(form) {
        //var formData = new FormData(form);
        ur = "/"+base_urls+"/organization/Organization/insert_organization"
        //ur = "<?= base_url('master/master/insert_orgz_hdr')?>";
         var editortext = $('#summernoteorgz').code();  
         //alert(editortext);        
         var formData = new FormData(form);
         formData.append('snotes',  editortext);

          $("#saveloadorghdr").css("display","inline-block");
          $("#formsaveorghdr").attr("type","button");
          $("#formsaveorghdr").css("display","none");
        
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
                 
                    $("#resultareaorghdr").text(msg); 
                    setTimeout(function() {   //calls click event after a certain time
                    $("#HdrOrgzModal .close").click();
                    $("#resultareaorghdr").text(" "); 
                    $('#HdrOrgzForm')[0].reset(); 
                    $("#saveloadorghdr").css("display","none");
                    $("#formsaveorghdr").css("display","inline-block");
                    $("#formsaveorghdr").attr("type","submit");
                  }, 1000);
                }else{
                    $("#resultareaorghdr").text(data);
                 }
                 //ajex_orgListData();
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


      //===============Contact Header========

$("#CntHdrForm").validate({
    rules: {
        contact_name: "required",
       designation: "required"
      },
      submitHandler: function(form) {
          ur = "/"+base_urls+"/contact/Contact/insert_contact";
          var editortext = $('#summernotecnt').code();          
          var formData = new FormData(form);
          formData.append('summernote',  editortext);
          //alert(formData);
          $("#saveloadcnt").css("display","inline-block");
          $("#formsavecnt").attr("type","button");
          $("#formsavecnt").css("display","none");

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

                      //alert(msg);
                      $("#resultareacnthdr").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#CntHdrModal .close").click();
                      $("#resultareacnthdr").text(" "); 
                      $('#CntHdrForm')[0].reset(); 
                      $("#saveloadcnt").css("display","none");
                      $("#formsavecnt").css("display","inline-block");
                      $("#formsavecnt").attr("type","submit");
                      }, 1000);
                   }else{
                      $("#resultareacnthdr").text(data);
                   }
                      //ajex_contactListData();
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










  
//================================ Lead Rates form===============================

  
  $("#LeadRatesForm").validate({
      rules: {
        lead_from:"required",
       lead_rates:"required"
      },
      submitHandler: function(form) {
        //alert($('#MasterForm').serialize());
          ur = "insert_lead_rates";
          var formData = new FormData(form);

          $("#rateload").css("display","inline-block");
          $("#ratesave").attr("type","button");
          $("#ratesave").css("display","none");

          $.ajax({
              type : "POST",
              url  :  ur,
              data :  formData, // serializes the form's elements.
                success : function (data) {
                  //alert(data); // show response from the php script.
                  if(data != '' ){
                      //if(data == 1)
                        var msg = "Data Successfully Save !";
                      // else
                      //   var msg = "Data Successfully Update !";

                      $("#resultrate").text(msg); 
                      setTimeout(function() {   //calls click event after a certain time
                      $("#modalRates .close").click();
                      $("#resultrate").text(" "); 
                      $('#LeadRatesForm')[0].reset(); 
                      $("#rateid").val("");
                      $("#rateload").css("display","none");
                      $("#ratesave").css("display","inline-block");
                      $("#ratesave").attr("type","submit");
                     }, 1000);
                   }else{
                      $("#resultrate").text(data);
                   }
                      ajex_RateData(data);
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

function ajex_RateData(thsid){
  ur = "ajax_LeadRateData";
    $.ajax({
      url: ur,
      type: "POST",
      data:{'id':thsid},
      success: function(data){
        //alert(data);
       $("#ajax_content").empty().append(data).fadeIn();
        //console.log(data);
     }
  });

}


function editRate(ths) {
  //console.log('edit ready !');
  $('.error').css('display','none');
  var rowValue = $(ths).attr('arrt');
  var button_property = $(ths).attr('property');
  //console.log(rowValue);
   if(rowValue !== undefined)
     var editVal = JSON.parse(rowValue);
      //alert(editVal.contact_id);
    if(button_property != 'view')
     { 
      $('#rateid').val(editVal.rates_id);
      $('#lead_rates').val(editVal.lead_rates).prop('selected',true);
      $('#lead_from').val(editVal.lead_from);
      $('#lead_to').val(editVal.lead_to);
      $('#lead_product').val(editVal.lead_product);
      $('#rate_type').val(editVal.rate_type).prop('selected',true);
      $('#bsc_frght').val(editVal.bsc_frght);
      $('#gr_chrg').val(editVal.gr_chrg);
      $('#lbr_chrg').val(editVal.lbr_chrg);
      $('#enrt_chrg').val(editVal.enrt_chrg);
      $('#dlvry_charge').val(editVal.dlvry_charge);
      $('#misc_charge').val(editVal.misc_charge);
      $('#risk_charge').val(editVal.risk_charge);
      $('#rate_rmrks').val(editVal.rate_rmrks);
     } 

      if(button_property == 'view'){
      $('.top_title').html('View');
       //$('#button').css('display','none');
       $("#LeadRatesForm :input").prop("disabled", true);
      }else{
      $('.top_title').html('Update');
       //$('#button').css('display','block');
       $("#LeadRatesForm :input").prop("disabled", false);
      }
};


//========================end=======================