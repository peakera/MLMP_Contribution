<?php 

//Separate out the contribution form fields, date form fields and the contributor information form fields

$elementTable = get_db()->getTable('Element');

foreach($type->getTypeElements() as $typeElement){
	$elementId = $typeElement->element_id;
	$setElements = $elementTable->findBySet('Contributor Information');	
}

$contributorElementIds = array();
foreach($setElements as $setElement){
	$contributorElementIds[] = $setElement->id;	
}

//Pull the Dublin Core "Date" ID to work with the calendar
$dateID = $elementTable->findByElementSetNameAndElementName('Dublin Core', 'Date')->id;
		
$itemTypeForm = '';
$profileForm = '';
$dateForm = '';
foreach ($type->getTypeElements() as $contributionTypeElement) 
	{
    if(in_array( $contributionTypeElement->element_id, $contributorElementIds)) 
	{
        $profileForm .= $this->elementForm($contributionTypeElement->Element, $item, array('contributionTypeElement'=>$contributionTypeElement)); 
    } 
	else if($contributionTypeElement->element_id == $dateID)
	{
		$dateForm .= $this->elementForm($contributionTypeElement->Element, $item, array('contributionTypeElement'=>$contributionTypeElement)); 
	}
	else 
	{
        $itemTypeForm .= $this->elementForm($contributionTypeElement->Element, $item, array('contributionTypeElement'=>$contributionTypeElement));        
    }
}

?>


<form method="post" id="contribution-form" action="" enctype="multipart/form-data">
<div id="form-container">

<?php $value = $_POST['contribution_type'];?>
<input type="hidden" name='contribution_type' value='<?php echo $value ?>' />

      
	<ul class="form-headings">
        <li><a href="#1" data-liquidslider-ref="slider-id">Your Contribution</a></li>
        <li class="arrow"><img src="plugins/Contribution/views/public/css/images/arrow1.png"/></li>
        <li><a href="#2" data-liquidslider-ref="slider-id">Where</a></li>
        <li class="arrow"> <img src="plugins/Contribution/views/public/css/images/arrow1.png"/></li>
        <li><a href="#3" data-liquidslider-ref="slider-id">When</a></li>
        <li class="arrow"><img src="plugins/Contribution/views/public/css/images/arrow1.png"/></li>
        <li><a href="#4" data-liquidslider-ref="slider-id">About You</a></li>
        <li class="arrow"><img src="plugins/Contribution/views/public/css/images/arrow1.png"/></li>
        <li><a href="#5" data-liquidslider-ref="slider-id">Share</a></li>
	</ul>


<div class="liquid-slider"  id="slider-id">
      

      <div><!--------------------- Contribution Information Slide ------------->
          <div class="form">
          
			<?php if (!$type): ?>
            	<p>You must choose a contribution type to continue.</p>   
            <?php else: ?>
            	<h2>Contribute Your <?php echo $type->display_name;?></h2>
            <?php endif; ?>
            
            <?php if ($type->isFileRequired()): $required = true;?>
            	<div class="field" style="height: 100px;">
                	
            		<?php echo $this->formLabel('contributed_file', 'Upload a file'); ?>
                    <div id="typeWarning"></div>
            		<?php echo $this->formFile('contributed_file', array('class' => 'fileinput')); ?>
            	</div>
                
             <?php endif; ?>
            
            
            <?php echo $itemTypeForm ?>
            
			<?php if (!isset($required) && $type->isFileAllowed()):?>
            	<div id="file" class="field" style="height: 100px;">
                    <div id="typeWarning"></div>
            		<?php echo $this->formLabel('contributed_file', 'Upload a file (Optional)'); ?>
            		<?php echo $this->formFile('contributed_file', array('class' => 'fileinput')); ?>
				</div>
            <?php endif; ?>

         </div>
      </div>

      
      
        

        <div><!----------------------- Geolocation Information Slide ------------------> 
          <div class="form">
                        
          	<h2>Where did this happen?</h2>
               <?php echo get_specific_plugin_hook_output('Geolocation', 'contribution_type_form', array('view' => $this, 'item' => $item, 'type'=>$type) ); ?>
                  
           </div>
        </div> 


        
        
     
        <div><!------------------------- Calendar Information Slide ------------------> 
          <div class="form">
          
             <h2>When did this happen?</h2>
             	<?php echo get_specific_plugin_hook_output('Calendar', 'contribution_type_form', array('view' => $this, 'item' => $item, 'type'=>$type) ); ?>
                <div style="display:none;">
                    <?php echo $dateForm ?>
                </div>
               <div id="dateFormField">
                 <div class="field" id="element[40]">
                   <label>When did this happen? (YYYYMMDD)</label>
                   <div class="inputs">
                     <div class="input-block">
                       <div class="input">
                         <input type="text" name="Elements[40][0][text]" id="Elements-40-0-text" value class="textinput">
                       </div>
                     </div>
                   </div>
                 </div>
               </div>

           </div>
        </div>
        
        

        <div><!------------------------ Contributor Information Slide ------------------> 
          <div class="form" id="contributorInformationForm">
          
          		<h2>About You</h2>
                <p class = "disclaimer">
                        These questions are optional, but researchers can learn quite a bit by pairing contributions with demographic information. For example, a researcher might want to look at how stories contributed by male writers under 25 differ from stories written by female contributors over 40. We hope you take a few moments of your time to provide this information and help us make this online collection as complete as possible. 
                        </p>
                        <h3>If you enter this information, it will be displayed on the site.</h3>
            	<div class="inputs">
                 	<?php $anonymous = isset($_POST['contribution-anonymous']) ? $_POST['contribution-anonymous'] : 0; ?>
                 	<?php echo $this->formCheckbox('contribution-anonymous', $anonymous, null, array(1, 0)); ?>
                 	<?php echo $this->formLabel('contribution-anonymous', "Contribute anonymously."); ?>
            	</div>

                    <?php echo $profileForm ?>
                
           </div>
         </div>
        


        <div><!------------------------------------- Share/Terms Slide ------------------>         
          <div class="form">
                <h2>Share</h2>
                <fieldset id="contribution-confirm-submit" <?php if (!isset($type)) { echo 'style="display: none;"'; }?>>
                
                    <?php $user = current_user(); ?>
                    <?php if(get_option('contribution_simple') && !current_user()) : ?>
                        <div class="field">
                            <?php echo $this->formLabel('contribution_simple_email', 'Email (Required)'); ?>
                            <?php echo $this->formText('contribution_simple_email'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="inputs">
                         <?php $contact = isset($_POST['contact-ok']) ? $_POST['contact-ok'] : 1; ?>
                         <?php echo $this->formCheckbox('contact-ok', $contact, null, array('1', '0')); ?>
                         <?php echo $this->formLabel('contact-ok', 'It is ok to contact me for further information.'); ?>
                     </div>
                    
                    <div class="inputs">
                         <?php $public = isset($_POST['contribution-public']) ? $_POST['contribution-public'] : 1; ?>
                         <?php echo $this->formCheckbox('contribution-public', $public, null, array('1', '0')); ?>
                         <?php echo $this->formLabel('contribution-public', 'Share my contribution publicly. Uncheck to share only with approved researchers.'); ?>
                     </div>
                       
                     <div class="inputs" id="terms">
                          <?php $agree = isset( $_POST['terms-agree']) ?  $_POST['terms-agree'] : 0 ?>
                          <?php echo $this->formCheckbox('terms-agree', $agree, null, array('1', '0')); ?>
                          <?php echo $this->formLabel('terms-agree', 'I agree to the '); ?>
                          <?php echo '<a href="#" id="termsLink">Terms and Conditions.</a>'; ?>
                          
                            <div id="termsDiv">
                                <a href="#" id="hideTerms">&#91;Hide&#93;</a>
                                <?php echo get_option('contribution_consent_text'); ?>
                            </div>
                            <div id="warning"></div>
                            
                      </div>
                    </fieldset>
                    
                    <?php echo $this->formSubmit('form-submit', 'Contribute', array('class' => 'submitinput')); ?>                           
            </div>
         </div>
         
</div> <!-- End liquid slider -->
</div> <!-- End form-container -->
</form>   
  
<script>
$(document).ready(function(){
	
//Creates the slider -- Find more options at http://liquidslider.kevinbatdorf.com
    $(function(){
          $('#slider-id').liquidSlider({
            continuous:false,
            hideSideArrows: true,
			hoverArrows: false,
			dynamicTabs: false,
			crossLinks: true,
			preloader: true
			
          });
    
    /* If you need to access the internal property or methods, use this:
            var sliderObject = $.data( $('#slider-id')[0], 'liquidSlider');
            console.log(sliderObject); */
     });


//Pre-populates Contributor Information form fields from previous selection
		<?php foreach($_POST as $key=>$post)
		{?>
			$("#Elements-<?php echo $key; ?>-0-text").val("<?php echo $post; ?>");
		<?php
		}
		?>
});


//Required to prevent tab button from breaking slider when tabbing through form fields
$('.form .field:last-child input').on('keydown', function(e) { 
  var keyCode = e.keyCode || e.which; 

  if (keyCode == 9) { 
    e.preventDefault(); 
	
  } 
});


//Disables name field when anonymous is checked
<?php $contributorNameID = $elementTable->findByElementSetNameAndElementName('Contributor Information', 'Contributor Name')->id;  ?>    
$("#contribution-anonymous").click(function(event){
	if ($('#contribution-anonymous').is(':checked')){
		$("#Elements-<?php echo $contributorNameID ?>-0-text").val("Anonymous");
		$("#Elements-<?php echo $contributorNameID ?>-0-text").attr("disabled", "disabled");
	}
	else{
		$("#Elements-<?php echo $contributorNameID ?>-0-text").val("");
		$("#Elements-<?php echo $contributorNameID ?>-0-text").removeAttr("disabled");
	}
});

//Toggles the Terms and conditions block when clicked
$("#termsLink").click(function(event){
		$('#slider-id').css("height", "100%");
        $('#termsDiv').slideToggle();
        event.preventDefault();
});

$("#hideTerms").click(function(event){
        $('#termsDiv').slideUp();
        event.preventDefault();
});

             
             
//////////////////////////   Validation functions    /////////////////////////////////

//Requires users to select a valid MIME type
var typeArray = new Array();
<?php $typeList = option('file_extension_whitelist');
	  $typeArray = explode(',', $typeList);	
	  
	  $count = count($typeArray);
	  
	  for($i = 0; $i < $count; $i++)
	  {
		  echo 'typeArray[' . $i . '] = "' . $typeArray[$i]. '";';
	  }
?>


$('#contributed_file').change(function(event) {
	var fileName = $('#contributed_file').val();
	var extension = fileName.split('.').pop().toLowerCase();
	if (jQuery.inArray(extension, typeArray) < 0){
		event.preventDefault();
		$('#file-warning').remove();
		$('#typeWarning').append("<p id='file-warning' class='warning' >*** Please upload a file with an <a target='_blank' href='<?php echo contribution_contribute_url('filetype'); ?>'> accepted file type</a> ***</p>");
	}
	else{
	$('#file-warning').remove();
	}
	
});


function testValidation()
{
	var flag = true;
	
//Requires user to check terms and conditions box	
	if ($('#terms-agree').is(':checked')){
		$('#term-warning').remove();	
	}
	else{	
		$('#term-warning').remove();
		$('#warning').append("<p id='term-warning' class='warning' >*** You must agree to the terms and conditions to submit your contribution ***</p>");
		flag = false;
	};
	

//Requires users to input a valid email address if it is asked	
	if($('#contribution_simple_email').length !=0){
		
		var userEmail = $('#contribution_simple_email').val();
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		var isValid = emailReg.test(userEmail);
		
		if(!isValid || $('#contribution_simple_email').val().length == 0 ){
			$('#email-warning').remove();
			$('#warning').append("<p id='email-warning' class='warning' >*** Please enter a valid email address to submit your contribution ***</p>");
			flag = false;
		}
		else{
			$('#email-warning').remove();	
		}	
	
	}

<?php if ($type->isFileRequired()):	?>
//Requires users to upload a file if required
	if($('#contributed_file').val().length == 0){
		$('#file-warning').remove();
		$('#warning').append("<p id='file-warning' class='warning' >*** Please upload your <?php echo $type->display_name;?> ***</p>");
		flag = false;
	}
	else{
		$('#file-warning').remove();	
	}	

<?php endif; ?>


	return flag;
}


//validation functions when clicking "submit"
$("#form-submit").click(function(event){
	valid = testValidation();
	if(testValidation())
	{
		return true;
	}
	else
	{
	$('#slider-id').css("height", "100%");
	event.preventDefault();
	return false;
	}
	
});







</script>
   







