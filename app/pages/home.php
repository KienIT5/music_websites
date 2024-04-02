<?php 
 require page('includes/header')

?>

	<section>
		<img class="hero" src="<?=ROOT?>/assets/images/hero.jpg">
	</section>
	
	<div class="section-title">Featured</div>

	<section class="content">
		
		<?php 
			$rows = db_query("select * from songs order by id desc limit 16");
		?>

		<?php if(!empty($rows)):?>
			<?php foreach($rows as $row):?>
				<?php include page('includes/song')?>
			<?php endforeach;?>
		<?php else:?>
			<div class="m-2">No songs found</div>
		<?php endif;?>

	</section>

<?php require page('includes/footer')?>
