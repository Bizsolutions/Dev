<?php

class B2S_RePost_Save {

    private $title;
    private $contentHtml;
    private $postId;
    private $content;
    private $excerpt;
    private $url;
    private $imageUrl;
    private $keywords;
    private $blogUserId;
    private $userTimezone;
    private $setPreFillText;
    private $optionPostFormat;
    private $allowHashTag;
    private $bestTimes;
    private $userVersion;
    private $defaultPostData;
    private $b2sUserLang;
    private $notAllowNetwork;
    private $allowNetworkOnlyImage;
    private $tosCrossPosting;
    private $linkNoCache;

    function __construct($blogUserId = 0, $b2sUserLang = 'en', $userTimezone = 0, $optionPostFormat = array(), $allowHashTag = true, $bestTimes = array(), $userVersion = 0) {
        $this->userVersion = defined("B2S_PLUGIN_USER_VERSION") ? B2S_PLUGIN_USER_VERSION : (int) $userVersion;
        $this->blogUserId = $blogUserId;
        $this->userTimezone = $userTimezone;
        $this->optionPostFormat = $optionPostFormat;
        $this->allowHashTag = $allowHashTag;
        $this->b2sUserLang = $b2sUserLang;
        $this->bestTimes = (!empty($bestTimes)) ? $bestTimes : array();
        $this->setPreFillText = array(0 => array(6 => 300, 8 => 239, 9 => 200, 10 => 442, 16 => 250, 17 => 442, 18 => 800, 21 => 65000), 1 => array(8 => 1200, 10 => 442, 17 => 442, 19 => 239), 2 => array(8 => 239, 10 => 442, 17 => 442, 19 => 239), 20 => 300);
        $this->setPreFillTextLimit = array(0 => array(6 => 400, 8 => 400, 9 => 200, 10 => 500, 18 => 1000, 20 => 400, 16 => false, 21 => 65535), 1 => array(8 => 1200, 10 => 500, 19 => 400), 2 => array(8 => 400, 10 => 500, 19 => 9000));
        $this->notAllowNetwork = array(4, 11, 14, 16, 18);
        $this->allowNetworkOnlyImage = array(6, 7, 12, 21);
        $this->tosCrossPosting = unserialize(B2S_PLUGIN_NETWORK_CROSSPOSTING_LIMIT);
        $this->linkNoCache = B2S_Tools::getNoCacheData(B2S_PLUGIN_BLOG_USER_ID);
    }
    
    public function setPostData($postId = 0, $title = '', $content = '', $excerpt = '', $url = '', $imageUrl = '', $keywords = '') {
        $this->postId = $postId;
        $this->title = $title;
        $this->content = B2S_Util::prepareContent($postId, $content, $url, false, true, $this->b2sUserLang);
        $this->excerpt = B2S_Util::prepareContent($postId, $excerpt, $url, false, true, $this->b2sUserLang);
        $this->contentHtml = B2S_Util::prepareContent($postId, $content, $url, '<p><h1><h2><br><i><b><a><img>', true, $this->b2sUserLang);
        $this->url = $url;
        $this->imageUrl = $imageUrl;
        $this->keywords = $keywords;
        $this->defaultPostData = array(
            'default_titel' => $title,
            'image_url' => ($imageUrl !== false) ? trim(urldecode($imageUrl)) : '',
            'lang' => $this->b2sUserLang,
            'no_cache' => 0, //default inactive , 1=active 0=not
            'board' => '',
            'group' => '',
            'url' => $url,
            'user_timezone' => $this->userTimezone
        );
    }
    
    public function generatePosts($startDate = '0000-00-00', $settings = array(), $networkData = array(), $twitter = '') {
        foreach ($networkData as $k => $value) {
            if (isset($value->networkAuthId) && (int) $value->networkAuthId > 0 && isset($value->networkId) && (int) $value->networkId > 0 && isset($value->networkType)) {
                //Filter: Image network
                if (in_array((int) $value->networkId, $this->allowNetworkOnlyImage) && empty($this->imageUrl)) {
                    continue;
                }
                //Filter: Blog network
                if (in_array((int) $value->networkId, $this->notAllowNetwork)) {
                    continue;
                }
                //Filter: TOS Crossposting ignore
                if (isset($this->tosCrossPosting[$value->networkId][$value->networkType])) {
                    continue;
                }
                //Filter: DeprecatedNetwork-8 31 march
                if ((int) $value->networkId == 8) {
                    continue;
                }
                $selectedTwitterProfile = (isset($twitter) && !empty($twitter)) ? (int) $twitter : '';
                if ((int) $value->networkId != 2 || ((int) $value->networkId == 2 && (empty($selectedTwitterProfile) || ((int) $selectedTwitterProfile == (int) $value->networkAuthId)))) {
                    $schedDate = $this->getPostDateTime($startDate, $settings, $value->networkAuthId);
                    $schedDateUtc = date('Y-m-d H:i:s', strtotime(B2S_Util::getUTCForDate($schedDate, ($this->userTimezone * -1))));
                    $shareApprove = (isset($value->instant_sharing) && (int) $value->instant_sharing == 1) ? 1 : 0;
                    $defaultPostData = $this->defaultPostData;
                    if ((int) $value->networkId == 1 || (int) $value->networkId == 3 || (int) $value->networkId == 19) {
                        if (is_array($this->linkNoCache) && isset($this->linkNoCache[$value->networkId]) && (int) $this->linkNoCache[$value->networkId] > 0) {
                            $defaultPostData['no_cache'] = $this->linkNoCache[$value->networkId];
                        }
                    }
                    $schedData = $this->prepareShareData($value->networkAuthId, $value->networkId, $value->networkType);
                    if ($schedData !== false && is_array($schedData)) {
                        $schedData = array_merge($schedData, $defaultPostData);
                    }
                    $this->saveShareData($schedData, $value->networkId, $value->networkType, $value->networkAuthId, $shareApprove, strip_tags($value->networkUserName), $schedDate, $schedDateUtc);
                }
            }
        }
    }
    
    public function getPostDateTime($startDate = '0000-00-00', $settings = array(), $networkAuthId = 0) {
        $date = new DateTime($startDate);
        if (!empty($this->bestTimes) && isset($this->bestTimes['delay_day'][$networkAuthId]) && isset($this->bestTimes['time'][$networkAuthId]) && !empty($this->bestTimes['time'][$networkAuthId])) {
            if((int) $this->bestTimes['delay_day'][$networkAuthId] > 0) {
                $date->modify('+' . $this->bestTimes['delay_day'][$networkAuthId] . ' days');
            }
            $time = $this->getTime($this->bestTimes['time'][$networkAuthId]);
            $date->setTime($time['h'], $time['m']);
        } else if (isset($settings['time']) && !empty($settings['time'])) {
            $time = $this->getTime($settings['time']);
            $date->setTime($time['h'], $time['m']);
        }
        if(isset($settings['type']) && (int) $settings['type'] == 0 && isset($settings['weekday']) && is_array($settings['weekday']) && !empty($settings['weekday'])) {
            while (!$settings['weekday'][(int) $date->format('w')]) {
                $date->modify('+1 days');
            }
        }
        return $date->format("Y-m-d H:i:s");
    }
    
    private function getTime($time) {
        $output = array('h' => (int) substr($time, 0, 2), 'm' => (int) substr($time, 3, 2));
        if(substr($time, -2) == "PM") {
            $output['h'] += 12;
        }
        return $output;
    }
    
    public function prepareShareData($networkAuthId = 0, $networkId = 0, $networkType = 0) {
        
        if(B2S_PLUGIN_USER_VERSION >= 3) {
            global $wpdb;
            $sqlGetData = $wpdb->prepare("SELECT `data` FROM `{$wpdb->prefix}b2s_posts_network_details` WHERE `network_auth_id` = %d", (int) $networkAuthId);
            $dataString = $wpdb->get_var($sqlGetData);
            if ($dataString !== NULL && !empty($dataString)) {
                $networkAuthData = unserialize($dataString);
                if(!empty($this->url) && $networkAuthData != false && is_array($networkAuthData) && isset($networkAuthData['url_parameter'][0]['querys']) && !empty($networkAuthData['url_parameter'][0]['querys'])) {
                    $this->url = B2S_Util::addUrlParameter($this->url, $networkAuthData['url_parameter'][0]['querys']);
                }
            }
        }
        
        if ((int) $networkId > 0 && (int) $networkAuthId > 0) {
            $postData = array('content' => '', 'custom_title' => '', 'tags' => array(), 'network_auth_id' => (int) $networkAuthId);

            if ((int)$this->userVersion < 1 || $this->optionPostFormat == false || !isset($this->optionPostFormat[$networkId][$networkType])) {
                $this->optionPostFormat = unserialize(B2S_PLUGIN_NETWORK_SETTINGS_TEMPLATE_DEFAULT);
            }
            
            //PostFormat
            if (in_array($networkId, array(1, 2, 3, 12, 19))) {
                //Get: client settings
                if (isset($this->optionPostFormat[$networkId][$networkType]['format']) && ((int) $this->optionPostFormat[$networkId][$networkType]['format'] === 0 || (int) $this->optionPostFormat[$networkId][$networkType]['format'] === 1)) {
                    $postData['post_format'] = (int) $this->optionPostFormat[$networkId][$networkType]['format'];
                } else {
                    //Set: default settings
                    $defaultPostFormat = unserialize(B2S_PLUGIN_NETWORK_SETTINGS_TEMPLATE_DEFAULT);
                    $postData['post_format'] = isset($defaultPostFormat[$networkId][$networkType]['format']) ? (int) $defaultPostFormat[$networkId][$networkType]['format'] : 0;
                }
            }
            //Special
            if (in_array($networkId, array(1, 2, 3, 12, 19))) {
                if ($networkId == 12 && $this->imageUrl == false) {
                    return false;
                }
                $postData['content'] = $this->optionPostFormat[$networkId][$networkType]['content'];
                
                $preContent = addcslashes(B2S_Util::getExcerpt($this->content, (int) $this->optionPostFormat[$networkId][$networkType]['short_text']['range_min'], (int) $this->optionPostFormat[$networkId][$networkType]['short_text']['range_max']),"\\$");   
                $postData['content'] = preg_replace("/\{CONTENT\}/", $preContent, $postData['content']);

                $title = sanitize_text_field($this->title);
                $postData['content'] = preg_replace("/\{TITLE\}/", addcslashes($title,"\\$"), $postData['content']);

                $defaultSchema = unserialize(B2S_PLUGIN_NETWORK_SETTINGS_TEMPLATE_DEFAULT);
                if (!isset($this->optionPostFormat[$networkId][$networkType]['short_text']['excerpt_range_max'])) {
                    $this->optionPostFormat[$networkId][$networkType]['short_text']['excerpt_range_max'] = $defaultSchema[$networkId][$networkType]['short_text']['excerpt_range_max'];
                }
                if (!isset($this->optionPostFormat[$networkId][$networkType]['short_text']['excerpt_range_min'])) {
                    $this->optionPostFormat[$networkId][$networkType]['short_text']['excerpt_range_min'] = $defaultSchema[$networkId][$networkType]['short_text']['excerpt_range_min'];
                }
                $excerpt = (isset($this->excerpt) && !empty($this->excerpt)) ? addcslashes(B2S_Util::getExcerpt($this->excerpt, (int) $this->optionPostFormat[$networkId][$networkType]['short_text']['excerpt_range_min'], (int) $this->optionPostFormat[$networkId][$networkType]['short_text']['excerpt_range_max']),"\\$") : '';
               
                $postData['content'] = preg_replace("/\{EXCERPT\}/", $excerpt, $postData['content']);

                $hashtagcount = substr_count($postData['content'], '#');
                if (strpos($postData['content'], "{KEYWORDS}") !== false) {
                    $hashtags = $this->getHashTagsString("", (($networkId == 12) ? 30-$hashtagcount : -1));
                    $postData['content'] = preg_replace("/\{KEYWORDS\}/", addcslashes($hashtags,"\\$"), $postData['content']);
                } else if ($this->allowHashTag) {
                    $add = ($networkId != 2) ? "\n\n" : "";
                    $hashtags = $this->getHashTagsString($add, (($networkId == 12) ? 30-$hashtagcount : -1));
                    $postData['content'] .= " " . $hashtags;
                }
                
                $authorId = get_post_field('post_author', $this->postId);
                if (isset($authorId) && !empty($authorId) && (int) $authorId > 0) {
                    $hook_filter = new B2S_Hook_Filter();
                    $author_name = $hook_filter->get_wp_user_post_author_display_name((int) $authorId);
                    $postData['content'] = stripslashes(preg_replace("/\{AUTHOR\}/", addcslashes($author_name, "\\$"), $postData['content']));
                } else {
                    $postData['content'] = preg_replace("/\{AUTHOR\}/", "", $postData['content']);
                }
            }

            if ($networkId == 4) {
                $postData['custom_title'] = strip_tags($this->title);
                $postData['content'] = $this->contentHtml;
                if ($this->allowHashTag) {
                    if (is_array($this->keywords) && !empty($this->keywords)) {
                        foreach ($this->keywords as $tag) {
                            $postData['tags'][] = str_replace(" ", "", $tag->name);
                        }
                    }
                }
            }

            if ($networkId == 6 || $networkId == 20) {
                if ($this->imageUrl !== false) {
                    $postData['content'] = (isset($this->setPreFillText[$networkType][$networkId])) ? B2S_Util::getExcerpt($this->content, (int) $this->setPreFillText[$networkType][$networkId], (int) $this->setPreFillTextLimit[$networkType][$networkId]) : $this->content;
                    if ($this->allowHashTag) {
                        $postData['content'] .= $this->getHashTagsString();
                    }
                    $postData['custom_title'] = strip_tags($this->title);
                } else {
                    return false;
                }
            }

            if ($networkId == 7) {
                if ($this->imageUrl !== false) {
                    $postData['custom_title'] = strip_tags($this->title);
                } else {
                    return false;
                }
            }
            if ($networkId == 8) {
                $postData['content'] = (isset($this->setPreFillText[$networkType][$networkId])) ? B2S_Util::getExcerpt($this->content, (int) $this->setPreFillText[$networkType][$networkId], (int) $this->setPreFillTextLimit[$networkType][$networkId]) : $this->content;
                $postData['custom_title'] = strip_tags($this->title);
            }
            if ($networkId == 9 || $networkId == 16) {
                $postData['custom_title'] = $this->title;
                $postData['content'] = (isset($this->setPreFillText[$networkType][$networkId])) ? B2S_Util::getExcerpt($this->content, (int) $this->setPreFillText[$networkType][$networkId], (int) $this->setPreFillTextLimit[$networkType][$networkId]) : $this->content;
                if ($this->allowHashTag) {
                    if (is_array($this->keywords) && !empty($this->keywords)) {
                        foreach ($this->keywords as $tag) {
                            $postData['tags'][] = str_replace(" ", "", $tag->name);
                        }
                    }
                }
            }

            if ($networkId == 10 || $networkId == 17 || $networkId == 18) {
                $postData['content'] = (isset($this->setPreFillText[$networkType][$networkId])) ? B2S_Util::getExcerpt($this->content, (int) $this->setPreFillText[$networkType][$networkId], (isset($this->setPreFillTextLimit[$networkType][$networkId]) ? (int) $this->setPreFillTextLimit[$networkType][$networkId] : false)) : $this->content;
                if ($this->allowHashTag) {
                    $postData['content'] .= $this->getHashTagsString();
                }
            }

            if ($networkId == 11 || $networkId == 14) {
                $postData['custom_title'] = strip_tags($this->title);
                $postData['content'] = $this->contentHtml;
            }

            if ($networkId == 11) {
                if ($this->allowHashTag) {
                    if (is_array($this->keywords) && !empty($this->keywords)) {
                        foreach ($this->keywords as $tag) {
                            $postData['tags'][] = str_replace(" ", "", $tag->name);
                        }
                    }
                }
            }

            if ($networkId == 13 || $networkId == 15) {
                $postData['content'] = strip_tags($this->title);
            }
            
            if ($networkId == 21) {
                if ($this->imageUrl !== false) {
                    $postData['content'] = (isset($this->setPreFillText[$networkType][$networkId])) ? B2S_Util::getExcerpt($this->content, (int) $this->setPreFillText[$networkType][$networkId], (isset($this->setPreFillTextLimit[$networkType][$networkId]) ? (int) $this->setPreFillTextLimit[$networkType][$networkId] : false)) : $this->content;
                    $postData['custom_title'] = strip_tags($this->title);
                    if ($this->allowHashTag) {
                        if (is_array($this->keywords) && !empty($this->keywords)) {
                            foreach ($this->keywords as $tag) {
                                $postData['tags'][] = str_replace(" ", "", $tag->name);
                            }
                        }
                    }
                } else {
                    return false;
                }
            }
            
            return $postData;
        }
        return false;
    }

    private function getHashTagsString($add = "\n\n", $limit = -1) {// limit = -1 => no limit
        if($limit != 0){
            $hashTags = '';
            if (is_array($this->keywords) && !empty($this->keywords)) {
                foreach ($this->keywords as $tag) {
                    $hashTags .= ' #' . str_replace(array(" ", "-", '"', "'", "!", "?", ",", ".", ";", ":"), "", $tag->name);
                }
            }
            if($limit > 0) {
                $pos = 0;
                $temp_str = $hashTags;
                for($i = 0; $i <= $limit; $i++){
                    $pos = strpos($temp_str, '#');
                    $temp_str = substr($temp_str, $pos+1);
                }
                if($pos !== false){
                    $pos = strpos($hashTags, $temp_str);
                    $hashTags = substr($hashTags, 0, $pos-1);
                }
            }
            return (!empty($hashTags) ? (!empty($add) ? $add . $hashTags : $hashTags) : '');
        } else {
            return '';
        }
    }

    public function saveShareData($shareData = array(), $network_id = 0, $network_type = 0, $network_auth_id = 0, $shareApprove = 0, $network_display_name = '', $sched_date = '0000-00-00 00:00:00', $sched_date_utc = '0000-00-00 00:00:00') {

        if(isset($shareData['image_url']) && !empty($shareData['image_url'])){
            $image_data = wp_check_filetype($shareData['image_url']);
            if(isset($image_data['ext']) && $image_data['ext'] == 'gif' && in_array($network_id, unserialize(B2S_PLUGIN_NETWORK_NOT_ALLOW_GIF))){
                $shareData['image_url'] = '';
            }
        }

        global $wpdb;
        $networkDetailsId = 0;
        $schedDetailsId = 0;
        $networkDetailsIdSelect = $wpdb->get_col($wpdb->prepare("SELECT postNetworkDetails.id FROM {$wpdb->prefix}b2s_posts_network_details AS postNetworkDetails WHERE postNetworkDetails.network_auth_id = %s", $network_auth_id));
        if (isset($networkDetailsIdSelect[0])) {
            $networkDetailsId = (int) $networkDetailsIdSelect[0];
        } else {
            $wpdb->insert($wpdb->prefix.'b2s_posts_network_details', array(
                'network_id' => (int) $network_id,
                'network_type' => (int) $network_type,
                'network_auth_id' => (int) $network_auth_id,
                'network_display_name' => $network_display_name), array('%d', '%d', '%d', '%s'));
            $networkDetailsId = $wpdb->insert_id;
        }

        if ($networkDetailsId > 0) {
            //DeprecatedNetwork-8 31 march
            if ($network_id == 8 && $sched_date_utc >= '2019-03-30 23:59:59') {
                $wpdb->insert($wpdb->prefix.'b2s_posts', array(
                    'post_id' => $this->postId,
                    'blog_user_id' => $this->blogUserId,
                    'user_timezone' => $this->userTimezone,
                    'publish_date' => date('Y-m-d H:i:s', strtotime(B2S_Util::getUTCForDate(gmdate('Y-m-d H:i:s'), $this->userTimezone * (-1)))),
                    'publish_error_code' => 'DEPRECATED_NETWORK_8',
                    'network_details_id' => $networkDetailsId), array('%d', '%d', '%s', '%s', '%s', '%d'));
            } else {
                $wpdb->insert($wpdb->prefix.'b2s_posts_sched_details', array('sched_data' => serialize($shareData), 'image_url' => (isset($shareData['image_url']) ? $shareData['image_url'] : '')), array('%s', '%s'));
                $schedDetailsId = $wpdb->insert_id;
                $wpdb->insert($wpdb->prefix.'b2s_posts', array(
                    'post_id' => $this->postId,
                    'blog_user_id' => $this->blogUserId,
                    'user_timezone' => $this->userTimezone,
                    'publish_date' => "0000-00-00 00:00:00",
                    'sched_details_id' => $schedDetailsId,
                    'sched_type' => 5, // Re-Posting
                    'sched_date' => $sched_date,
                    'sched_date_utc' => $sched_date_utc,
                    'network_details_id' => $networkDetailsId,
                    'post_for_approve' => (int) $shareApprove,
                    'hook_action' => (((int) $shareApprove == 0) ? 1 : 0)), array('%d', '%d', '%s', '%s', '%d', '%d', '%s', '%s', '%d', '%d', '%d'));
                B2S_Rating::trigger();
            }
        }
    }
    
    public function deletePostsByBlogPost($blogPostId = 0) {
        global $wpdb;
        $getSchedData = $wpdb->prepare("SELECT id as b2sPostId FROM {$wpdb->prefix}b2s_posts WHERE post_id = %d AND b.sched_type = %d AND b.publish_date = %s AND b.hide = %d", (int) $blogPostId, 5, "0000-00-00 00:00:00", 0);
        $schedDataResult = $wpdb->get_results($getSchedData);
        $delete_scheds = array();
        foreach ($schedDataResult as $k => $value) {
            array_push($delete_scheds, $value->b2sPostId);
        }
        if (!empty($delete_scheds)) {
            require_once (B2S_PLUGIN_DIR . '/includes/B2S/Post/Tools.php');
            B2S_Post_Tools::deleteUserSchedPost($delete_scheds);
            B2S_Heartbeat::getInstance()->deleteSchedPost();
        }
    }
    
}
