/*custom JS here*/
var numofsteps = 13;
var progresscounter = 0;
var ctr = 0;


jQuery(document).ready(function(){

  // reset progress bar
  jQuery( "#step-progressbar" ).progressbar({
    value: 0,
  });


  // DONE FOR YOU FUNNEL STARTS HERE
  var form3 = jQuery("#create-sales-option-campaign").show();
  form3.validate({
      errorPlacement: function errorPlacement(error, element) {
          if (element.attr("name") == "landing-page-name" )
              error.appendTo('#landing-page-name-error');
          else if  (element.attr("name") == "landing-page-type" )
              error.appendTo('#landing-page-type-error');
          else if  (element.attr("name") == "landing-page-list-id" )
              error.appendTo('#landing-page-list-id-error');
          else if  (element.attr("name") == "landing-page-url" )
              error.appendTo('#landing-page-url-error');
          else if  (element.attr("name") == "list_name" )
              error.appendTo('#list_name-error');
          else if  (element.attr("name") == "subscriber_email" )
              error.appendTo('#subscriber_email-error');
          else if  (element.attr("name") == "form_name" )
              error.appendTo('#form_name-error');
          else if  (element.attr("name") == "sub2_redirect" )
              error.appendTo('#sub2_redirect-error');
          // else if  (element.attr("name") == "sub3_redirect" )
          //     error.appendTo('#sub3_redirect-error');
          else if  (element.attr("name") == "list_company" )
              error.appendTo('#list_company-error');
          else if  (element.attr("name") == "list_address" )
              error.appendTo('#list_address-error');
          else if  (element.attr("name") == "list_city" )
              error.appendTo('#list_city-error');
          else if  (element.attr("name") == "list_state" )
              error.appendTo('#list_state-error');
          else if  (element.attr("name") == "list_postal" )
              error.appendTo('#list_postal-error');
          else if  (element.attr("name") == "list_country" )
              error.appendTo('#list_country-error');
          else if  (element.attr("name") == "landing-page-url-link" )
              error.appendTo('#landing-page-url-link-error');
          element.before(error);
      },
      rules: {
          confirm: {
              equalTo: "#password"
          },
          "landing-page-name": {
              required: true
          },
          "landing-page-type": {
              required: true
          },
          "landing-page-list-id": {
              required: true
          },
          "landing-page-url": {
              required: true
          },
          "list_name": {
              required: true
          },
          "subscriber_email": {
              required: true
          },
          "form_name": {
              required: true
          },
          "sub2_redirect": {
              required: true
          },
          // "sub3_redirect": {
          //  required: true
          // },
          "list_company": {
              required: true
          },
          "list_address": {
              required: true
          },
          "list_city": {
              required: true
          },
          "list_state": {
              required: true
          },
          "list_postal": {
              required: true
          },
          "list_country": {
              required: true
          },
          "landing-page-url-link": {
              required: true
          }
      }
  });
  form3.steps({
      headerTag: "h3",
      bodyTag: "section",
      transitionEffect: "slideLeft",
      transitionEffectSpeed: "500",
      // customize Labels on action buttons
      labels: {
          finish: "Finish",
          next: 'Continue <i class="fa fa-arrow-right" aria-hidden="true"></i>',
          previous: "Previous",
      },
      autoFocus: true,
      // initialize
      onInit: function(event, current){
          jQuery('.actions > ul > li:first-child').attr('style', 'display:none'); // hide previous button on 1st step.
          // jQuery('.actions > ul > li:nth-child(2)').attr('style', 'display:none'); // hide continue button on 1st step.
          console.log('oninit: ' + current);
          if(current == 0) {
            jQuery('.actions > ul > li:first-child a').attr('href', 'desk.php?action=new_funnel_campaign');
          }
          else{
            jQuery('.actions > ul > li:first-child a').attr('href', '#previous');
          }

          jQuery('.sales_option_campaign #step-progressbar').progressbar({value: 25});
          // $body.find('#create-funnel-campaign-p-4 .actions > ul > li:last-child').hide();
          // console.log('currently in step # ' . current);

          jQuery('.steps ul li.disabled').hide();

          // $body.find('.progress_indicator_txt span').html('8%');
          // 
          

      },
      onStepChanging: function (event, currentIndex, newIndex)
      {
          // if user goes back to 1st step, initialize action buttons
          if (currentIndex == 0) {
            jQuery('.actions > ul > li:first-child a').attr('href', 'desk.php?action=new_funnel_campaign');
            jQuery('.actions > ul > li:nth-child(2)').attr('style', 'display:none');
          }

          if (newIndex < currentIndex) {
              return true; // If user click on "Previous" button or clicked a previous step header, we just normally let him/her go
          }

          var step4 = jQuery('input[name=landing-page-url]').val();
          if(newIndex == 11 && typeof step4 !== 'undefined') {
              var ans = confirm("Are you sure you want to proceed? \nIf you click YES you won't be able to modify the details from the previous steps.");
              if(ans) {
                  //Update Form (Thank You URL)
                  aem_functions.update_form();

                  //Save campaign
                  aem_functions.save_funnel_campaign();
                  //Disable fields
                  $body.find('select').attr('disabled', true);
                  $body.find('input').attr('disabled', true);
                  $body.find('textarea').attr('disabled', true);
              } else {
                  return false;
              }
          }

          //Save List
          if(currentIndex == 2 && newIndex == 3) {
              var method = $body.find('#select_list_method').val();
              if(method == 'select_existing_list') {
                  aem_functions.save_list_to_session();
              } else if(method == 'create_new_list') {
                  if($body.find('#list_name').val() != "" && $body.find('#subscriber_email').val() !== ""){
                      aem_functions.add_new_list();
                  }
              }
          }

          //Save Form
          if(currentIndex == 4 && newIndex == 5) {
              aem_functions.add_new_form();
          }

          //Update Form (Redirect URL)
          if(currentIndex == 8 && newIndex == 9) {
              aem_functions.update_form();
          }

          form3.validate().settings.ignore = ":disabled,:hidden";
          return form3.valid();
      },
      onStepChanged: function (event, current, next) {

          console.log("onChanged - current: " + current + " - next: " + next);

          jQuery('.actions > ul > li:first-child a').attr('href', '#previous'); //

          ctr = (1 + current) * 25;
          console.log("changing: " + current);
          // Math.round(price / listprice * 100) / 100
          jQuery('#step-progressbar').progressbar({value: +ctr.toFixed(0) });

          // update the progressbar percentage text
          // $body.find('.progress_indicator_txt span').html( (+ctr).toFixed(2) + '%');
          // $body.find('.progress_indicator_txt span').html( (+ctr.toFixed(0) * current) + '%' );

          // display current step (from hidden status)
          jQuery('.steps ul li.current').show();

          // on first step, hide the previous button.
          if (current > 0) {
              jQuery('.actions > ul > li:first-child').attr('style', '');
          } else {
              jQuery('.actions > ul > li:first-child').attr('style', 'display:none');
          }

          // if(current == 3 && next == 4) {
          //     var method = $body.find('#select_list_method').val();
          //     if(method == 'select_existing_list') {
          //         jQuery('.actions > ul > li:nth-child(1) > a').click();
          //     }
          // }

          // if(current == 3 && next == 2 ) {
          //     var method = $body.find('#select_list_method').val();
          //     if(method == 'select_existing_list') {
          //         $body.find('#list_company').val('n/a');
          //         $body.find('#list_address').val('n/a');
          //         $body.find('#list_address2').val('n/a');
          //         $body.find('#list_city').val('n/a');
          //         $body.find('#list_state').val('n/a');
          //         $body.find('#list_postal').val('n/a');
          //         $body.find('#list_country').val('n/a');
          //         jQuery('.actions > ul > li:nth-child(2) > a').click();
          //     }
          // }

          if (current == 1) { 
              jQuery('.filter-button-group > div:first-child').click();
          }
          


          // console.log(current);
          if (current == 6) { // if current index is equals to 4th step
              jQuery('.actions > ul > li:nth-child(2)').attr('disabled', 'disabled');
              jQuery('.actions > ul > li:nth-child(2)').hide();

              // also load the template ID selected
              // loadTemplate();
              console.log("Loaded Optin Template: " + sessionStorage.getItem('optin'));
              var tmp = aem_functions.get_dfy_template_by_id(sessionStorage.getItem('optin'));
              // console.log(tmp);
          }

          if (current == 8) { // if current index is equals to 4th step
              jQuery('.actions > ul > li:nth-child(2)').attr('disabled', 'disabled');
              jQuery('.actions > ul > li:nth-child(2)').hide();

              // also load the template ID selected
              // loadTemplate();
              console.log("Loaded Download Template: " + sessionStorage.getItem('download'));
              aem_functions.get_dfy_template_by_id(sessionStorage.getItem('download'));
          }

          if (current == 10) { // if current index is equals to 4th step
              jQuery('.actions > ul > li:nth-child(2)').attr('disabled', 'disabled');
              jQuery('.actions > ul > li:nth-child(2)').hide();

              // also load the template ID selected
              // loadTemplate();
              console.log("Loaded Thank You Template: " + sessionStorage.getItem('thankyou'));
              aem_functions.get_dfy_template_by_id( sessionStorage.getItem('thankyou') );
          }


      },
      onFinishing: function (event, currentIndex)
      {
          aem_functions.destroy_list_session();
          form3.validate().settings.ignore = ":disabled";
          return form3.valid();
      },
      onFinished: function (event, currentIndex)
      {
          window.location.href = "/aem/manage/desk.php?action=funnel_campaign";
      }
  });




  // DONE FOR YOU FUNNEL STARTS HERE
  var form2 = jQuery("#create-premade-campaign").show();
  form2.validate({
      errorPlacement: function errorPlacement(error, element) {
          if (element.attr("name") == "landing-page-name" )
              error.appendTo('#landing-page-name-error');
          else if  (element.attr("name") == "landing-page-type" )
              error.appendTo('#landing-page-type-error');
          else if  (element.attr("name") == "landing-page-list-id" )
              error.appendTo('#landing-page-list-id-error');
          else if  (element.attr("name") == "landing-page-url" )
              error.appendTo('#landing-page-url-error');
          else if  (element.attr("name") == "list_name" )
              error.appendTo('#list_name-error');
          else if  (element.attr("name") == "subscriber_email" )
              error.appendTo('#subscriber_email-error');
          else if  (element.attr("name") == "form_name" )
              error.appendTo('#form_name-error');
          else if  (element.attr("name") == "sub2_redirect" )
              error.appendTo('#sub2_redirect-error');
          // else if  (element.attr("name") == "sub3_redirect" )
          //     error.appendTo('#sub3_redirect-error');
          else if  (element.attr("name") == "list_company" )
              error.appendTo('#list_company-error');
          else if  (element.attr("name") == "list_address" )
              error.appendTo('#list_address-error');
          else if  (element.attr("name") == "list_city" )
              error.appendTo('#list_city-error');
          else if  (element.attr("name") == "list_state" )
              error.appendTo('#list_state-error');
          else if  (element.attr("name") == "list_postal" )
              error.appendTo('#list_postal-error');
          else if  (element.attr("name") == "list_country" )
              error.appendTo('#list_country-error');
          else if  (element.attr("name") == "landing-page-url-link" )
              error.appendTo('#landing-page-url-link-error');
          element.before(error);
      },
      rules: {
          confirm: {
              equalTo: "#password"
          },
          "landing-page-name": {
              required: true
          },
          "landing-page-type": {
              required: true
          },
          "landing-page-list-id": {
              required: true
          },
          "landing-page-url": {
              required: true
          },
          "list_name": {
              required: true
          },
          "subscriber_email": {
              required: true
          },
          "form_name": {
              required: true
          },
          "sub2_redirect": {
              required: true
          },
          // "sub3_redirect": {
          //  required: true
          // },
          "list_company": {
              required: true
          },
          "list_address": {
              required: true
          },
          "list_city": {
              required: true
          },
          "list_state": {
              required: true
          },
          "list_postal": {
              required: true
          },
          "list_country": {
              required: true
          },
          "landing-page-url-link": {
              required: true
          }
      }
  });
  form2.steps({
      headerTag: "h3",
      bodyTag: "section",
      transitionEffect: "slideLeft",
      transitionEffectSpeed: "500",
      // customize Labels on action buttons
      labels: {
          finish: "Create New Campaign",
          next: 'Continue <i class="fa fa-arrow-right" aria-hidden="true"></i>',
          previous: "Previous",
      },
      autoFocus: true,
      // initialize
      onInit: function(event, current){
          // jQuery('.actions > ul > li:first-child').attr('style', 'display:none'); // hide previous button on 1st step.
          jQuery('.actions > ul > li:nth-child(2)').attr('style', 'display:none'); // hide continue button on 1st step.
          // console.log(current);
          if(current == 0) {
            jQuery('.actions > ul > li:first-child a').attr('href', 'desk.php?action=new_funnel_campaign');
          }
          else{
            jQuery('.actions > ul > li:first-child a').attr('href', '#previous');
          }
          jQuery('.done_for_you_campaign #step-progressbar').progressbar({value: 8.33333333334});
          // $body.find('#create-funnel-campaign-p-4 .actions > ul > li:last-child').hide();
          // console.log('currently in step # ' . current);

          jQuery('.steps ul li.disabled').hide();

          // $body.find('.progress_indicator_txt span').html('8%');

      },
      onStepChanging: function (event, currentIndex, newIndex)
      {
          // if user goes back to 1st step, initialize action buttons
          if (currentIndex == 0) {
            jQuery('.actions > ul > li:first-child a').attr('href', 'desk.php?action=new_funnel_campaign');
            jQuery('.actions > ul > li:nth-child(2)').attr('style', 'display:none');
          }

          if (newIndex < currentIndex) {
              return true; // If user click on "Previous" button or clicked a previous step header, we just normally let him/her go
          }

          var step4 = jQuery('input[name=landing-page-url]').val();
          if(newIndex == 11 && typeof step4 !== 'undefined') {
              var ans = confirm("Are you sure you want to proceed? \nIf you click YES you won't be able to modify the details from the previous steps.");
              if(ans) {
                  //Update Form (Thank You URL)
                  aem_functions.update_form();

                  //Save campaign
                  aem_functions.save_funnel_campaign();
                  //Disable fields
                  $body.find('select').attr('disabled', true);
                  $body.find('input').attr('disabled', true);
                  $body.find('textarea').attr('disabled', true);
              } else {
                  return false;
              }
          }

          //Save List
          if(currentIndex == 2 && newIndex == 3) {
              var method = $body.find('#select_list_method').val();
              if(method == 'select_existing_list') {
                  aem_functions.save_list_to_session();
              } else if(method == 'create_new_list') {
                  if($body.find('#list_name').val() != "" && $body.find('#subscriber_email').val() !== ""){
                      aem_functions.add_new_list();
                  }
              }
          }

          //Save Form
          if(currentIndex == 4 && newIndex == 5) {
              aem_functions.add_new_form();
          }

          //Update Form (Redirect URL)
          if(currentIndex == 8 && newIndex == 9) {
              aem_functions.update_form();
          }

          form2.validate().settings.ignore = ":disabled,:hidden";
          return form2.valid();
      },
      onStepChanged: function (event, current, next) {

          console.log("onChanged - current: " + current + " - next: " + next);

          jQuery('.actions > ul > li:first-child a').attr('href', '#previous'); //

          ctr = (1 + current) * 8.333333333334;
          console.log("changing: " + current);
          // Math.round(price / listprice * 100) / 100
          jQuery('#step-progressbar').progressbar({value: +ctr.toFixed(0) });

          // update the progressbar percentage text
          // $body.find('.progress_indicator_txt span').html( (+ctr).toFixed(2) + '%');
          // $body.find('.progress_indicator_txt span').html( (+ctr.toFixed(0) * current) + '%' );

          // display current step (from hidden status)
          jQuery('.steps ul li.current').show();

          // on first step, hide the previous button.
          if (current > 0) {
              jQuery('.actions > ul > li:first-child').attr('style', '');
          } else {
              jQuery('.actions > ul > li:first-child').attr('style', 'display:none');
          }

          if(current == 3 && next == 4) {
              var method = $body.find('#select_list_method').val();
              if(method == 'select_existing_list') {
                  jQuery('.actions > ul > li:nth-child(1) > a').click();
              }
          }

          if(current == 3 && next == 2 ) {
              var method = $body.find('#select_list_method').val();
              if(method == 'select_existing_list') {
                  $body.find('#list_company').val('n/a');
                  $body.find('#list_address').val('n/a');
                  $body.find('#list_address2').val('n/a');
                  $body.find('#list_city').val('n/a');
                  $body.find('#list_state').val('n/a');
                  $body.find('#list_postal').val('n/a');
                  $body.find('#list_country').val('n/a');
                  jQuery('.actions > ul > li:nth-child(2) > a').click();
              }
          }

          // console.log(current);
          if (current == 6) { // if current index is equals to 4th step
              jQuery('.actions > ul > li:nth-child(2)').attr('disabled', 'disabled');
              jQuery('.actions > ul > li:nth-child(2)').hide();

              // also load the template ID selected
              // loadTemplate();
              console.log("Loaded Optin Template: " + sessionStorage.getItem('optin'));
              var tmp = aem_functions.get_dfy_template_by_id(sessionStorage.getItem('optin'));
              // console.log(tmp);
          }

          if (current == 8) { // if current index is equals to 4th step
              jQuery('.actions > ul > li:nth-child(2)').attr('disabled', 'disabled');
              jQuery('.actions > ul > li:nth-child(2)').hide();

              // also load the template ID selected
              // loadTemplate();
              console.log("Loaded Download Template: " + sessionStorage.getItem('download'));
              aem_functions.get_dfy_template_by_id(sessionStorage.getItem('download'));
          }

          if (current == 10) { // if current index is equals to 4th step
              jQuery('.actions > ul > li:nth-child(2)').attr('disabled', 'disabled');
              jQuery('.actions > ul > li:nth-child(2)').hide();

              // also load the template ID selected
              // loadTemplate();
              console.log("Loaded Thank You Template: " + sessionStorage.getItem('thankyou'));
              aem_functions.get_dfy_template_by_id( sessionStorage.getItem('thankyou') );
          }


      },
      onFinishing: function (event, currentIndex)
      {
          aem_functions.destroy_list_session();
          form2.validate().settings.ignore = ":disabled";
          return form2.valid();
      },
      onFinished: function (event, currentIndex)
      {
          window.location.href = "/aem/manage/desk.php?action=funnel_campaign";
      }
  });



  jQuery('.btn-choose').click(function() {
      jQuery('.actions > ul > li:nth-child(2) > a').click();
  });


  // GET SELECTED DFY FUNNEL TEMPLATE ID //
  jQuery('a.btn-dfy-choose').click(function(e){
    e.preventDefault();
    thisData = jQuery(this).attr('data-info');
    // console.log(thisData);

    if (thisData) {
      // store in temp array then store to session storage
      // selectedTemplate = sessionStorage.getItem('selectedTemplate');
      var result = thisData.split(",");
      sessionStorage.setItem('optin', result[0]);
      sessionStorage.setItem('download', result[1]);
      sessionStorage.setItem('thankyou', result[2]);
    }
    else{
      console.log('empty template selected. please restart page.');
    }
  });

  //COLLECTION OF FUNCTIONS
  var aem_functions = {
      create_landing_page     :   function(landing_page_html, form) {
          jQuery.ajax({
              method: "post",
              url: "../manage/templates/classic/ajax/custom.php",
              data: {
                  'action':'create_landing_page',
                  'landing_page_html': landing_page_html,
                  'form': form
              },
              dataType: 'json',
              success:function(result) {
                  if(result.type == 'success'){
                      jQuery('input[name=landing-page-url]:checked').val(result.message);

                      //Save campaign
                      aem_functions.save_funnel_campaign();
                      //Disable fields
                      $body.find('select').attr('disabled', true);
                      $body.find('input').attr('disabled', true);
                      $body.find('textarea').attr('disabled', true);
                  } else {
                      alert(result.message);
                  }
              },
              error: function(errorThrown){
                  console.log(errorThrown);
              }
          });
      },
      get_lists   :   function() {
          jQuery.ajax({
              method: "post",
              url: "../manage/templates/classic/ajax/custom.php",
              data: {
                  'action':'get_lists'
              },
              dataType: 'json',
                success: function(result) {
                            var select_list = jQuery("body").find("#landing-page-list-id");
                            select_list.html('');
                            select_list.append( jQuery("<option></option>").attr({"value": "", "disabled": "disabled", "selected": "selected"}).text("-- SELECT LIST --") );

                            jQuery.each(result.data, function(key, value) {
                                select_list.append(jQuery("<option></option>").attr("value", value.id).text(value.name));
                            });
                          },

                error: function(errorThrown){
                        console.log(errorThrown);
                      },
          });
      },
      get_forms   :   function() {
          jQuery.ajax({
              method: "post",
              url: "../manage/templates/classic/ajax/custom.php",
              data: {
                  'action':'get_forms'
              },
              dataType: 'json',
              success:function(result) {
                  var select_form = jQuery("body").find("#landing-page-form-id");
                  select_form.html('');
                  jQuery.each(result.data, function(key, value) {
                      select_form.append(jQuery("<option></option>").attr("value", value.id).text(value.name));
                  });
              },
              error: function(errorThrown){
                  console.log(errorThrown);
              }
          });
      },
      save_funnel_campaign    :   function() {
          var fields = jQuery('#create-funnel-campaign').serialize();
          jQuery.ajax({
              method: "post",
              url: "../manage/functions/funnel_campaign.php",
              data: {
                  'action': 'list_insert_post',
                  'fields': fields
              },
              dataType: 'json',
              success:function(result) {
                  $body.find('#funnel_link').append('Your Campaign URL:<br /><a href="'+result.link+'" target="_blank">'+result.link+'</a>');
                  $body.find('#fb_share').attr('href', "https://www.facebook.com/sharer/sharer.php?u="+result.link);
                  $body.find('#twitter_share').attr('href', "https://twitter.com/home?status="+result.link);
                  $body.find('#email_share').attr('href', "mailto:?body="+result.link);
                  $body.find('#gplus_share').attr('href', "https://plus.google.com/share?url="+result.link);
                  $body.find('.done_campaign_name').append( '"' + jQuery('#landing-page-name').val() + '"' );
                  $body.find('.done_campaign_name_two').append( '"' + jQuery('#landing-page-name').val() + '"' );
              },
              error: function(errorThrown){
                  console.log(errorThrown);
              }
          });
      },
      save_list_to_session    :   function() {
          var list_id = jQuery('#landing-page-list-id').val();
          jQuery.ajax({
              method: "post",
              url: "../manage/functions/funnel_campaign.php",
              data: {
                  'action': 'save_list_to_session',
                  'list_id': list_id
              },
              dataType: 'json',
              success:function(result) {

              },
              error: function(errorThrown){
                  console.log(errorThrown);
              }
          });
      },
      destroy_list_session    :   function() {
          jQuery.ajax({
              method: "post",
              url: "../manage/functions/funnel_campaign.php",
              data: {
                  'action': 'destroy_list_session'
              },
              dataType: 'json',
              success:function(result) {

              },
              error: function(errorThrown){
                  console.log(errorThrown);
              }
          });
      },
      add_new_list    :   function(callback) {
          var fields = $body.find('.new_list_div :input').serialize();
          jQuery.ajax({
              method: "post",
              url: "../manage/templates/classic/ajax/api.php",
              data: {
                  'action':'add_list',
                  fields : fields
              },
              dataType: 'json',
              success:function(result) {
                  if(result.type == 'success') {
                      aem_functions.get_lists();
                      aem_functions.add_new_subscriber();
                  }
              },
              error: function(errorThrown){
                  console.log(errorThrown);
              }
          });
      },
      add_new_subscriber  :   function() {
          jQuery.ajax({
              method: "post",
              url: "../manage/templates/classic/ajax/api.php",
              data: {
                  'action':'add_subscriber',
                  'email' : $body.find('#subscriber_email').val()
              },
              dataType: 'json',
              success:function(result) {
                  if(result.type == 'error') {
                      alert(result.message);
                  }
              },
              error: function(errorThrown){
                  console.log(errorThrown);
              }
          });
      },
      add_new_form    :   function() {
          var fields = $body.find('.new_form_div :input').serialize();
          jQuery.ajax({
              method: "post",
              url: "../manage/templates/classic/ajax/api.php",
              data: {
                  'action':'add_form',
                  fields : fields
              },
              dataType: 'json',
              success:function(result) {
                  if(result.type == 'error') {
                      alert(result.message);
                  } else if(result.type == 'success') {
                      aem_functions.get_form();
                      aem_functions.add_form_id_to_url();
                  }
              },
              error: function(errorThrown){
                  console.log(errorThrown);
              }
          });
      },
      update_form    :   function() {
          var fields = $body.find('.new_form_div :input').serialize();
          jQuery.ajax({
              method: "post",
              url: "../manage/templates/classic/ajax/api.php",
              data: {
                  'action':'edit_form',
                  fields : fields
              },
              dataType: 'json',
              success:function(result) {
                  if(result.type == 'error') {
                      alert(result.message);
                  } else if(result.type == 'success') {
                      aem_functions.get_form();
                  }
              },
              error: function(errorThrown){
                  console.log(errorThrown);
              }
          });
      },
      get_form        :   function() {
          jQuery.ajax({
              method: "post",
              url: "../manage/templates/classic/ajax/api.php",
              data: {
                  'action':'get_form'
              },
              dataType: 'json',
              success:function(result) {
                  if(result.type == 'success') {
                      $body.find('#formcode').text(result.message.html);
                  }
              },
              error: function(errorThrown){
                  console.log(errorThrown);
              }
          });
      },
      add_form_id_to_url  :   function() {
          jQuery.ajax({
              method: "post",
              url: "../manage/templates/classic/ajax/api.php",
              data: {
                  'action':'get_form'
              },
              dataType: 'json',
              success:function(result) {
                  if(result.type == 'success') {
                      $body.find('.buildertemplate').each(function(index, item) {
                          var href = jQuery(item).attr('href');
                          var newhref = href+'/'+result.message.id;
                          jQuery(item).attr('href', newhref);
                      });
                      $body.find('.sitebuildertemplate').each(function(index, item) {
                          var href = jQuery(item).attr('href');
                          var newhref = href+'/'+result.message.id;
                          jQuery(item).attr('href', newhref);
                      });
                  }
              },
              error: function(errorThrown){
                  console.log(errorThrown);
              }
          });
      },
      focus_on_element    :   function(element_name) {
          var elementOffset = $body.find(element_name).offset().top;
          jQuery('html, body').animate({scrollTop: elementOffset}, 600);
      },
      reset_values        :   function(element_name) {
          jQuery(element_name).find('input:text').val('');
      },
      get_dfy_template_by_id  : function(id) {
        jQuery.ajax({
            method: "post",
            url: "../manage/templates/classic/ajax/custom.php",
            data: {
                'action'      : 'get_dfy_template_by_id',
                'groupset_id' : 1
            },
            dataType: 'json',
            success:function(result) {
                // var select_list = jQuery("body").find("#landing-page-list-id");
                // select_list.html('');
                // select_list.append(jQuery("<option></option>").attr({"value": "", "disabled": "disabled", "selected": "selected"}).text("-- SELECT LIST --"));
                // jQuery.each(result.data, function(key, value) {
                //     select_list.append(jQuery("<option></option>").attr("value", value.id).text(value.name));
                // });
                console.log(result.data);


                var customized_optin = jQuery("body").find("#customized_optin");
                customized_optin.html('');
                var strbuilder =  '<div class="template_wrapper">';
                strbuilder += '<span class="text_center template_title">Template Name</span>';
                strbuilder += '<img src="image_url" class="img-thumbnail" alt="Template Name" />';
                strbuilder += '<div class="template_buttons">';
                strbuilder += '<a href="builder-hyperlink" class="btn btn-primary btn-choose buildertemplate" target="_blank">CHOOSE</a>';
                strbuilder += '<a href="builder-preview.html" class="btn btn-demo" target="_blank">DEMO</a>';
                strbuilder += '</div></div>';

                customized_optin.append(strbuilder);


            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
      }
  };



  // CUSTOM CAMPAIGN STARTS HERE
    var $body = jQuery('body');

    var form = jQuery("#create-funnel-campaign").show();
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            if (element.attr("name") == "landing-page-name" )
                error.appendTo('#landing-page-name-error');
            else if  (element.attr("name") == "landing-page-type" )
                error.appendTo('#landing-page-type-error');
            else if  (element.attr("name") == "landing-page-list-id" )
                error.appendTo('#landing-page-list-id-error');
            else if  (element.attr("name") == "landing-page-url" )
                error.appendTo('#landing-page-url-error');
            else if  (element.attr("name") == "list_name" )
                error.appendTo('#list_name-error');
            else if  (element.attr("name") == "subscriber_email" )
                error.appendTo('#subscriber_email-error');
            else if  (element.attr("name") == "form_name" )
                error.appendTo('#form_name-error');
            else if  (element.attr("name") == "sub2_redirect" )
                error.appendTo('#sub2_redirect-error');
            // else if  (element.attr("name") == "sub3_redirect" )
            //     error.appendTo('#sub3_redirect-error');
            else if  (element.attr("name") == "list_company" )
                error.appendTo('#list_company-error');
            else if  (element.attr("name") == "list_address" )
                error.appendTo('#list_address-error');
            else if  (element.attr("name") == "list_city" )
                error.appendTo('#list_city-error');
            else if  (element.attr("name") == "list_state" )
                error.appendTo('#list_state-error');
            else if  (element.attr("name") == "list_postal" )
                error.appendTo('#list_postal-error');
            else if  (element.attr("name") == "list_country" )
                error.appendTo('#list_country-error');
            else if  (element.attr("name") == "landing-page-url-link" )
                error.appendTo('#landing-page-url-link-error');
            element.before(error);
        },
        rules: {
            confirm: {
                equalTo: "#password"
            },
            "landing-page-name": {
                required: true
            },
            "landing-page-type": {
                required: true
            },
            "landing-page-list-id": {
                required: true
            },
            "landing-page-url": {
                required: true
            },
            "list_name": {
                required: true
            },
            "subscriber_email": {
                required: true
            },
            "form_name": {
                required: true
            },
            "sub2_redirect": {
                required: true
            },
            // "sub3_redirect": {
            //  required: true
            // },
            "list_company": {
                required: true
            },
            "list_address": {
                required: true
            },
            "list_city": {
                required: true
            },
            "list_state": {
                required: true
            },
            "list_postal": {
                required: true
            },
            "list_country": {
                required: true
            },
            "landing-page-url-link": {
                required: true
            }
        }
    });
    form.steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        transitionEffectSpeed: "500",
        // customize Labels on action buttons
        labels: {
            finish: "Create New Campaign",
            next: 'Continue <i class="fa fa-arrow-right" aria-hidden="true"></i>',
            previous: "Previous",
        },
        autoFocus: true,
        // initialize
        onInit: function(event, current){
            jQuery('.actions > ul > li:first-child').attr('style', 'display:none'); // hide previous button on 1st step.
            // $body.find('#create-funnel-campaign-p-4 .actions > ul > li:last-child').hide();
            // console.log('currently in step # ' . current);

            jQuery('.steps ul li.disabled').hide();
            jQuery('.custom_campaign #step-progressbar').progressbar({value: 8.33333333334});
            // $body.find('.progress_indicator_txt span').html('8%');

        },
        onStepChanging: function (event, currentIndex, newIndex)
        {
            if (newIndex < currentIndex) {
                return true; // If user click on "Previous" button or clicked a previous step header, we just normally let him/her go
            }

            var step4 = jQuery('input[name=landing-page-url]').val();
            if(newIndex == 11 && typeof step4 !== 'undefined') {
                var ans = confirm("Are you sure you want to proceed? \nIf you click YES you won't be able to modify the details from the previous steps.");
                if(ans) {
                    //Update Form (Thank You URL)
                    aem_functions.update_form();

                    //Save campaign
                    aem_functions.save_funnel_campaign();
                    //Disable fields
                    $body.find('select').attr('disabled', true);
                    $body.find('input').attr('disabled', true);
                    $body.find('textarea').attr('disabled', true);
                } else {
                    return false;
                }
            }

            //Save List
            if(currentIndex == 2 && newIndex == 3) {
                var method = $body.find('#select_list_method').val();
                if(method == 'select_existing_list') {
                    aem_functions.save_list_to_session();
                } else if(method == 'create_new_list') {
                    if($body.find('#list_name').val() != "" && $body.find('#subscriber_email').val() !== ""){
                        aem_functions.add_new_list();
                    }
                }
            }

            //Save Form
            if(currentIndex == 4 && newIndex == 5) {
                aem_functions.add_new_form();
            }

            //Update Form (Redirect URL)
            if(currentIndex == 8 && newIndex == 9) {
                aem_functions.update_form();
            }

            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onStepChanged: function (event, current, next) {
            ctr = (1 + current) * 8.333333333334;

            // Math.round(price / listprice * 100) / 100
            jQuery('#step-progressbar').progressbar({value: +ctr.toFixed(0) });

            // update the progressbar percentage text
            // $body.find('.progress_indicator_txt span').html( (+ctr).toFixed(2) + '%');
            // $body.find('.progress_indicator_txt span').html( (+ctr.toFixed(0) * current) + '%' );

            // display current step (from hidden status)
            jQuery('.steps ul li.current').show();

            // on first step, hide the previous button.
            if (current > 0) {
                jQuery('.actions > ul > li:first-child').attr('style', '');
            } else {
                jQuery('.actions > ul > li:first-child').attr('style', 'display:none');
            }

            if(current == 2 && next == 3) {
                var method = $body.find('#select_list_method').val();
                if(method == 'select_existing_list') {
                    jQuery('.actions > ul > li:nth-child(1) > a').click();
                }
            }

            console.log("onChanged - current: " + current + " - next: " + next);

            if(current == 2 && next == 1) {

                var method = $body.find('#select_list_method').val();
                if(method == 'select_existing_list') {
                    $body.find('#list_company').val('n/a');
                    $body.find('#list_address').val('n/a');
                    $body.find('#list_address2').val('n/a');
                    $body.find('#list_city').val('n/a');
                    $body.find('#list_state').val('n/a');
                    $body.find('#list_postal').val('n/a');
                    $body.find('#list_country').val('n/a');
                    jQuery('.actions > ul > li:nth-child(2) > a').click();
                }
            }

            // console.log(current);
            if (current == 5) { // if current index is equals to 4th step
                jQuery('.actions > ul > li:nth-child(2)').attr('disabled', 'disabled');
                jQuery('.actions > ul > li:nth-child(2)').hide();
                jQuery('.filter-button-group > div:first-child').click();
            }

            if (current == 7) { // if current index is equals to 4th step
                jQuery('.actions > ul > li:nth-child(2)').attr('disabled', 'disabled');
                jQuery('.actions > ul > li:nth-child(2)').hide();

                 jQuery('.filter-button-group > div:first-child').click();

            }

            if (current == 9) { // if current index is equals to 4th step
                jQuery('.actions > ul > li:nth-child(2)').attr('disabled', 'disabled');
                jQuery('.actions > ul > li:nth-child(2)').hide();
                jQuery('.filter-button-group > div:first-child').click();
            }


            if (current == 11) {
              jQuery('.actions > ul > li:first-child').attr('style', 'display:none'); // hide previous button on 1st step.
              jQuery('.progressbar_wrapper').hide();
            }
            

        },
        onFinishing: function (event, currentIndex)
        {
            aem_functions.destroy_list_session();
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function (event, currentIndex)
        {
            window.location.href = "/aem/manage/desk.php?action=new_funnel_campaign";
        }
    });

    // MIMIC CLICKING OF CONTINUE BUTTON
    jQuery('.btn-choose').click(function() {
        jQuery('.actions > ul > li:nth-child(2) > a').click();
    });





    //GET LISTS
    aem_functions.get_lists();

    //REFRESH LIST
    $body.on('click', '#refresh_list_list', function(e){
        e.preventDefault();
        aem_functions.get_lists();
    });

    $body.on('change', '#landing-page-list-id', function(e){
        e.preventDefault();
        $body.find('#select_list_method').val('select_existing_list');
    });

    $body.on('click', '.newlist_btn', function(e){
        e.preventDefault();
        $body.find('#new_list_div').show();
        $body.find('#new_subscriber_div').show();
        $body.find('#select_existing_list_div').hide();
        $body.find('#select_list_method').val('create_new_list');
        aem_functions.focus_on_element('#new_list_div');
        jQuery('.select_existing_list').removeClass('list_selection_active');
        jQuery('.newlist_btn').addClass('list_selection_active');
    });

    $body.on('click', '.select_existing_list', function(e){
        e.preventDefault();
        $body.find('#select_existing_list_div').show();
        $body.find('#landing-page-list-id').show();
        $body.find('#new_list_div').hide();
        $body.find('#new_subscriber_div').hide();
        aem_functions.reset_values('#new_list_div');
        aem_functions.reset_values('#new_subscriber_div');
        $body.find('#select_list_method').val('select_existing_list');
        aem_functions.focus_on_element('#select_existing_list_div');

        // css update
        // set to active
        jQuery('.select_existing_list').addClass('list_selection_active');
        jQuery('.newlist_btn').removeClass('list_selection_active');
    });

    $body.on('click', '.btn_landing_page_select_template', function(e){
        e.preventDefault();
        $body.find('.select_landing_page_template').show();
        $body.find('.select_landing_page_builder').hide();
        aem_functions.focus_on_element('#select_landing_page_template');

        // $body.find('#select_list_method').val('create_new_list');
        // aem_functions.focus_on_element('#new_list_div');
        jQuery('.btn_landing_page_builder').removeClass('list_selection_active');
        jQuery('.btn_landing_page_select_template').addClass('list_selection_active');
    });



    /* Redirect Page Step Wizard */
    $body.on('click', '.btn_pre_made_template', function(e){
        e.preventDefault();
        $body.find('#pre_made_templates_container').show();
        $body.find('#custom_page_design_container').hide();
        $body.find('#external_url_container').hide();
        aem_functions.focus_on_element('#pre_made_templates_container');

        jQuery('.btn_pre_made_template').addClass('list_selection_active');
        jQuery('.btn_custom_page_design').removeClass('list_selection_active');
        jQuery('.btn_redirect_page_custom_url').removeClass('list_selection_active');

        jQuery('.actions > ul > li:nth-child(2)').hide(); // make sure that the 'Continue' button is hidden

        jQuery('.filter-button-group > div:first-child').click();

    });

    $body.on('click', '.btn_custom_page_design', function(e){
        e.preventDefault();
        $body.find('#pre_made_templates_container').hide();
        $body.find('#custom_page_design_container').show();
        $body.find('#external_url_container').hide();
        aem_functions.focus_on_element('#custom_page_design_container');

        jQuery('.btn_pre_made_template').removeClass('list_selection_active');
        jQuery('.btn_custom_page_design').addClass('list_selection_active');
        jQuery('.btn_redirect_page_custom_url').removeClass('list_selection_active');

        jQuery('.actions > ul > li:nth-child(2)').hide(); // make sure that the 'Continue' button is hidden

        jQuery('.filter-button-group > div:first-child').click();
    });

    $body.on('click', '.btn_redirect_page_custom_url', function(e){
        e.preventDefault();
        jQuery('.actions > ul > li:nth-child(2) > a').click();
        /*$body.find('#pre_made_templates_container').hide();
        $body.find('#custom_page_design_container').hide();
        $body.find('#external_url_container').show();
        jQuery('.btn_pre_made_template').removeClass('list_selection_active');
        jQuery('.btn_custom_page_design').removeClass('list_selection_active');
        jQuery('.btn_redirect_page_custom_url').addClass('list_selection_active');*/

    });

    /*$body.find('#redirect_externalurl_txtbox').on('input propertychange paste', function() {
        jQuery('.actions > ul > li:nth-child(2)').show();
    });*/
    /* Redirect Page Step Ends */


    /* Thank You Page Step Wizard */
    $body.on('click', '.btn_thankyou_premadetemplate', function(e){
        e.preventDefault();
        $body.find('#thankyou_premadetemplate_container').show();
        $body.find('#thankyou_custompagedesign_container').hide();
        $body.find('#thankyou_externalurl_container').hide();
        aem_functions.focus_on_element('#thankyou_premadetemplate_container');

        jQuery('.btn_thankyou_premadetemplate').addClass('list_selection_active');
        jQuery('.btn_thankyou_custompagedesign').removeClass('list_selection_active');
        jQuery('.btn_thankyou_externalurl').removeClass('list_selection_active');

        jQuery('.actions > ul > li:nth-child(2)').hide(); // make sure that the 'Continue' button is hidden

        jQuery('.filter-button-group > div:first-child').click();

    });

    $body.on('click', '.btn_thankyou_custompagedesign', function(e){
        e.preventDefault();
        $body.find('#thankyou_premadetemplate_container').hide();
        $body.find('#thankyou_custompagedesign_container').show();
        $body.find('#thankyou_externalurl_container').hide();
        aem_functions.focus_on_element('#thankyou_custompagedesign_container');

        jQuery('.btn_thankyou_premadetemplate').removeClass('list_selection_active');
        jQuery('.btn_thankyou_custompagedesign').addClass('list_selection_active');
        jQuery('.btn_thankyou_externalurl').removeClass('list_selection_active');

        jQuery('.actions > ul > li:nth-child(2)').hide(); // make sure that the 'Continue' button is hidden
    });

    $body.on('click', '.btn_thankyou_externalurl', function(e){
        e.preventDefault();
        jQuery('.actions > ul > li:nth-child(2) > a').click();
        /*$body.find('#thankyou_premadetemplate_container').hide();
        $body.find('#thankyou_custompagedesign_container').hide();
        $body.find('#thankyou_externalurl_container').show();
        jQuery('.btn_thankyou_premadetemplate').removeClass('list_selection_active');
        jQuery('.btn_thankyou_custompagedesign').removeClass('list_selection_active');
        jQuery('.btn_thankyou_externalurl').addClass('list_selection_active');*/

    });

    /*$body.find('#thankyou_externalurl_txtbox').on('input propertychange paste', function() {
        jQuery('.actions > ul > li:nth-child(2)').show();
    });*/
    /* Thank You Page Step Ends */

    $body.on('click','.skip_to_publish', function(e) {
        e.preventDefault();
        jQuery('.actions > ul > li:nth-child(2) > a').click();
        var sub2_redirect_url = $body.find('#sub2_redirect').val();
        $body.find('#sub3_redirect').val(sub2_redirect_url);
        jQuery('.actions > ul > li:nth-child(2) > a').click();
    });

    $body.on('click', '#add_new_list_go', function(e){
        e.preventDefault();
        aem_functions.add_new_list();
    });

    $body.on('click', '#add_new_list_cancel', function(e){
        e.preventDefault();
        $body.find('#new_list_div').hide();
    })

    $body.on('click', '#add_new_subscriber', function(e){
        e.preventDefault();
        aem_functions.add_new_subscriber();
    });

    $body.on('click', '#add_new_form', function(e){
        e.preventDefault();
        aem_functions.add_new_form();
    });

    $body.on('click', 'input[name=landing-page-url]', function(e){
        if(jQuery(this).data('customlandingpage') == 'yes') {
            $body.find('#custom_landing_page_div').show();
        } else {
            $body.find('#custom_landing_page_div').hide();
        }
    });

    $body.on('input', '#sub2_redirect_input', function(e){
        e.preventDefault();
        jQuery("input[name=sub2_redirect]").removeAttr("checked");
        jQuery(this).closest('label').find('input[name=sub2_redirect]').attr('checked', true);
        jQuery(this).closest('label').find('input[name=sub2_redirect]').val(jQuery(this).val());
    });

    $body.on('input', '#sub3_redirect_input', function(e){
        e.preventDefault();
        jQuery("input[name=sub3_redirect]").removeAttr("checked");
        jQuery(this).closest('label').find('input[name=sub3_redirect]').attr('checked', true);
        jQuery(this).closest('label').find('input[name=sub3_redirect]').val(jQuery(this).val());
    });

    $body.on('input', '#landing-page-url-link', function(e){
        e.preventDefault();
        jQuery('input[name=landing-page-url]:checked').val(jQuery(this).val());
    });

    $body.on('click', 'input[name=download-page-url]', function(){
        jQuery('.template_buttons').hide();
        var template = jQuery(this).attr('id');
        jQuery('label[for="'+template+'"]').find('.template_buttons').show();
    });

    $body.on('click', '.accesssitebuilder', function(){
        jQuery('.actions > ul > li:nth-child(2) > a').click();
    });


    // TOOLTIPS
    jQuery('#tooltip_name_campaign').tooltip();
    jQuery('#tooltip_step2').tooltip();
    
    // TABS
    jQuery('#getstarted_tabs').tabs();
    
    // DATA TABLES
    jQuery('.dataTables').DataTable({
      "iDisplayLength": 100,
       responsive: true,
    });

    jQuery('body').on('click', '.clickableRow', function(e) {
        e.preventDefault();
        var tr = jQuery(this);
        var editModal = jQuery('#editRowForm');
        editModal.find('#name').val(tr.find('.name').text());
        editModal.find('#slug').val(tr.find('.slug').text());
        editModal.find('#type').val(tr.find('.type').text());
        editModal.find('#category').val(tr.find('.category').text());
        editModal.find('#image_url').val(tr.find('.image_url').text());
        editModal.find('#builder_id').val(tr.find('.builder_id').text());
        editModal.find('#builder_url').val(tr.find('.builder_url').text());
        editModal.find('#groupset_id').val(tr.find('.groupset_id').text());

        jQuery('#deleteRowModal').find('#deleteRowID').text(tr.find('.name').text());
        jQuery('#selectOption').modal();
    });

    // ISOTOPE - FILTERING
    // initialize isotope 
    var $grid = jQuery('.grid') .isotope({
        // options
        itemSelector: '.grid_item',
        isFitWidth: true,
        filter: '*'
    });

    

    $grid.isotope({ filter: '*' });

    jQuery('.filter-button-group').on( 'click', 'div', function() {
      var filterValue = jQuery(this).attr('data-filter');
      $grid.isotope({ filter: filterValue });
    });
});
