<ol class="breadcrumb breadcrumb-2"> 
		<report id="esp_footer">
<div class="esp_seo_footer">
<div class="esp_ty_seofooter">
<div class="esp_seo_cat_bg">
<div class="esp_container_fixed">

<ul class="noListStyle esp_seo_catg_head">
<li><a href="#" id="seo_domestic_flights" onmouseover="func(this.id);" class="esp_links_tab active">Stock <img src="<?=base_url();?>assets/images/down.png" alt=""></a></li>
<li><a href="#" id="seo_hotels_india" class="esp_links_tab">Lead<img src="<?=base_url();?>assets/images/down.png" alt=""></a></li>
<li><a href="#" style="display:none" id="seo_international_flightss" class="esp_links_tab">Activity <img src="<?=base_url();?>assets/images/down.png" alt=""></a></li>
<li><a href="#" id="seo_international_flightss_1" class="esp_links_tab">Tour <img src="<?=base_url();?>assets/images/down.png" alt=""></a></li>				
</ul>

</div>
</div>
<div class="esp_seo_link_bg clearfix" style="">
<div class="esp_container_fixed">
<div class="topic-html-content">
<div class="topic-html-content-body">

<ul class="esp_links_seo clearfix" id="seo_domestic_flights_content" style="display: block;">
<li><a href="<?=base_url();?>report/Report/searchStock/">Product Stock Report</a></li>
</ul>

<ul class="esp_links_seo clearfix" id="seo_hotels_india_content" style="display: none;">
<li><a href="#"></a><a href="<?=base_url();?>report/Report/searchLead/">Lead Report</a></li>
</ul>

<ul class="esp_links_seo clearfix" id="seo_international_flightss_content" style="display: none;">
<li><a href="#"></a><a href="<?=base_url();?>report/Report/searchActivity/">Activity Report</a></li>
</ul>

<ul class="esp_links_seo clearfix" id="seo_international_flightss_1_content" style="display: none;">
<li><a href="#"></a><a href="<?=base_url();?>report/Report/searchTourReport/">Tour Report</a></li>
</ul>


</div>
</div>
</div>
</div>
</div>
</div>
  
<script type="text/javascript">
      jQuery(document).ready(function () {

          function ESP_SEO_Toggle_Slide(target) {

              if (!ESP_SEO_Toggle_Slide.oldLinks) {
                  ESP_SEO_Toggle_Slide.oldLinks = { tab: null, content: null };
              };

              $(".esp_links_tab").removeClass("active");
              var tab_id = $(target).prop("id");
              var content_id = tab_id + "_content";
              var content_id_h = $("#" + content_id)

              if (content_id_h.css("display") == "none") {
                  $(".esp_seo_link_bg").show();
                  $(target).addClass("active");
                  content_id_h.slideDown("fast");
                  if (content_id != ESP_SEO_Toggle_Slide.oldLinks.content) {
                      $("#" + ESP_SEO_Toggle_Slide.oldLinks.content).slideUp();
                  }
              } else {
                  content_id_h.slideUp("fast");
                  $(".esp_seo_link_bg").slideUp();
              }

              ESP_SEO_Toggle_Slide.oldLinks = { tab: tab_id, content: content_id };
          }

          $('.esp_seo_footer').find('.esp_seo_link_bg .esp_container_fixed > ul , .esp_seo_link_bg').css({
              'display': 'none'
          });

          $(".esp_links_tab").on("click", function () {
              ESP_SEO_Toggle_Slide(this);
          });

          /*function ESP_About_Toggle_Slide(target) {
              $(".esp_about_content1").slideToggle();
              $(target).toggleClass("active");
          }*/

          $('.esp_about_footer').find('.esp_about_content1').css({
              'display': 'none'
          });

          $(".esp_abt_txt").hover(function () {
              //ESP_About_Toggle_Slide(this);
              $(".esp_about_content1").slideDown();
          }, function () {
              $(".esp_about_content1").slideUp();
          });
          $('.footCountryDdn').hover(function () {
              $('.esp_off').addClass('esp_on');
          }, function () {
              $('.esp_off').removeClass('esp_on');
          });

          $(".esponhover").hover(function () {
              $(".dropdown-menu").show();
          }, function () {
              $(".dropdown-menu").hide();
          });
          $(".esphover2").hover(function () {
              $(".contact-us").slideDown(200);
          }, function () {
              $(".contact-us").hide();
          });
          $(".esphover3").hover(function () {
              $(".country-tooltip").slideDown(200);
          }, function () {
              $(".country-tooltip").hide();
          });

      });
</script>

<script>
      jQuery(document).ready(function () {

          $("#seo_domestic_flights").hover(function () {

              $(".esp_seo_link_bg").show();
              $("#seo_hotels_india").removeClass('active');
			  $("#seo_international_flightss").removeClass('active');
			  $("#seo_international_flightss_1").removeClass('active');
			  

			   $("#seo_international_flightss_content").slideUp();
			   $("#seo_international_flightss_1_content").slideUp();
              $("#seo_hotels_india_content").slideUp();

              $("#seo_domestic_flights").addClass('active');
              $("#seo_domestic_flights_content").slideDown();
			  
          });



 $("#seo_international_flightss").hover(function () {

              $(".esp_seo_link_bg").show();
              $("#seo_domestic_flights").removeClass('active');
			  $("#seo_international_flightss_1").removeClass('active');
              $("#seo_hotels_india").removeClass('active');

              $("#seo_domestic_flights_content").slideUp();
			  $("#seo_international_flightss_1_content").slideUp();
              $("#seo_hotels_india_content").slideUp();

              $("#seo_international_flightss").addClass('active');
              $("#seo_international_flightss_content").slideDown();

          });



          $("#seo_hotels_india").hover(function () {

              $(".esp_seo_link_bg").show();
              $("#seo_domestic_flights_content").slideUp();
			  $("#seo_international_flightss_content").slideUp();
			  $("#seo_international_flightss_1_content").slideUp();

              $("#seo_domestic_flights").removeClass('active');
			  $("#seo_international_flightss").removeClass('active');
			  $("#seo_international_flightss_1").removeClass('active');

              $("#seo_hotels_india").addClass('active');
              $("#seo_hotels_india_content").slideDown();
          });
		  
		  
		  $("#seo_international_flightss_1").hover(function () {

              $(".esp_seo_link_bg").show();
              $("#seo_domestic_flights").removeClass('active');
			  $("#seo_international_flightss").removeClass('active');
              $("#seo_hotels_india").removeClass('active');

              $("#seo_domestic_flights_content").slideUp();
			  $("#seo_international_flightss_content").slideUp();
              $("#seo_hotels_india_content").slideUp();

              $("#seo_international_flightss_1").addClass('active');
              $("#seo_international_flightss_1_content").slideDown();

          });
		  
      });
	  
	  
</script>
</report>
		
		</ol>