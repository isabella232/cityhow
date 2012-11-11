<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>
<?php
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
if ($term->name != 'Any City') {
	$city_name = substr($term->name,0,-3); //remove state
}
else {
	$city_name = $term->name;
}
$user_city_slug = strtolower($user_city);
$user_city_slug = str_replace(' ','-',$user_city_slug);
?>
<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">
<?php
// if user city or any city
if ($term->name == $user_city OR $term->name == 'Any City') :
?>			
			<div id="row-fluid">
				<div class="span8 content-faux">						
					<h3 class="page-title">
<?php
if ($city_name != 'Any City') {
	echo 'City of ';
}
echo $city_name;?></h3>
					<div class="intro-block noborder"><p>Suggest an idea for a new CityHow Guide, create your own Guide, or ask another employee to write one.</p>
					</div>
				</div>
				
				<div class="span4 sidebar-faux">
<?php if (is_user_logged_in()) : ?>				
					<div class="widget-side">
						<div class="widget-copy">					
							<div class="sidebar-buttons"><a href="<?php echo $app_url;?>/add-idea" title="Add your idea"><button class="nh-btn-blue-med btn-fixed">Add an Idea for a Guide</button></a></div>
							<div class="sidebar-buttons"><a href="<?php echo $app_url;?>/create-guide" title="Create a CityHow Guide"><button class="nh-btn-blue-med btn-fixed">Create a CityHow Guide</button></a></div>
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
			</div><!-- /row-fluid-->

			<div class="content-full">
				<div id="list-city">					
					<h5 class="widget-title">Guides for <?php
if ($city_name != 'Any City') {
	echo 'the City of ';
}
echo $city_name;
?></h5>
					<ul class="list-city">												
<?php 
$post_cities = wp_get_post_terms($post->ID,'nh_cities');
$term = array_pop($post_cities);
// only show match to term name
$arg_terms = array($term->name);
$guide_cat = get_category_id('guides');
$city_args = array(
	'post_status' => 'publish',
	'orderby' => 'date',
	'order' => 'DESC',
	'posts_per_page' => '12',
	'paged' => get_query_var('paged'),
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
			'terms' => $arg_terms
		)	
	)
);
$city_query = new WP_Query($city_args);
	if ($city_query->have_posts()) : 
		while($city_query->have_posts()) : $city_query->the_post();
		$imgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');	
?>
<li class="city-list" id="post-<?php echo $post->ID;?>"><a class="nhline link-other" rel="bookmark" title="See <?php echo the_title();?>" href="<?php the_permalink();?>"><img src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $imgSrc[0];?>&w=184&h=115&zc=1&a=t" alt="Photo from <?php echo the_title();?>" />
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
?>
<?php 	
	else : 
?>
<li style="margin-left:1.5em !important;border:none;width:100%;" class="city-list" id="nopost">There are no public CityHow Guides for <?php 
if ($city_name != 'Any City') {
	echo 'the City of ';
}
echo $city_name;
?>. <a href="'.$app_url.'/create-guide" title="Create a CityHow Guide">Create one!</a></li>
					</ul>
<?php
	endif; // if posts
wp_reset_query();
?>
				</div><!--/ list city-->
				
				<div id="list-ideas-city">
					<h5 class="widget-title">Ideas for <?php
if ($city_name != 'Any City') {
	echo 'the City of ';
}
echo $city_name;
?></h5>
					<ul class="list-ideas-city">

<?php 
$idea_cat = get_category_id('ideas');
// only show match to term name
$arg_ideas = array($term->name);
$idea_args = array(
	'post_type' => array('post'), //include projects
	'post_status' => 'publish',
	'orderby' => 'date',
	'order' => 'DESC',
	'meta_key' => 'nh_idea_city',
	'posts_per_page' => '-1',
	'paged' => get_query_var('paged'),
	'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'category',
			'field' => 'slug',
			'terms' => array( 'ideas' )
		),
		array(
			'taxonomy' => 'nh_cities',
			'field' => 'slug',
			'terms' => $arg_ideas
		)	
	)
);
$idea_query = new WP_Query($idea_args);
	if ($idea_query->have_posts()) : 
		while($idea_query->have_posts()) : $idea_query->the_post();	
?>
<li class="idea-city-list" id="post-<?php echo $post->ID;?>"><a class="nhline" rel="bookmark" title="See <?php echo the_title();?>" href="<?php the_permalink();?>"><?php echo the_title();?></a>	
</li>
<?php 
		endwhile; 
?>
<?php 
	else : 
?>
<li style="margin-left:1.5em !important;border:none;width:100%;" class="idea-list" id="nopost">There are no Ideas yet for
<?php 
if ($city_name != 'Any City') {
	echo 'the City of ';
}
echo $city_name;
?>. <a href="'.$app_url.'/add-idea" title="Add Your Idea">Add your own idea!</a></li>
					</ul>
<?php
endif;
wp_reset_query();					 
?>								
				</div><!--/ list ideas-->

<?php
$users = $wpdb->get_results("SELECT * from nh_usermeta where meta_value = '".$user_city."' AND meta_key = 'user_city'");		
$users_count = count($users);
// dont show users for Any City
if ($users AND $user_city == $term->name) :
?>				
				<div id="list-people">					
					<h5 class="widget-title">People in 
<?php 
if ($city_name != 'Any City') {
	echo 'the City of ';
}
echo $city_name;
?></h5>
					<ul class="list-people">
<?php
foreach ($users as $user) {
	$user_data = get_userdata($user->user_id);

	$user_name = $user_data->first_name.' '.$user_data->last_name;
	$user_avatar = get_avatar($user_data->ID,'72','identicon','');

	echo '<li class="people-list">';
	echo '<a href="'.$app_url.'/author/'.$user_data->user_login.'" class="cityuser" rel="tooltip" data-placement="top" data-title="<strong>'.$user_name.'</strong><br/>';

	$user_content_count = nh_get_user_posts_count($user_data->ID,array(
		'post_type' =>'post',
		'post_status'=> 'publish',
		'posts_per_page' => -1
		));
	if ($user_content_count) {
		if ($user_content_count == '1') {
			echo $user_content_count.'&nbsp;article';
		}
		elseif ($user_content_count > 1) {
			echo $user_content_count.'&nbsp;articles';
		}
	}
	$user_likes = get_user_meta($user_data->ID,'nh_li_user_loves');
	foreach ($user_likes as $like) {
		$user_likes_count = count($like);
		if ($user_likes_count) {
			if ($user_likes_count == '1') {
				echo ' &nbsp;&#8226;&nbsp; '.$user_likes_count.'&nbsp;like';
			}
			elseif ($user_likes_count > 1) {
				echo ' &nbsp;&#8226;&nbsp; '.$user_likes_count.'&nbsp;likes';
			}
		}
	}
	$comment_args = array('user_id' => $user_data->ID);   
	$comments = get_comments($comment_args);
	$user_comments_count = count($comments);
	if ($user_comments_count) {
		if ($user_comments_count == '1') {
			echo ' &nbsp;&#8226;&nbsp; '.$user_comments_count.'&nbsp;comment';
		}
		elseif ($user_comments_count > 1) {
			echo ' &nbsp;&#8226;&nbsp; '.$user_comments_count.'&nbsp;comments';
		}
	}

	$user_votes = get_user_meta($user_data->ID,'nh_user_votes');
	foreach ($user_votes as $vote) {
		$user_votes_count = count($vote);
		if ($user_votes_count) {
			if ($user_votes_count == '1') {
				echo ' &nbsp;&#8226;&nbsp; '.$user_votes_count.'&nbsp;vote';
			}
			elseif ($user_votes_count > 1) {
				echo ' &nbsp;&#8226;&nbsp; '.$user_votes_count.'&nbsp;votes';
			}
		}
	}
	echo '">'.$user_avatar.'</a>';
	echo '</li>';
}
?>

					</ul>
				</div><!--/ list people-->
														
<?php elseif (!$users AND $user_city) :
?>	
					<div id="list-people">					
						<h5 class="widget-title">People in 
<?php 
if ($city_name != 'Any City') {
	echo 'the City of ';
}
echo $city_name;
?></h5>
	<li style="margin-left:1.5em !important;border:none;width:100%;" class="people-list" id="nopost">There are no registered users from 
<?php
if ($city_name != 'Any City') {
	echo 'the City of ';
}
echo $city_name.' ';
?>
yet. <a href="<?php echo $app_url;?>/register" title="Create an account">Create an Account!</a></li>
<?php
endif;
wp_reset_query();					 
?>				
					</ul>		
				</div><!--/ list people-->
			</div><!--/ content-full -->		
<?php else : // if not user city or any city ?>
				<div class="span7">						
					<h3 class="page-title">
<?php
if ($city_name != 'Any City') {
	echo 'City of ';
}
echo $city_name;?></h3>
					<div class="intro-block noborder" style="min-height:400px;"><p>Sorry ... content for this city is only available to employees of the City of <?php echo $city_name;?></p>
					</div>
				</div>
			</div><!-- /row-fluid-->
<?php endif; // end if user city or any city ?>		
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>