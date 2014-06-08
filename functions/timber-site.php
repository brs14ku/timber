<?php

namespace Timber;

class Site extends Core
{
    public $blogname;
    public $charset;
    public $description;
    public $id;
    public $language;
    public $language_attributes;
    public $multisite;
    public $name;
    public $pingback_url;
    public $siteurl;
    public $theme;
    public $title;
 
    /**
     * @param string|int $site_name_or_id
     */
    function __construct($site_name_or_id = null) {
        if (is_multisite()) {
            $this->init_with_multisite($site_name_or_id);
        } else {
            $this->init();
        }
    }

    /**
     * @param string|int $site_name_or_id
     */
    function init_with_multisite($site_name_or_id) {
        if ($site_name_or_id === null) {
            //this is necessary for some reason, otherwise returns 1 all the time
            if (is_multisite()) {
                restore_current_blog();
                $site_name_or_id = get_current_blog_id();
            }
        }
        $info = get_blog_details($site_name_or_id);
        $this->import($info);
        $this->ID = $info->blog_id;
        $this->name = $this->blogname;
        $this->title = $this->blogname;
        $this->url = $this->siteurl;
        $this->id = $this->ID;
        $theme_slug = get_blog_option($info->blog_id, 'stylesheet');
        $this->theme = new Theme($theme_slug);
        $this->description = get_blog_option($info->blog_id, 'blogdescription');
        $this->multisite = true;
    }

    function init() {
        $this->name = get_bloginfo('name');
        $this->title = $this->name;
        $this->description = get_bloginfo('description');
        $this->url = get_bloginfo('url');
        $this->language = get_bloginfo('language');
        $this->charset = get_bloginfo('charset');
        $this->pingback_url = get_bloginfo('pingback_url');
        $this->theme = new Theme();
        $this->language_attributes = Helper::function_wrapper('language_attributes');
        $this->multisite = false;
    }

    /**
     * @param string $field
     * @return mixed
     */
    function __get($field) {
        if (!isset($this->$field)) {
            $this->$field = get_blog_option($this->ID, $field);
        }
        return $this->$field;
    }

    /**
     * @return string
     */
    function get_link() {
        return $this->siteurl;
    }

    /**
     * @return string
     */
    function get_url() {
        return $this->get_link();
    }

    /**
     * @return string
     */
    function link() {
        return $this->get_link();
    }

    /**
     * @return string
     */
    function url() {
        return $this->get_link();
    }
}

class_alias('Timber\Site', 'TimberSite');
