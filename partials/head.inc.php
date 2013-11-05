<?php
/**
 * Include for site's head and header sections.
 *
 * @package ProcessWire
 * @since Theme_Name 1.0
 */
?>
<!doctype html>
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="<?= __('en', 'theme_text_domain'); ?>"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9 oldie" lang="<?= __('en', 'theme_text_domain'); ?>"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="<?= __('en', 'theme_text_domain'); ?>"> <!--<![endif]-->
<?php include('./partials/meta.inc.php'); ?>
<body>

	<div id="masthead" class="masthead">

		<div class="container">

			<a href='<?php echo $config->urls->root; ?>'><p id='logo'>ProcessWire</p></a>

			<ul id='topnav'><?php

				// Create the top navigation list by listing the children of the homepage.
				// If the section we are in is the current (identified by $page->rootParent)
				// then note it with <a class='on'> so we can style it differently in our CSS.
				// In this case we also want the homepage to be part of our top navigation,
				// so we prepend it to the pages we cycle through:

				$homepage = $pages->get("/");
				$children = $homepage->children;
				$children->prepend($homepage);

				foreach($children as $child) {
					$class = $child === $page->rootParent ? " class='on'" : '';
					echo "<li><a$class href='{$child->url}'>{$child->title}</a></li>";
				}

			?></ul>

			<h1 id='title'><?php

				// The statement below asks for the page's headline or title.
				// Separating multiple fields with a pipe "|" returns the first
				// one that has a value. So in this case, we print the headline
				// field if it's there, otherwise we print the title.

				echo $page->get("headline|title");

			?></h1>

			<form id='search_form' action='<?php echo $config->urls->root?>search/' method='get'>
				<input type='text' name='q' id='search_query' value='<?php echo htmlentities($input->whitelist('q'), ENT_QUOTES, 'UTF-8'); ?>' />
				<button type='submit' id='search_submit'>Search</button>
			</form>

			<?php

			// Grab a random image from the homepage and display it.
			// Note that $homepage was loaded above where we generated the top navigation.

			if(count($homepage->images)) {
				$image = $homepage->images->getRandom();
				$thumb = $image->size(232, 176);
				echo "<a href='{$image->url}'><img id='photo' src='{$thumb->url}' alt='{$thumb->description}' width='{$thumb->width}' height='{$thumb->height}' /></a>";
			}

			?>

		</div>
	</div>

	<div id="content" class="content">

		<div class="container">

			<div id="sidebar">

				<?php

				// Output subnavigation
				//
				// Below we check to see that we're not on the homepage, and that
				// there are at least one or more pages in this section.
				//
				// Note $page->rootParent is always the top level section the page is in,
				// or to word differently: the first parent page that isn't the homepage.

				if($page->path != '/' && $page->rootParent->numChildren > 0) {

					// We have determined that we're not on the homepage
					// and that this section has child pages, so make navigation:

					echo "<ul id='subnav' class='nav'>";

					foreach($page->rootParent->children as $child) {
						$class = $page === $child ? " class='on'" : '';
						echo "<li><a$class href='{$child->url}'>{$child->title}</a></li>";
					}

					echo "</ul>";
				}

				?>

				<div class='sidebar_item'>

					<?php

					// if the current page has a populated 'sidebar' field, then print it,
					// otherwise print the sidebar from the homepage

					if($page->sidebar) echo $page->sidebar;
						else echo $homepage->sidebar;
					?>


				</div>

			</div><!--/sidebar-->

			<div id="bodycopy">

