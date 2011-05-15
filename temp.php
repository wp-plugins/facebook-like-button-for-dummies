<meta property="og:title" content="
<?php if (is_home ()) { ?><? bloginfo('name'); ?> | <?php bloginfo('description'); ?><?php } ?>
<?php if (is_search ()) { ?><? bloginfo('name'); ?> | <?php echo $s; ?><?php } ?>
<?php if (is_single ()) { ?><? bloginfo('name'); ?> | <?php wp_title(''); ?>
<?php } ?> <?php if (is_page ()) { ?><? bloginfo('name'); ?> | <?php wp_title(''); ?><?php } ?> <?php if (is_category ()) { ?><? bloginfo('name'); ?> | Катгория: <?php single_cat_title(); ?><?php } ?> <?php if (is_month ()) { ?><? bloginfo('name'); ?> | Архив за: <?php the_time('F'); ?><?php } ?> <?php if (is_tag ()) { ?><? bloginfo('name'); ?> | Теми относно: <?php single_tag_title(); ?><?php } ?> "/>
<meta property="og:type" content="<?php if (is_home ()) { ?>blog<?php } else { ?>article<?php } ?>"/>
<meta property="og:url" content="<?php if (is_home ()) { ?><?php bloginfo('url'); ?><?php } else { ?><?php the_permalink(); ?><?php } ?>"/>
<meta property="og:image" content="<?php echo catch_first_image() ?>"/>
<meta property="og:site_name" content="<? bloginfo('name'); ?>"/>
<meta property="og:description" content="        <?php if (is_home ()) { ?><?php bloginfo('description'); ?><?php } ?> <?php if (is_search ()) { ?>Всички теми относно: <?php echo $s; ?><?php } ?> <?php if (is_single ()) { ?> <?php

    function fb_meta_desc() {
        global $post;
        $meta = strip_tags($post->post_content);
        $meta = str_replace(array("\n", "\r", "\t", '"', "'"), ' ', $meta);
        $meta = substr($meta, 0, 200);
        echo $meta;
    }

fb_meta_desc(); ?> <?php } ?> <?php if (is_page ()) { ?> <?php

    function fb_meta_desc() {
        global $post;
        $meta = strip_tags($post->post_content);
        $meta = str_replace(array("\n", "\r", "\t"), ' ', $meta);
        $meta = substr($meta, 0, 200);
        echo $meta;
    }

fb_meta_desc(); ?> <?php } ?> <?php if (is_category ()) { ?>Категория: <?php single_cat_title(); ?><?php } ?> <?php if (is_month ()) { ?>Архив за <?php the_time('F'); ?><?php } ?> <?php if (is_tag ()) { ?>Всички теми относно: <?php single_tag_title(); ?><?php } ?> "/>

