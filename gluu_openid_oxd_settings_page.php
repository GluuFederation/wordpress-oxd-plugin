<?php

function gluu_is_oxd_registered() {
    $oxd_id 	= get_option('gluu_oxd_id');
    if(! $oxd_id ) {
        return 0;
    } else {
        return 1;
    }
}
function gluu_oxd_register_openid() {
    wp_enqueue_script('jquery');
    wp_enqueue_media();
    wp_enqueue_script( 'oxd_scope_custom_script',plugins_url('includes/js/oxd_scope_custom_script.js', __FILE__), array('jquery'));
    $custom_nonce = wp_create_nonce('validating-nonce-value');
    if( isset( $_GET[ 'tab' ]) && $_GET[ 'tab' ] !== 'register' ) {
        $active_tab = $_GET[ 'tab' ];
    }
    else if( isset( $_GET[ 'tab' ]) && $_GET[ 'tab' ] !== 'register_edit' ) {
        $active_tab = $_GET[ 'tab' ];
    }else if(gluu_is_oxd_registered()) {
        $active_tab = 'register_edit';
    }else{
        $active_tab = 'register';
    }
    ?>
    <div id="tab" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
        <h2 class="nav-tab-wrapper">
            <a class="nav-tab <?php  if($active_tab == 'register' or $active_tab == 'register_edit')  echo 'nav-tab-active'; ?>" href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">General</a>
            <?php if ( !gluu_is_oxd_registered()) {?>
            <button class="nav-tab not_checked_button" disabled >OpenID Connect Configuration</button>
            <?php }else {?>
                <a class="nav-tab <?php echo $active_tab == 'login_config' ? 'nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'login_config'), $_SERVER['REQUEST_URI'] ); ?>">OpenID Connect Configuration</a>
            <?php }?>
            <a class="nav-tab " href="https://oxd.gluu.org/docs/plugin/wordpress/" target="_blank">Documentation</a>
        </h2>
    </div>
    <div id="oxd_openid_settings">
        <div class="oxd_container">
            <div id="oxd_openid_msgs"></div>
            <table style="width:100%;">
                <tr>
                    <td style="vertical-align:top;width:65%;">
                        <?php
                        if ( $active_tab == 'register') {
                            if ( !gluu_is_oxd_registered()) {
                                if(!empty($_SESSION['openid_error'])){
                                    gluu_oxd_openid_show_client_page($custom_nonce);
                                }else{
                                    gluu_oxd_openid_show_new_registration_page($custom_nonce);
                                }
                            }else{
                                gluu_oxd_openid_show_new_registration__restet_page($custom_nonce);
                            }
                        }else if($active_tab == 'login_config') {
                            gluu_oxd_openid_login_config_info($custom_nonce);
                        }else if($active_tab == 'register_edit') {
                            if ( !gluu_is_oxd_registered()) {
                                wp_redirect(add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ));
                            }
                            if(!empty($_SESSION['openid_error_edit'])){
                                gluu_oxd_openid_edit_client_page($custom_nonce);
                            }
                            elseif(!empty($_SESSION['openid_edit_success'])){
                                gluu_oxd_openid_show_new_registration__restet_page($custom_nonce);
                            }else if(!empty($_SESSION['openid_success_reg'])){
                                gluu_oxd_openid_show_new_registration__restet_page($custom_nonce);
                            }else if(empty($_GET['tab'])){
                                gluu_oxd_openid_show_new_registration__restet_page($custom_nonce);
                            }
                            else{
                                gluu_oxd_openid_edit_page($custom_nonce);
                            }
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php
}
function gluu_oxd_openid_show_client_page($custom_nonce) {
    update_option ( 'oxd_openid_new_registration', 'true' );
    global $current_user;
    get_currentuserinfo();
    $gluu_oxd_config 	= get_option('gluu_oxd_config');
    ?>
    <form name="f" method="post" action="" id="register-form">
        <input type="hidden" name="option" value="oxd_openid_connect_register_site_oxd" />
        <input type="hidden" name="custom_nonce" value="<?php echo $custom_nonce; ?>" />
        <div class="oxd_openid_table_layout">
            <?php if(!gluu_is_oxd_registered()) { ?>
                <div  class="mess_red">
                    Please enter the details of your OpenID Connect Provider.
                </div>
            <?php } ?>
            <br/>
            <div><h3>Register your site with an OpenID Connect Provider</h3></div>
            <hr>
            <div ><h4>The Gluu Server is a free open source OpenID Provider. For more info see  <a target="_blank" href="http://gluu.org">http://gluu.org</a>.</h4></div>
            <div >
                <h4> For instructions on oxd installation, please refer to <a href="https://oxd.gluu.org/docs" target="_blank">https://oxd.gluu.org/docs</a></h4>
            </div>
            <hr>
            <table class="oxd_openid_settings_table">
                <tr>
                    <td><b><font color="#FF0000">*</font>Automatically register new users:</b></td>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span><b><font color="#FF0000">*</font>Automatically register new users:</b></span></legend><label for="users_can_register">
                                <input name="users_can_register" type="checkbox" id="users_can_register" <?php if(get_option('users_can_register')){ echo "checked";} ?> value="1">
                                 </label>
                        </fieldset>
                        <br/>
                    </td>
                </tr>
                <tr>
                    <td><label for="default_role"><b><font color="#FF0000">*</font>New User Default Role:</b></label></td>
                    <td>
                        <select name="default_role" id="default_role"><?php wp_dropdown_roles( get_option('default_role') ); ?></select>
                        <br/><br/>
                    </td>
                </tr>
                <tr>
                    <td><b>URI of the OpenID Connect Provider:</b></td>
                    <td><input class="oxd_openid_table_textbox" type="url" name="gluu_server_url"  placeholder="Enter URI of the OpenID Connect Provider" value="<?php if(get_option('gluu_op_host')){ echo get_option('gluu_op_host');} ?>" /></td>
                </tr>
                <tr>
                    <td><label for="gluu_custom_url"><b>Custom URI after logout:</b></label></td>
                    <td><input class="oxd_openid_table_textbox"  type="url" name="gluu_custom_url"  placeholder="Enter custom URI after logout" value="<?php if(get_option('gluu_custom_url')){ echo get_option('gluu_custom_url');} ?>" /></td>
                </tr>
                <tr>
                    <td><b><font color="#FF0000">*</font>Redirect URL:</b></td>
                    <td><input class="oxd_openid_table_textbox" type="url" name="gluu_redirect_url" disabled required value="<?php echo get_option('gluu_redirect_url');?>" /></td>
                </tr>
                <tr>
                    <td><b><font color="#FF0000">*</font>Client ID:</b></td>
                    <td><input class="oxd_openid_table_textbox" type="text" name="gluu_client_id" required placeholder="Enter OpenID Connect Provider client ID" value="" /></td>
                </tr>
                <tr>
                    <td><b><font color="#FF0000">*</font>Client Secret:</b></td>
                    <td><input class="oxd_openid_table_textbox" type="text" name="gluu_client_secret" required placeholder="Enter OpenID Connect Provider client secret" value="" /></td>
                </tr>
                <tr>
                    <td><b><font color="#FF0000">*</font>oxd port:</b></td>
                    <td>
                        <br/>
                        <input class="oxd_openid_table_textbox" required type="number" name="oxd_host_port" value="<?php if($gluu_oxd_config['oxd_host_port']){ echo $gluu_oxd_config['oxd_host_port'];}else{ echo 8099;} ?>" placeholder="Please enter free port (for example 8099). (Min. number 0, Max. number 65535)" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" name="submit" value="Next" style="float: left; margin-right: 15px " class="button button-primary button-large" />
                        <input type="button" onclick="delete_register('cancel','<?php echo $custom_nonce;?>')" name="cancel" value="cancel" style="float: left; " class="button button-primary button-large" />
                    </td>

                </tr>
            </table>
        </div>
    </form>
    <?php
}
function gluu_oxd_openid_show_new_registration_page($custom_nonce) {
    update_option ( 'oxd_openid_new_registration', 'true' );
    global $current_user;
    get_currentuserinfo();
    $gluu_oxd_config 	= get_option('gluu_oxd_config');
    ?>
    <form name="f" method="post" action="" id="register-form">
        <input type="hidden" name="option" value="oxd_openid_connect_register_site_oxd" />
        <input type="hidden" name="custom_nonce" value="<?php echo $custom_nonce; ?>" />
        <div class="oxd_openid_table_layout">
            <?php if(!gluu_is_oxd_registered()) { ?>
                <div class="mess_red">
                    Please enter the details of your OpenID Connect Provider.
                </div>
            <?php } ?>
            <br/>
            <div><h3>Register your site with an OpenID Connect Provider</h3></div>
            <hr>
            <div ><h4>The Gluu Server is a free open source OpenID Provider. For more info see <a target="_blank" href="http://gluu.org">http://gluu.org</a>.</h4>
            </div>
            <div >
                <h4> For instructions on oxd installation, please refer to <a href="https://oxd.gluu.org/docs" target="_blank">https://oxd.gluu.org/docs</a></h4>
            </div>
            <hr>
            <table class="oxd_openid_settings_table">
                <tr>
                    <td><b><font color="#FF0000">*</font>Automatically register new users:</b></td>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span><b><font color="#FF0000">*</font>Automatically register new users:</b></span></legend><label for="users_can_register">
                                <input name="users_can_register" type="checkbox" id="users_can_register" <?php if(get_option('users_can_register')){ echo "checked";} ?> value="1">
                                 </label>
                        </fieldset>
                        <br/>
                    </td>
                </tr>
                <tr>
                    <td><label for="default_role"><b><font color="#FF0000">*</font>New User Default Role:</b></label></td>
                    <td>
                        <select name="default_role" id="default_role"><?php wp_dropdown_roles( get_option('default_role') ); ?></select>
                        <br/><br/>
                    </td>
                </tr>
                <tr>
                    <td><b>URI of the OpenID Connect Provider:</b></td>
                    <td><input class="oxd_openid_table_textbox" type="url" name="gluu_server_url" placeholder="Enter URI of the OpenID Connect Provider" value="<?php if(get_option('gluu_op_host')){ echo get_option('gluu_op_host');} ?>" /></td>
                </tr>
                <tr>
                    <td><label for="gluu_custom_url"><b>Custom URI after logout:</b></label></td>
                    <td><input class="oxd_openid_table_textbox" type="url" name="gluu_custom_url"  placeholder="Enter custom URI after logout" value="<?php if(get_option('gluu_custom_url')){ echo get_option('gluu_custom_url');} ?>" /></td>
                </tr>
                <tr>
                    <td><b><font color="#FF0000">*</font>oxd port:</b></td>
                    <td>
                        <br/>
                        <input class="oxd_openid_table_textbox" required type="number" name="oxd_host_port" value="<?php if($gluu_oxd_config['oxd_host_port']){ echo $gluu_oxd_config['oxd_host_port'];}else{ echo 8099;} ?>" placeholder="Please enter free port (for example 8099). (Min. number 0, Max. number 65535)" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" name="submit" value="Register" style="float: left; margin-right: 15px " class="button button-primary button-large" />
                        <?php if(get_option('gluu_op_host')){?>
                            <input type="button" onclick="delete_register('cancel','<?php echo $custom_nonce;?>')" name="cancel" value="cancel" style="float: left; " class="button button-primary button-large" />
                        <?php }?>
                    </td>

                </tr>
            </table>
        </div>
    </form>
    <?php
}
function gluu_oxd_openid_show_new_registration__restet_page($custom_nonce) {
    update_option ( 'oxd_openid_new_registration', 'true' );
    global $current_user;
    $gluu_oxd_config 	= get_option('gluu_oxd_config');
    get_currentuserinfo();
    ?>
    <form name="f" method="post" action="" id="register-form">
        <input type="hidden" name="option" value="oxd_openid_reset_config" />
        <input type="hidden" name="custom_nonce" value="<?php echo $custom_nonce; ?>" />
        <div class="oxd_openid_table_layout">
            <fieldset style="border: 2px solid #53cc6b; ">
                <legend><div class="about">
                        <img style=" height: 45px;" src="<?php echo plugins_url('includes/images/gl.png', __FILE__)?>" />
                    </div></legend>
                <table style="margin-left: 30px" class="form-table"
                    <tr>
                        <td><b>Automatically register new users:</b></td>
                        <td>
                            <fieldset><legend class="screen-reader-text"><span>Automatically register new users:</b></span></legend><label for="users_can_register">
                                    <input name="users_can_register" type="checkbox" disabled id="users_can_register" <?php if(get_option('users_can_register')){ echo "checked";} ?> value="1">
                                     </label>
                            </fieldset>
                            <br/>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="default_role"><b>New User Default Role:</b></label></td>
                        <td>
                            <select disabled name="default_role" id="default_role"><?php wp_dropdown_roles( get_option('default_role') ); ?></select>
                            <br/><br/>
                        </td>
                    </tr>
                    <tr>
                        <td><b>URI of the OpenID Connect Provider:</b></td>
                        <td><input class="oxd_openid_table_textbox" disabled type="url" name="gluu_server_url" placeholder="Enter URI of the OpenID Connect Provider" value="<?php if(get_option('gluu_op_host')){ echo get_option('gluu_op_host');} ?>" /></td>
                    </tr>
                    <tr>
                        <td><label for="gluu_custom_url"><b>Custom URI after logout:</b></label></td>
                        <td><input class="oxd_openid_table_textbox" disabled type="url" name="gluu_custom_url"  placeholder="Enter custom URI after logout" value="<?php if(get_option('gluu_custom_url')){ echo get_option('gluu_custom_url');} ?>" /></td>
                    </tr>
                    <?php
                    if(!empty($gluu_oxd_config['gluu_client_id']) and !empty($gluu_oxd_config['gluu_client_secret'])){
                        ?>
                        <tr>
                            <td><b>Client ID:</b></td>
                            <td><input class="oxd_openid_table_textbox" disabled type="text" name="gluu_client_id"  placeholder="Enter OpenID Connect Provider client ID" value="<?php if($gluu_oxd_config['gluu_client_id']){ echo $gluu_oxd_config['gluu_client_id'];} ?>" /></td>
                        </tr>
                        <tr>
                            <td><b>Client Secret:</b></td>
                            <td><input class="oxd_openid_table_textbox" disabled type="text" name="gluu_client_secret" required placeholder="Enter OpenID Connect Provider client secret" value="<?php if($gluu_oxd_config['gluu_client_secret']){ echo $gluu_oxd_config['gluu_client_secret'];} ?>" /></td>
                        </tr>
                        <?php
                    }
                    ?>

                    <tr>
                        <td><b>oxd port:</b></td>
                        <td>
                            <br/>
                            <input class="oxd_openid_table_textbox" disabled required type="number" name="oxd_host_port" value="<?php if($gluu_oxd_config['oxd_host_port']){ echo $gluu_oxd_config['oxd_host_port'];}else{ echo 8099;} ?>" placeholder="Please enter free port (for example 8099). (Min. number 0, Max. number 65535)" />
                        </td>
                    </tr>
                    <tr>
                        <td><b>oxd id:</b></td>
                        <td>
                            <input <?php echo 'disabled'?> type="text" name="oxd_id" value="<?php echo get_option('gluu_oxd_id'); ?>" size="100%" /><br/>
                        </td>
                    </tr>
                    <tr>
                        <td><a class="button button-primary button-large" style="float: right" href="<?php echo add_query_arg( array('tab' => 'register_edit'), $_SERVER['REQUEST_URI'] ); ?>">Edit</a></td>
                        <td><input type="submit" name="submit" style="float: left" value="Delete" <?php if(!gluu_is_oxd_registered()) echo 'disabled'?> class="button button-primary button-large" /></td>
                    </tr>
                </table>

            </fieldset>
        </div>
    </form>
    <?php
}
function gluu_oxd_openid_edit_page($custom_nonce) {
    update_option ( 'oxd_openid_new_registration', 'true' );
    global $current_user;
    $gluu_oxd_config 	= get_option('gluu_oxd_config');
    get_currentuserinfo();
    ?>
    <form name="f" method="post" action="" id="register-form">
        <input type="hidden" name="option" value="oxd_openid_edit_config" />
        <input type="hidden" name="custom_nonce" value="<?php echo $custom_nonce; ?>" />
        <div class="oxd_openid_table_layout">
            <fieldset style="border: 2px solid #53cc6b;">
                <legend><div class="about">
                        <img style=" height: 45px" src="<?php echo plugins_url('includes/images/gl.png', __FILE__)?>" />
                    </div></legend>
                <table style="margin-left: 30px" class="form-table"
                <tr>
                    <td><b>Automatically register new users:</b></td>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span>Automatically register new users:</b></span></legend><label for="users_can_register">
                                <input name="users_can_register" type="checkbox"  id="users_can_register" <?php if(get_option('users_can_register')){ echo "checked";} ?> value="1">
                                 </label>
                        </fieldset>
                        <br/>
                    </td>
                </tr>
                <tr>
                    <td><label for="default_role"><b>New User Default Role:</b></label></td>
                    <td>
                        <select  name="default_role" id="default_role"><?php wp_dropdown_roles( get_option('default_role') ); ?></select>
                        <br/><br/>
                    </td>
                </tr>
                <tr>
                    <td><b>URI of the OpenID Connect Provider:</b></td>
                    <td><input class="oxd_openid_table_textbox" disabled type="url" name="gluu_server_url"  placeholder="Enter URI of the OpenID Connect Provider" value="<?php if(get_option('gluu_op_host')){ echo get_option('gluu_op_host');} ?>" /></td>
                </tr>
                <tr>
                    <td><label for="gluu_custom_url"><b>Custom URI after logout:</b></label></td>
                    <td><input class="oxd_openid_table_textbox"  type="url" name="gluu_custom_url"  placeholder="Enter custom URI after logout" value="<?php if(get_option('gluu_custom_url')){ echo get_option('gluu_custom_url');} ?>" /></td>
                </tr>
                <?php
                if(!empty($gluu_oxd_config['gluu_client_id']) and !empty($gluu_oxd_config['gluu_client_secret'])){
                    ?>
                    <tr>
                        <td><b>Client ID:</b></td>
                        <td><input class="oxd_openid_table_textbox"  type="text" name="gluu_client_id"  placeholder="Enter OpenID Connect Provider client ID" value="<?php if($gluu_oxd_config['gluu_client_id']){ echo $gluu_oxd_config['gluu_client_id'];} ?>" /></td>
                    </tr>
                    <tr>
                        <td><b>Client Secret:</b></td>
                        <td><input class="oxd_openid_table_textbox"  type="text" name="gluu_client_secret"  placeholder="Enter OpenID Connect Provider client secret" value="<?php if($gluu_oxd_config['gluu_client_secret']){ echo $gluu_oxd_config['gluu_client_secret'];} ?>" /></td>
                    </tr>
                    <?php
                }
                ?>

                <tr>
                    <td><b>oxd port:</b></td>
                    <td>
                        <br/>
                        <input class="oxd_openid_table_textbox"  required type="number" name="oxd_host_port" value="<?php if($gluu_oxd_config['oxd_host_port']){ echo $gluu_oxd_config['oxd_host_port'];}else{ echo 8099;} ?>" placeholder="Please enter free port (for example 8099). (Min. number 0, Max. number 65535)" />
                    </td>
                </tr>
                <tr>
                    <td><b>oxd id:</b></td>
                    <td>
                        <input <?php echo 'disabled'?> type="text" name="oxd_id" value="<?php echo get_option('gluu_oxd_id'); ?>" size="100%" /><br/>
                    </td>
                </tr>
                <tr>
                    <td> <input type="submit" name="submit" value="Save" style="float: right" class="button button-primary button-large" />
                    </td>
                    <td><a class="button button-primary button-large"  href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">Cancel</a></td>

                </tr>
                </table>

            </fieldset>
        </div>
    </form>
    <?php
}
function gluu_oxd_openid_edit_client_page($custom_nonce) {
    update_option ( 'oxd_openid_new_registration', 'true' );
    global $current_user;
    $gluu_oxd_config 	= get_option('gluu_oxd_config');
    get_currentuserinfo();
    ?>
    <form name="f" method="post" action="" id="register-form">
        <input type="hidden" name="option" value="oxd_openid_edit_config" />
        <input type="hidden" name="custom_nonce" value="<?php echo $custom_nonce; ?>" />
        <div class="oxd_openid_table_layout">
            <fieldset style="border: 2px solid #53cc6b;">
                <legend><div class="about">
                        <img style=" height: 45px" src="<?php echo plugins_url('includes/images/gl.png', __FILE__)?>" />
                    </div></legend>
                <table style="margin-left: 30px" class="form-table"
                <tr>
                    <td><b>Automatically register new users:</b></td>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span>Automatically register new users:</b></span></legend><label for="users_can_register">
                                <input name="users_can_register" type="checkbox"  id="users_can_register" <?php if(get_option('users_can_register')){ echo "checked";} ?> value="1">
                                 </label>
                        </fieldset>
                        <br/>
                    </td>
                </tr>
                <tr>
                    <td><label for="default_role"><b>New User Default Role:</b></label></td>
                    <td>
                        <select  name="default_role" id="default_role"><?php wp_dropdown_roles( get_option('default_role') ); ?></select>
                        <br/><br/>
                    </td>
                </tr>
                <tr>
                    <td><b>URI of the OpenID Connect Provider:</b></td>
                    <td><input class="oxd_openid_table_textbox" disabled type="url" name="gluu_server_url"  placeholder="Enter URI of the OpenID Connect Provider" value="<?php if(get_option('gluu_op_host')){ echo get_option('gluu_op_host');} ?>" /></td>
                </tr>
                <tr>
                    <td><label for="gluu_custom_url"><b>Custom URI after logout:</b></label></td>
                    <td><input class="oxd_openid_table_textbox"  type="url" name="gluu_custom_url"  placeholder="Enter custom URI after logout" value="<?php if(get_option('gluu_custom_url')){ echo get_option('gluu_custom_url');} ?>" /></td>
                </tr>
                    <tr>
                        <td><b>Client ID:</b></td>
                        <td><input class="oxd_openid_table_textbox"  type="text" name="gluu_client_id"  placeholder="Enter OpenID Connect Provider client ID" value="<?php if($gluu_oxd_config['gluu_client_id']){ echo $gluu_oxd_config['gluu_client_id'];} ?>" /></td>
                    </tr>
                    <tr>
                        <td><b>Client Secret:</b></td>
                        <td><input class="oxd_openid_table_textbox"  type="text" name="gluu_client_secret" placeholder="Enter OpenID Connect Provider client secret" value="<?php if($gluu_oxd_config['gluu_client_secret']){ echo $gluu_oxd_config['gluu_client_secret'];} ?>" /></td>
                    </tr>

                <tr>
                    <td><b>oxd port:</b></td>
                    <td>
                        <br/>
                        <input class="oxd_openid_table_textbox"  required type="number" name="oxd_host_port" value="<?php if($gluu_oxd_config['oxd_host_port']){ echo $gluu_oxd_config['oxd_host_port'];}else{ echo 8099;} ?>" placeholder="Please enter free port (for example 8099). (Min. number 0, Max. number 65535)" />
                    </td>
                </tr>
                <tr>
                    <td><b>oxd id:</b></td>
                    <td>
                        <input <?php echo 'disabled'?> type="text" name="oxd_id" value="<?php echo get_option('gluu_oxd_id'); ?>" size="100%" /><br/>
                    </td>
                </tr>
                <tr>
                    <td> <input type="submit" style="float: right" name="submit" value="Save" class="button button-primary button-large" />
                    </td>
                    <td><a class="button button-primary button-large"  href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">Cancel</a></td>
                </tr>
                </table>

            </fieldset>
        </div>
    </form>
    <?php
}

function gluu_oxd_openid_login_config_info($custom_nonce){

    ?>
    <div class="oxd_openid_table_layout">
        <?php
        $options = get_option('gluu_oxd_config');
        if(!gluu_is_oxd_registered()) {
            ?>
            <div class="mess_red">
                Please enter the details of your OpenID Connect Provider.
            </div>
        <?php } ?>
        <div>
            <form action="" method="post">
                <input type="hidden" name="option" value="oxd_openid_config_info_hidden" />
                <input type="hidden" name="custom_nonce" value="<?php echo $custom_nonce; ?>" />
                <br/>
                <fieldset style="border: 2px solid #53cc6b;">
                    <legend><div class="about">
                            <img style=" height: 45px" src="<?php echo plugins_url('includes/images/gl.png', __FILE__)?>" />
                        </div></legend>
                    <table style="margin-left: 30px" class="form-table">
                        <tbody>
                        <tr>
                            <th scope="col" >
                                Requested scopes
                            </th>
                            <?php $get_scopes = get_option('gluu_oxd_openid_scops');
                            ?>
                            <td>
                                <div >
                                    <div>
                                        <label  for="openid">
                                            <input checked type="checkbox" name=""  id="openid" value="openid"  disabled />
                                            <input type="hidden"  name="scope[]"  value="openid" />openid
                                        </label><br/>
                                        <label  for="profile">
                                            <input checked type="checkbox" name=""  id="profile" value="profile"  disabled />
                                            <input type="hidden"  name="scope[]"  value="profile" />profile
                                        </label><br/>
                                        <label  for="email">
                                            <input checked type="checkbox" name=""  id="email" value="email"  disabled />
                                            <input type="hidden"  name="scope[]"  value="email" />email
                                        </label><br/>
                                        <?php foreach($get_scopes as $scop) :?>
                                            <?php if ($scop == 'openid' or $scop == 'email' or $scop == 'profile'){?>
                                            <?php } else{?>
                                                <label  for="<?php echo $scop;?>">
                                                    <input <?php if($options && in_array($scop, $options['scope'])){ echo "checked";} ?> type="checkbox" name="scope[]"  id="<?php echo $scop;?>" value="<?php echo $scop;?>" <?php if (!gluu_is_oxd_registered() || $scop=='openid') echo ' disabled '; ?> />
                                                    <?php echo $scop;?></label><br/>
                                            <?php }
                                        endforeach;?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr >
                            <th scope="row">
                                Add scopes
                            </th>
                            <td>
                                <div >
                                    <div id="p_scents">
                                        <input <?php if(!gluu_is_oxd_registered()) echo 'disabled'?> type="button" id="add_new_scope" value="Add scope">
                                        <p>
                                            <input <?php if(!gluu_is_oxd_registered()) echo 'disabled'?> type="text" name="new_scope[]" placeholder="Input scope name" />
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td>
                                <div style="">
                                    <button type="button" style="cursor: pointer; text-align: center;" id="show_scope_table">Delete scopes</button>
                                </div>
                                <table id="custom_scope_table" class="form-table" style="width:95%;display: none; text-align: center">
                                    <tr>
                                        <th> <h3>N</h3> </th>
                                        <th><h3>Scope name</h3></th>
                                        <th><h3>Delete</h3></th>
                                    </tr>
                                    <tr>
                                        <th>1</th>
                                        <th>openid</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th>2</th>
                                        <th>profile</th>
                                        <th><h3></h3></th>
                                    </tr>
                                    <tr>
                                        <th>3</th>
                                        <th>email</th>
                                        <th></th>
                                    </tr>
                                    <?php
                                    $n = 3;
                                    foreach($get_scopes as $scop) :?>
                                        <?php if ($scop == 'openid' or $scop == 'email' or $scop == 'profile'){?>
                                        <?php } else{
                                            $n++;
                                            ?>
                                            <tr>
                                                <td><?php echo $n;?></td>
                                                <td><?php echo $scop;?></td>
                                                <td>
                                                    <?php if($scop!='openid'){?>
                                                        <input type="button" onclick="delete_scopes('<?php echo $scop;?>','<?php echo $custom_nonce;?>')" class="button button-primary button-large" <?php if(!gluu_is_oxd_registered() || $scop=='openid') echo 'disabled'?> value="Delete" name="set_oxd_config" />
                                                    <?php }?>
                                                </td>
                                            </tr>
                                        <?php }


                                    endforeach;
                                    ?>
                                </table>
                                <br/>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" >
                                Manage Authentication
                            </th>
                            <?php $get_scopes = get_option('gluu_oxd_openid_custom_scripts');
                            ?>
                            <td>
                                <div style="margin-right: 30px">
                                        <p style="font-weight:bold "><input type="checkbox" name="send_user_check" id="send_user" value="1" <?php if(!gluu_is_oxd_registered()) echo 'disabled'?> <?php checked( get_option('gluu_send_user_check'));?> /><label for="send_user"> Send user straight to OpenID Provider for authentication</label>
                                            </p>
                                        <br/>

                                    <table>
                                        <tr >
                                            <label for="send_user_type"><p style="font-weight:bold ">Select acr</p></label>
                                            <span>To signal which type of authentication should be used, an OpenID Connect client may request a specific authentication context class reference value or "acr".</span>
                                                <br/>
                                            <?php
                                            $custom_scripts = get_option('gluu_oxd_openid_custom_scripts');
                                            if(!empty($custom_scripts)){
                                            ?>
                                                <select name="send_user_type" id="send_user_type" <?php if(!gluu_is_oxd_registered()) echo 'disabled'?>>
                                                    <option value="default">none</option>
                                                    <?php
                                                    foreach($custom_scripts as $custom_script){
                                                        if($custom_script != "default"){
                                                        ?>
                                                        <option <?php if(get_option('gluu_auth_type') == $custom_script) echo 'selected'; ?> value="<?php echo $custom_script;?>"><?php echo $custom_script;?></option>
                                                        <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            <?php } ?>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <p>Add acr</p>
                            </th>
                            <td>
                                <div >

                                    <input <?php if(!gluu_is_oxd_registered()) echo 'disabled'?> type="button" id="add_new_suctom_script"  value="Add acr"/>
                                    <div id="p_scents_script">
                                        <p>
                                            <input <?php if(!gluu_is_oxd_registered()) echo 'disabled'?> type="text" style="margin-right: 5px " name="acr_value[]" size="40" placeholder="ACR Value" />
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                        <?php
                        $custom_scripts = get_option('gluu_oxd_openid_custom_scripts');
                        if(!empty($custom_scripts)){
                            ?>
                            <table style="margin-left: 30px" class="form-table">
                                <tbody>
                                    <tr>
                                    <th></th>
                                    <td>
                                        <div style="">
                                            <button type="button"  style="cursor: pointer; text-align: center;" id="show_script_table">Delete ACR's</button>
                                        </div>
                                        <br/>
                                        <table id="custom_script_table" class="form-table" style="width:95%;display: none; text-align: center">
                                            <tr>
                                                <th> <h3>N</h3> </th>
                                                <th><h3>ACR Value</h3></th>
                                                <th><h3>Delete</h3></th>
                                            </tr>
                                            <?php
                                            $n = 0;
                                            foreach($custom_scripts as $custom_script){
                                                $n++;
                                                ?>
                                                <tr>
                                                    <td><?php echo $n;?></td>
                                                    <td><?php echo $custom_script;?></td>
                                                    <td><input type="button" onclick="delete_custom_script('<?php echo $custom_script;?>', '<?php echo $custom_nonce;?>')" class="button button-primary button-large" <?php if(!gluu_is_oxd_registered() or $custom_script == 'none') echo 'disabled'?> value="Delete" name="set_oxd_config" /></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>

                                        </table>
                                        <br/>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        <?php }?>
                </fieldset>
                <br/><br/><br/>
                <input style="width: 100px" type="submit" class="button button-primary button-large" <?php if(!gluu_is_oxd_registered()) echo 'disabled'?> value="Save" name="set_oxd_config" />
            </form>
        </div>
    </div>
    <?php
}




