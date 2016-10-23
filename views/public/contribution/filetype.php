<?php 
$head = array('title' => __('Accepted File Types'));
echo head($head);
?>

<div id="primary">
<h1><?php echo $head['title']; ?></h1>
<?php 
	  $typeList = option('file_extension_whitelist');
	  $typeArray = explode(',', $typeList);	
	  $count = count($typeArray); ?>
	  
    <div id="acceptedTypes">
    <p>
		<?php	  
              for($i = 0; $i < $count; $i++){
                echo $typeArray[$i];
                if($i < $count-1){
                    echo ", ";	
                }
              }
         ?>
    </p>
    </div>
</div>
<?php echo foot(); ?>