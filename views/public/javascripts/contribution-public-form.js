
function toggleProfileEdit() {
    jQuery('div.contribution-userprofile').toggle();
    jQuery('span.contribution-userprofile-visibility').toggle();
}


function enableContributionTypeButtons(url) {
    var typeButtons = jQuery('a.type-option');
    var typeFormDiv = jQuery('div#type-form');

    
    typeButtons.click(function(e) {
        e.preventDefault();
        var el = jQuery(this);
		
		$("a").removeClass("selected");
		el.addClass("selected");
		
		typeButtons.fadeTo("fast", 0.3);
		el.fadeTo("fast", 1.0);
		
		
		$("#test").empty();
		
		var inputTexts = "{"
		$("#contributorInformationForm :text").each(function(index) {
			if((this).value)
			{	
				var pattern = new RegExp('(\\d+)');
				var array = pattern.exec($(this).attr('name'));
				var name = array[0];
			
				inputTexts += '"' + name + '": "' + $(this).val() + '", ';
			}
		});
		
		var typeId = el.attr('value'); 
		inputTexts += '"contribution_type":' + typeId + "}";
		
	 	typeFormDiv.empty();
		
		var json = JSON.parse(inputTexts);
		
        jQuery.post(url, json , function(data) {
            typeFormDiv.append(data);});
    });
}

function enableContributionAjaxForm(url) {
    jQuery(document).ready(function() {
        // Div that will contain the AJAX'ed form.
        var form = jQuery('#contribution-type-form');
        // Select element that controls the AJAX form.
        var contributionType = jQuery('#contribution-type');
        // Elements that should be hidden when there is no type form on the page.
        var elementsToHide = jQuery('#contribution-confirm-submit, #contribution-contributor-metadata');
        // Duration of hide/show animation.
        var duration = 0;

        // Remove the noscript-fallback type submit button.
        jQuery('#submit-type').remove();

        // When the select is changed, AJAX in the type form
        contributionType.change(function () {
            var value = this.value;
            elementsToHide.hide();
            form.hide(duration, function() {
                form.empty();
                if (value != "") {
                    jQuery.post(url, {contribution_type: value}, function(data) {
                       form.append(data); 
                       form.show(duration, function() {
                           form.trigger('contribution-form-shown');
                           elementsToHide.show();
                           //in case profile info is also being added, do the js for that form
                           jQuery(form).trigger('omeka:elementformload');
                           jQuery('.contribution-userprofile-visibility').click(toggleProfileEdit);
                       });
                    });
                }
            });
        });
    });
}

