<?php wp_nonce_field('b2s_security_nonce', 'b2s_security_nonce'); ?>
<div class="b2s-container">
    <div class="b2s-inbox">
        <div class="col-md-12 del-padding-left">
            <div class="col-md-9 del-padding-left del-padding-right">
                <!--Header|Start - Include-->
                <?php require_once (B2S_PLUGIN_DIR . 'views/b2s/html/header.php'); ?>
                <!--Header|End-->
                <div class="clearfix"></div>
                <!--Content|Start-->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <!--Menu|Start - Support-->
                        <ul class="nav nav-pills b2s-support-menu">
                            <li class="active">
                                <a href="#b2s-support-faq" class="b2s-support-faq" data-toggle="tab"><?php esc_html_e('Help & Community', 'blog2social') ?></a>
                            </li>
                            <li>
                                <a href="#b2s-support-check-system" class="b2s-support-check-sytem" data-toggle="tab"><?php esc_html_e('Troubleshooting-Tool', 'blog2social') ?> <span class="label label-success"><?php esc_html_e("NEW", "blog2social") ?></span></a>
                            </li>
                            <li>
                                <a href="#b2s-support-sharing-debugger" class="b2s-support-sharing-debugger" data-toggle="tab"><?php esc_html_e('Sharing-Debugger', 'blog2social') ?> <span class="label label-success"><?php esc_html_e("NEW", "blog2social") ?></span></a>
                            </li>
                            <li>
                                <a target="_blank" href="<?php echo B2S_Tools::getSupportLink('howto'); ?>"><?php esc_html_e('Step-by-Step-Guide', 'blog2social') ?></a>
                            </li>
                        </ul>
                        <hr class="b2s-support-line">
                        <!--Menu|End - Support-->
                        <div class="tab-content clearfix">
                            <div class="tab-pane active" id="b2s-support-faq">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <form action="<?php echo B2S_Tools::getSupportLink('faq_direct'); ?>" method="GET" target="_blank">
                                                <input type="hidden" name="action" value="search" />
                                                <h4 class="b2s-text-bold"><?php esc_html_e('How can we help?', 'blog2social') ?></h4>
                                                <div class="input-group">
                                                    <input type="text" name="search" placeholder="<?php esc_html_e('Enter your question or keyword here', 'blog2social') ?>" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="clearfix"></div>
                                        <br>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 text-center">
                                                    <a href="<?php echo B2S_Tools::getSupportLink('faq_installation'); ?>" class="b2s-color-black" target="_blank">
                                                        <i class="glyphicon glyphicon-play-circle b2s-support-icon"></i>
                                                        <br>
                                                        <?php esc_html_e('First Steps', 'blog2social') ?>
                                                    </a>
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    <a href="<?php echo B2S_Tools::getSupportLink('faq_sharing'); ?>" class="b2s-color-black" target="_blank">
                                                        <i class="glyphicon glyphicon-share b2s-support-icon"></i>
                                                        <br>
                                                        <?php esc_html_e('Sharing', 'blog2social') ?>
                                                    </a>
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    <a href="<?php echo B2S_Tools::getSupportLink('faq_network'); ?>" class="b2s-color-black" target="_blank">
                                                        <i class="glyphicon glyphicon-user b2s-support-icon"></i>
                                                        <br>
                                                        <?php esc_html_e('Social Networks', 'blog2social') ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-4 text-center">
                                                    <a href="<?php echo B2S_Tools::getSupportLink('faq_licence'); ?>" class="b2s-color-black" target="_blank">
                                                        <i class="glyphicon glyphicon-usd b2s-support-icon"></i>
                                                        <br>
                                                        <?php esc_html_e('Licensing', 'blog2social') ?>
                                                    </a>
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    <a href="<?php echo B2S_Tools::getSupportLink('faq_troubleshooting'); ?>" class="b2s-color-black" target="_blank">
                                                        <i class="glyphicon glyphicon-warning-sign b2s-support-icon"></i>
                                                        <br>
                                                        <?php esc_html_e('Troubleshooting for Error Messages', 'blog2social') ?>
                                                    </a>
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    <a href="<?php echo B2S_Tools::getSupportLink('faq_direct'); ?>" class="b2s-color-black" target="_blank">
                                                        <i class="glyphicon glyphicon-globe b2s-support-icon"></i>
                                                        <br>
                                                        <?php esc_html_e('Other topics', 'blog2social') ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 b2s-border-left">
                                            <h4 class="b2s-bold"><?php esc_html_e('Frequently asked questions', 'blog2social') ?></h4>
                                            <div class="b2s-faq-area">
                                                <div class="b2s-loading-area-faq" style="display:block">
                                                    <br>
                                                    <div class="b2s-loader-impulse b2s-loader-impulse-md"></div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="b2s-faq-content"></div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <br>
                                        <div class="col-md-12">
                                            <div class="b2s-community-container">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h4 class="b2s-bold"><?php esc_html_e('Welcome to the Blog2Social Community', 'blog2social') ?></h4>
                                                        <br>
                                                        <p class="b2s-gray-text"><?php esc_html_e('Find answers or join the conversation', 'blog2social') ?></p>
                                                        <br>
                                                        <a target="_blank" href="<?php echo B2S_Tools::getSupportLink('community') ?>" class="btn btn-success b2s-community-btn"><?php esc_html_e('Ask the Community', 'blog2social') ?></a>
                                                        <br><br>
                                                        <a target="_blank" href="<?php echo B2S_Tools::getSupportLink('community_lostpw') ?>"><?php esc_html_e('Forgot Username or Password?', 'blog2social') ?></a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="b2s-community-register-form">
                                                            <h5 class="b2s-bold"><?php esc_html_e('Create Account', 'blog2social') ?></h5>
                                                            <div class="alert alert-danger b2s-community-error" data-error-type="invalid_data" style="display: none;"><?php esc_html_e('Unknown error has occurred. Please try again.', 'blog2social') ?></div>
                                                            <div class="alert alert-danger b2s-community-error" data-error-type="invalid_password" style="display: none;"><?php esc_html_e('Enter at least 8 characters', 'blog2social') ?></div>
                                                            <div class="alert alert-danger b2s-community-error" data-error-type="invalid_email" style="display: none;"><?php esc_html_e('Invalid email address', 'blog2social') ?></div>
                                                            <div class="alert alert-danger b2s-community-error" data-error-type="exists_email" style="display: none;"><?php esc_html_e('Email address is taken.', 'blog2social') ?> <a target="_blank" href="<?php echo B2S_Tools::getSupportLink('community_lostpw') ?>"><?php esc_html_e('Forgot Password?', 'blog2social') ?></a></div>
                                                            <div class="alert alert-danger b2s-community-error" data-error-type="exists_username" style="display: none;"><?php esc_html_e('Username is taken.', 'blog2social') ?> <a target="_blank" href="<?php echo B2S_Tools::getSupportLink('community_lostpw') ?>"><?php esc_html_e('Forgot Password?', 'blog2social') ?></a></div>
                                                            <div class="input-group form-group">
                                                                <span class="input-group-addon btn-light"><i class="glyphicon glyphicon-user"></i></span>
                                                                <input type="text" id="b2s-community-username" name="fullname" placeholder="<?php esc_html_e('User name', 'blog2social') ?>" class="form-control">
                                                            </div>
                                                            <div class="input-group form-group">
                                                                <span class="input-group-addon btn-light"><i class="glyphicon glyphicon-envelope"></i></span>
                                                                <input type="text" id="b2s-community-email" autocomplete="off" name="email" placeholder="<?php esc_html_e('Email address', 'blog2social') ?>" class="form-control">
                                                            </div>
                                                            <div class="input-group form-group">
                                                                <span class="input-group-addon btn-light"><i class="glyphicon glyphicon-lock"></i></span>
                                                                <input type="password" id="b2s-community-password" autocomplete="new-password" name="password" placeholder="<?php esc_html_e('Create password', 'blog2social') ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="checkbox" id="b2s-community-terms"><label for="b2s-community-terms"><?php echo sprintf(__('I agree to the <a href="%s" target="_blank">community rules</a>', 'blog2social'), 'https://community.blog2social.com/help#community_rules') ?></label>
                                                            </div>
                                                            <input class="btn btn-primary width-100" id="b2s-community-register" type="button" value="<?php esc_html_e('Create Account', 'blog2social') ?>" disabled="disabeld">
                                                        </div>
                                                        <div class="b2s-community-register-loading" style="display:none;">
                                                            <div class="b2s-loader-impulse b2s-loader-impulse-md"></div>
                                                            <div class="clearfix"></div>
                                                            <div class="text-center b2s-loader-text"><?php esc_html_e("Loading...", "blog2social"); ?></div>
                                                        </div>
                                                        <div class="b2s-community-register-success text-center alert alert-success" style="display:none;">
                                                            <p class="b2s-bold"><?php esc_html_e('Yay :) You successfully registered for the Blog2Social Community!', 'blog2social') ?></p>
                                                            <br>
                                                            <a target="_blank" href="<?php echo B2S_Tools::getSupportLink('community') ?>" class="btn btn-primary"><?php esc_html_e('Go to the Blog2Social Community', 'blog2social') ?></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <br>
                                        <div class="col-md-12">
                                            <h4 class="b2s-text-bold"><?php esc_html_e('Sales Support', 'blog2social') ?></h4>
                                            <?php if (B2S_PLUGIN_USER_VERSION > 0) { ?>
                                            <div class="btn btn-default b2s-dashoard-btn-phone b2s-sales-support-box">
                                                <div class="b2s-support-phone-info">
                                                    <span class="pull-left b2s-bold"><?php esc_html_e('Call Us', 'blog2social') ?></span><span class="glyphicon glyphicon-earphone pull-right" aria-hidden="true"></span>
                                                    <br>
                                                    <div class="b2s-info-sm pull-left"><?php esc_html_e('M-F 9AM-5PM (CET)', 'blog2social') ?></div>
                                                </div>
                                                <div class="b2s-support-phone-number" style="display: none;">
                                                    <span class="pull-left b2s-bold b2s-support-phone-number-text"><?php esc_html_e('+49 2181 7569-277', 'blog2social') ?></span><span class="glyphicon glyphicon-earphone pull-right" aria-hidden="true"></span>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <a target="_blank" class="btn btn-default b2s-sales-support-box" href="<?php echo B2S_Tools::getSupportLink('faq'); ?>">
                                                <span class="pull-left b2s-bold"><?php esc_html_e('Send an email', 'blog2social') ?></span><span class="glyphicon glyphicon-envelope pull-right" aria-hidden="true"></span>
                                                <br>
                                                <div class="b2s-info-sm pull-left"><?php esc_html_e('Find the right license for your business', 'blog2social') ?></div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="b2s-support-check-system">
                                <div class="row b2s-loading-area width-100">
                                    <br>
                                    <div class="b2s-loader-impulse b2s-loader-impulse-md"></div>
                                    <div class="clearfix"></div>
                                    <div class="text-center b2s-loader-text"><?php esc_html_e("Loading...", "blog2social"); ?></div>
                                </div>
                                <div class="row width-100" id="b2s-support-no-admin" style="display: none;">
                                    <div class="text-center b2s-text-bold"><?php esc_html_e("You need admin rights to use the Troubleshooting-Tool. Please contact your administrator.", "blog2social"); ?></div>
                                </div>
                                <div id="b2s-main-debug" style="display: none;">
                                    <div class="clearfix"></div>
                                    <div class="row">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-3">
                                            <h4><?php esc_html_e("Needed", "blog2social"); ?></h4>
                                        </div>
                                        <div class="col-sm-2">
                                            <h4><?php esc_html_e("Current", "blog2social"); ?></h4>
                                        </div>
                                        <div class="col-sm-3">
                                            <button id="b2s-reload-debug-btn" class="btn btn-primary pull-right margin-right-15 b2s-margin-left-10" title="<?php esc_html_e("reload", "blog2social"); ?>"><i class="glyphicon glyphicon-refresh"></i></button>
                                            <a class="btn btn-primary pull-right b2s-support-link-not-active" title="<?php esc_html_e("Export as txt-file", "blog2social"); ?>" id="b2s-debug-export" download="blog2social-support.txt"><i class="glyphicon glyphicon-download-alt"></i></a>
                                        </div>
                                    </div>
                                    <br>
                                    <hr>
                                    <div id="b2s-debug-htmlData">

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="b2s-support-sharing-debugger">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3><?php esc_html_e("Enter a URL to see how your link preview will look on social media.", "blog2social"); ?></h3>
                                        <div class="b2s-sharing-debugger-result-area">
                                            <div class="clearfix"></div>
                                            <br>
                                            <div>
                                                <img class="b2s-feature-img-with-24" src="<?php echo plugins_url('/assets/images/portale/1_flat.png', B2S_PLUGIN_FILE); ?>" alt="Facebook">  <span class="b2s-text-bold"><?php esc_html_e("Facebook Open Graph Meta Tags", "blog2social") ?>
                                                    | <a class="btn-link" href="<?php echo B2S_Tools::getSupportLink("open_graph_tags"); ?>" target="_blank"><?php esc_html_e("Learn how to edit and adjust Open Graph tags.", "blog2social"); ?></a>
                                                </span>
                                            </div>
                                            <div class="input-group col-md-7 b2s-padding-top-8">
                                                <input type="text" name="b2s-debug-url" class="input-sm form-control" id="b2s-debug-url" value="<?php echo get_site_url(); ?>" data-network-id="1" placeholder="<?php esc_html_e("For example your Wordpress Home Page", "blog2social"); ?>">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary btn-sm b2s-btn-sharing-debugger" data-network-id="1" b2s-url-query="https://developers.facebook.com/tools/debug/sharing/?q="><?php esc_html_e("Debug & Preview", "blog2social") ?></button>
                                                </span>
                                            </div>
                                            <div class="clearfix"></div>
                                            <br>
                                            <div>
                                                <img class="b2s-feature-img-with-24" src="<?php echo plugins_url('/assets/images/portale/3_flat.png', B2S_PLUGIN_FILE); ?>" alt="Linkedin">  <span class="b2s-text-bold"><?php esc_html_e("LinkedIn Post Inspector", "blog2social") ?></span>
                                            </div>
                                            <div class="input-group col-md-7 b2s-padding-top-8">
                                                <input type="text" name="b2s-debug-url" class="input-sm form-control" id="b2s-debug-url" value="<?php echo get_site_url(); ?>" data-network-id="3" placeholder="<?php esc_html_e("For example your Wordpress Home Page", "blog2social"); ?>">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary btn-sm b2s-btn-sharing-debugger" data-network-id="3" b2s-url-query="https://www.linkedin.com/post-inspector/inspect/"><?php esc_html_e("Inspect Post", "blog2social") ?></button>
                                                </span>
                                            </div>
                                            <div class="clearfix"></div>
                                            <br>
                                            <div>
                                                <img class="b2s-feature-img-with-24" src="<?php echo plugins_url('/assets/images/portale/2_flat.png', B2S_PLUGIN_FILE); ?>" alt="Twitter">  <span class="b2s-text-bold"><?php esc_html_e("Twitter Card Validator", "blog2social") ?>
                                                    | <a class="btn-link" href="<?php echo B2S_Tools::getSupportLink("twitter_cards"); ?>" target="_blank"><?php esc_html_e("Learn how to edit and adjust Twitter Card tags.", "blog2social"); ?></a>
                                                </span>
                                            </div>
                                            <div class="b2s-padding-top-8">
                                                <button class="btn btn-primary btn-sm b2s-btn-sharing-debugger" data-network-id="2" b2s-url-query="https://cards-dev.twitter.com/validator?url="><?php esc_html_e("Validate directly on Twitter", "blog2social") ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <!--Content|End-->
            </div>
            <?php require_once (B2S_PLUGIN_DIR . 'views/b2s/html/sidebar.php'); ?>
        </div>
    </div>
</div>