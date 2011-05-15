<?php
/*
Plugin Name: Facebook Like Button for Dummies
Plugin URI: http://devcorner.georgievi.net/pages/wordpress/wp-plugins/facebook-like-button-for-dummies
Description: Automatically add Facebook Like button to posts, pages and archives, enable OpenGraph protocl support or Facebook content filter.
Version: 1.2
Author: Ivan Georgiev
Author URI: http://devcorner.georgievi.net/
License: GPL2
*/
/*
Copyright 2011 Ivan Georgiev  (email : baobab@abv.bg)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('init', create_function('', 'new com_bul7_wp_FacebookLikeButtonForDummies();'));


/**
 * @property boolean $hideAllLikeButtons
 * @property boolean $beforeSingleShow
 * @property boolean $afterSingleShow
 * @property boolean $beforePageShow
 * @property boolean $afterPageShow
 * @property boolean $openGraphEnable
 * @property string $openGraphImage
 * @property int $openGraphDescLen
 * @property string $fbAdmins
 * @property string $hidePoweredBy
 */
class com_bul7_wp_FacebookLikeButtonForDummies {
    public $options;
    private $homeUrl = 'http://devcorner.georgievi.net/pages/wordpress/wp-plugins/facebook-like-button-for-dummies';

    public function  __construct() {
        $this->readSettings();
        add_action('wp_head', array($this, 'goHead'));
        add_action('wp_footer', array($this, 'goFooter'));
        add_action('admin_menu', array($this, 'goAdmin'));
        if (!$this->hideAllLikeButtons) {
            add_filter('the_content', array($this, 'filterContent'));
        }
    }

    public function goHead() {
        if ($this->openGraphEnable) {
            $this->getOgMeta();
        }
    }

    public function goFooter() {
        if (! $this->hidePoweredBy) {
            echo ' <a href="'.$this->homeUrl.'" title="Facebook Like Button with Open Graph Support">Facebook Like Button for Dummies</a> ';
        }
    }

    public function goAdmin() {
        include("FacebookLikeButtonForDummiesAdmin.php");
        new com_bul7_wp_FacebookLikeButtonForDummiesAdmin($this);
    }

    public function filterContent($content) {
        $button = '<fb:like href="'
                    . urlencode(get_permalink())
                    . '"  show_faces="true" width="490" font=""'
                    . "\"></fb:like>\r\n";
        $pageType = $this->getPageType();
        $content = ($this->{'before'.$pageType.'Show'} ? $button : '') .
            $content .
            ($this->{'after'.$pageType.'Show'} ? $button : '');
        $content .= '<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><!-- Do not remove -->';
        
        return $content;
    }

    private function getDefaults() {
        return array(
            'hideAllLikeButtons' => FALSE,
            'beforeSingleShow' => FALSE,
            'afterSingleShow' => TRUE,
            'beforePageShow' => FALSE,
            'afterPageShow' => TRUE,
            'openGraphEnable' => TRUE,
            'openGraphImage' => '',
            'openGraphDescLen' => 200,
            'fbAdmins' => '',
            'hidePoweredBy' => FALSE,
            );
    }

    public function __get($name) {
        return isset($this->options[$name]) ? $this->options[$name] : NULL;
    }

    public function __set($name, $value) {
        $this->options[$name] = $value;
    }


    private function getOgMeta() {
        $nl = "\n";
        echo '<meta property="og:site_name" content="'.wp_specialchars(get_option('blogname')).'" />'.$nl;
        echo '<meta property="og:title" content="'.trim(wp_title('', FALSE)).'" />'.$nl;
        echo '<meta property="og:type" content="'. (is_home() ? 'blog' : 'article') .'" />'.$nl;
        echo '<meta property="og:url" content="'.get_permalink().'" />'.$nl;
        if ($desc = $this->getDescription()) {
            echo '<meta property="og:description" content="'.  wp_specialchars($desc).'" />'.$nl;
        }
        if ($image = $this->getImage()) {
            echo '<meta property="og:image" content="'.$image.'" />'.$nl;
        }
        if ($this->fbAdmins) {
            echo '<meta property="fb:admins" content="'.wp_specialchars($this->fbAdmins).'" />'.$nl;
        }
?>
<?php
    }

    private function readSettings() {
        $this->options = array_merge($this->getDefaults(), (array)get_option(get_class($this)));
    }

    public function saveSettings() {
        update_option(get_class($this), $this->options);
    }

    private function getPageType() {
        $types = array('home', 'single', 'page', 'category', 'tag', 'author', 'date', 'search', '404');
        foreach ($types as $t) {
            $f = "is_$t";
            if ($f()) {
                return ucfirst($t);
            }
        }
    }

    private function getDescription() {
        $m = 'get'.$this->getPageType().'Desc';
        return (method_exists($this, $m)) ? $this->$m() : NULL;
    }

    private function getPostDesc() {
        global $post;
        if ($post->post_excerpt) {
            return $post->post_excerpt;
        }
        if ($morePos = strpos($post->post_content, '<!--more-->')) {
            return $this->stripTags(substr($post->post_content, 0, $morePos));
        }
        $charLen = 0;
        $extract = '';
        foreach (explode(' ', $this->stripTags($post->post_content)) as $i => $w) {
            if ($charLen >= $this->openGraphDescLen) {
                $extract .= '...';
                break;
            }
            $extract .= ($i>0 ? ' ' : '') . $w;
            $charLen += strlen($w);
        }
        return $extract;
    }

    private function stripTags($text) {
        return preg_replace('/\s+/', ' ', strip_tags($text));
    }
    
    private function getSingleDesc() { return $this->getPostDesc(); }
    private function getPageDesc() { return $this->getPostDesc(); }
    
    private function getImage() {
        global $post;
        if (is_single() || is_page()) {
            $imgs = get_children('post_type=attachment&post_mime_type=image&post_parent='.$post->ID);
            if (is_array($imgs) && !empty($imgs)) {
                return wp_get_attachment_thumb_url(current($imgs)->ID);
            } else if ($img = $this->getFirstImage()) {
                return $img;
            }
        }
        return $this->openGraphImage;
    }

    private function getFirstImage() {
          global $post;
          $first_img = '';
          if (!preg_match('/<img[^>]*src=([\'"])(.*?)\\1/i', $post->post_content, $matches)) {
              return NULL;
          }
          return $matches[2];
    }
}


?>