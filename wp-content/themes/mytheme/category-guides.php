<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>
<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">
<?php
// limit list to user city
$city_terms = get_terms('nh_cities');
foreach ($city_terms as $city_term) {
	$city_term = $city_term->name;
	if ($city_term == $user_city OR $city_term == 'Any City') {
		$cities[] = $city_term;
	}
}
foreach ($cities as $city) {
	if ($city != 'Any City') {
		$city_name = substr($city,0,-3); //remove state
	}
	else {
		$city_name = $city;
	}	
}

$user_city_slug = strtolower($user_city);
$user_city_slug = str_replace(' ','-',$user_city_slug);
?>			
			<div class="row-fluid">
				<div class="span8 content-faux">	
					<h3 class="page-title">Guides for <?php echo $city_name;?></h3>
					<div class="intro-block noborder"><p>A CityHow Guide can be about anything that&#39;s useful to employees working for 
<?php
if ($city_name != 'Any City') {
	echo 'the City of '.$city_name;
}
elseif ($city_name == 'Any City') {
	echo 'your city';
}
echo '. Or it could be about something that&#39;s helpful to city employees working in any city.</p><p>If it&#39;s something you know how to do, it&#39;s probably something other people want to know how to do. So suggest a topic for a new CityHow Guide, create your own Guide, or ask another employee to write one.</p>';
?>
					</div>
				</div>
				<div class="span4 sidebar-faux">
<?php if (is_user_logged_in()) : ?>				
					<div class="widget-side">
						<h5 class="widget-title">Help Make CityHow Better</h5>						
						<div class="widget-copy">					
							<div class="sidebar-buttons"><a href="<?php echo $app_url;?>/add-idea" title="Add your idea"><button class="nh-btn-blue-med btn-fixed">Add an Idea for a Guide</button></a>
								<p>Help decide what content should be part of CityHow for your city.</p></div>
							<div class="sidebar-buttons"><a href="<?php echo $app_url;?>/create-guide" title="Create a CityHow Guide"><button class="nh-btn-blue-med btn-fixed">Create a CityHow Guide</button></a>
								<p>Share what you know about working in city government with others.</p></div>
						</div><!--/ widget copy-->
					</div><!--/ widget-->							
<?php else : ?>		
			<div class="widget-side">
				<h5 class="widget-title">Sign In to see your city's content</h5>				
				<div class="widget-copy">		
					<div class="sidebar-buttons"><a href="<?php echo $app_url;?>/signin" title="Sign In to CityHow"><button class="nh-btn-blue-med btn-fixed">Sign In to CityHow</button></a></div>
					<div class="sidebar-buttons"><a href="<?php echo $app_url;?>/register" title="Create an account"><button class="nh-btn-blue-med btn-fixed">Create an Account</button></a></div>	
					</div><!--/ widget copy-->
				</div><!--/ widget-->					
<?php endif; ?>
									
				</div><!--/ sidebar-->
			</div><!--/ row-fluid-->
			
			<div id="content-full" class="row-fluid">
				<div class="span12" id="list-guides">
					<ul class="list-guides">

<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$guide_cat = get_category_id('guides');
$list_args = array(
	'post_status' => 'publish',
	'posts_per_page' => 12,
	'paged' => $paged,
	'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'category',
			'field' => 'slug',
			'terms' => array( 'guides' )
		),
		array(
			'taxonomy' => 'nh_cities',
			'field' => 'slug',
			'terms' => array( $user_city_slug,'any-city' )
		)	
	)		
);
$list_query = new WP_Query($list_args);
if ($list_query->have_posts()) : 
	while($list_query->have_posts()) : $list_query->the_post();
$imgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
?>

<li class="guides-list" id="post-<?php echo $post->ID;?>"><a class="nhline link-other" rel="bookmark" title="See <?php echo the_title();?>" href="<?php the_permalink();?>"><img src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $imgSrc[0];?>&w=184&h=115&zc=1&a=t" alt="Photo from <?php echo the_title();?>" />
	
	<div class="home-caption">
<?php
$pad = ' ...';
$pic_title = trim_by_chars(get_the_title(),'60',$pad);
?>
		<p><?php echo $pic_title;?></a></p>		
	</div>	
</li>

<?php 
	endwhile; 
else :
?>	
<li class="guides-list" id="post-no-guides">Sorry, there are no public CityHow Guides to see at this time.</li>
<?php 
endif; 

$big = 999999999; // need an unlikely integer
echo paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link($big) ) ),
	'format' => '?paged=%#%',
	'current' => max(1, get_query_var('paged')),
	'total' => $wp_query->max_num_pages
));
wp_reset_query();
?>	
					</ul><!-- / list-guides-->							
				</div>
			</div><!--/ row-fluid-->
				
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>