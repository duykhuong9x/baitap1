<?php
/**
 * Import less css variable
*/
?>
<?php if(isset($_GET['@function_url'])):?>
	<?php foreach(explode(',',$_GET['@function_url']) as $file):?>
		@import <?php echo $file;?>;
	<?php endforeach;?>
<?php endif;?>
<?php if(isset($_GET['@variables_url'])):?>
	<?php foreach(explode(',',$_GET['@variables_url']) as $file):?>
		@import <?php echo $file;?>;
	<?php endforeach;?>
<?php endif;?>
<?php
foreach($_GET as $typo => $value){
	if($typo != 'custom_css_files')
		echo "$typo:$value;";
}
?>
<?php if(isset($_GET['custom_css_files'])):?>
	<?php foreach(explode(',',$_GET['custom_css_files']) as $file):?>
		@import "<?php echo $file;?>";
	<?php endforeach;?>
<?php endif;?>