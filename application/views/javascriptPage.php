<script>
var myWindow;
function openpopup(url,width,height,ev,id) {
/*      //   newWindow = window.open("add-address.php", null, "height=400,width=1000,status=yes,toolbar=no,menubar=no,location=");  
	var width = width;

    var height = height;

    var left = parseInt((screen.availWidth/2) - (width/2));

    var top = parseInt((screen.availHeight/2) - (height/2));

    var windowFeatures = "width=" + width + ",height=" + height + ",status,resizable,toolbar,left=" + left + ",top=" + top + "screenX=" + left + ",screenY=" + top;

    myWindow = window.open(url+"?&popup=True&"+ev+"="+id, "subWind"+url, windowFeatures);
	
	*/
	//alert(ev);
	 window.open(url+"?&popup=True&"+ev+"="+id, "", "width=1398, height=800");
 }
</script>
<script>
//####### starts this script is use for popup close #######//
function popupclose(){
window.close();
//####### starts this script is use for popup close #######//
}
</script>
<script>
//####### starts this script is use for select all checkbox #######//
function checkall(objForm)
{
//alert(objForm);
//getCid(this.value);
	var ele,len,i;

	ele= document.getElementsByTagName("input");

	len=ele.length;

	for(i=0;i<len;i++){

	if(ele[i].type=='checkbox'){

		ele[i].checked=objForm;

		}

	}

}
//####### ends this script is use for select all checkbox #######//
</script>

