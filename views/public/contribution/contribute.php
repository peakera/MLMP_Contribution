<?php
/**
 * @version $Id$
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2010
 * @package Contribution
 */

queue_js_url("http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js");
// queue_js_url("http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js");
queue_js_file('contribution-public-form');
queue_js_file('jquery.easing.1.3');
queue_js_file('jquery.touchSwipe.min');
queue_js_file('jquery.liquid-slider');
queue_css_file('liquid-slider');

$contributionPath = get_option('contribution_page_path');
if(!$contributionPath) {
    $contributionPath = 'contribution';
}

queue_css_file('form');

$head = array('title' => 'Contribute',
              'bodyclass' => 'contribution');
echo head($head); 

?>


    
<script type="text/javascript">
//Enable the "Type" buttons
// <![CDATA[
jQuery(document).ready(function() {            
enableContributionTypeButtons(<?php echo js_escape(url($contributionPath.'/type-form')); ?>);
});

// ]]>

</script>

<div id="primary">

<?php echo flash(); ?>
    
    <h2>What type of item would you like to contribute?</h2>
      
        <?php $options = get_table_options('ContributionType' ); ?>
        
        <ul id = "section-nav" class="contributionTypeTabs">
        	<?php 
			$tabs = array();
			foreach ($options as $id=>$option): ?>
				<li class="contributionTypeTab">
            		<a href='' class='type-option' value='<?php echo $id; ?>'><?php echo $option; ?></a>      		
                </li>
		     <?php endforeach; ?>
		</ul>
		

        <div id='type-form'>                   
        </div> <!-- Closes type-form div (set with AJAX, see type-form.php) -->
   
        
</div><!-- Ends primary div -->

                
<?php echo foot();
