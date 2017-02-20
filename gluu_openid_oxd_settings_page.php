<?php
	
	/**
	 * @copyright Copyright (c) 2017, Gluu Inc. (https://gluu.org/)
	 * @license	  MIT   License      : <http://opensource.org/licenses/MIT>
	 *
	 * @package	  OpenID Connect SSO Plugin by Gluu
	 * @category  Plugin for Wordpress
	 * @version   3.0.0
	 *
	 * @author    Gluu Inc.          : <https://gluu.org>
	 * @link      Oxd site           : <https://oxd.gluu.org>
	 * @link      Documentation      : <https://oxd.gluu.org/docs/plugin/wordpress/>
	 * @director  Mike Schwartz      : <mike@gluu.org>
	 * @support   Support email      : <support@gluu.org>
	 * @developer Volodya Karapetyan : <https://github.com/karapetyan88> <mr.karapetyan88@gmail.com>
	 *
	 *
	 * This content is released under the MIT License (MIT)
	 *
	 * Copyright (c) 2017, Gluu inc, USA, Austin
	 *
	 * Permission is hereby granted, free of charge, to any person obtaining a copy
	 * of this software and associated documentation files (the "Software"), to deal
	 * in the Software without restriction, including without limitation the rights
	 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	 * copies of the Software, and to permit persons to whom the Software is
	 * furnished to do so, subject to the following conditions:
	 *
	 * The above copyright notice and this permission notice shall be included in
	 * all copies or substantial portions of the Software.
	 *
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	 * THE SOFTWARE.
	 *
	 */
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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script>
            jQuery(document).ready(function(){
                jQuery('[data-toggle="tooltip"]').tooltip();
            });
        </script>
        <script type="application/javascript">
            jQuery(document ).ready(function() {
                <?php
                if(get_option('gluu_users_can_register') == 2){
                ?>
                jQuery("#p_role").children().prop('disabled',false);
                jQuery("#p_role *").prop('disabled',false);
                <?php
                }else if(get_option('gluu_users_can_register') == 3){
                ?>
                jQuery("#p_role").children().prop('disabled',true);
                jQuery("#p_role *").prop('disabled',true);
                jQuery("input[name='gluu_new_role[]']").each(function(){
                    var striped = jQuery('#p_role');
                    var value =  jQuery(this).attr("value");
                    jQuery('<p><input type="hidden" name="gluu_new_role[]"  value= "'+value+'"/></p>').appendTo(striped);
                });
                jQuery("#default_role").prop('disabled',true);
                <?php
                }else{
                ?>
                jQuery("#p_role").children().prop('disabled',true);
                jQuery("#p_role *").prop('disabled',true);
                jQuery("input[name='gluu_new_role[]']").each(function(){
                    var striped = jQuery('#p_role');
                    var value =  jQuery(this).attr("value");
                    jQuery('<p><input type="hidden" name="gluu_new_role[]"  value= "'+value+'"/></p>').appendTo(striped);
                });
                <?php
                }
                ?>
                jQuery('input:radio[name="gluu_users_can_register"]').change(
                    function(){
                        if(jQuery(this).is(':checked') && jQuery(this).val() == '2'){
                            jQuery("#p_role").children().prop('disabled',false);
                            jQuery("#p_role *").prop('disabled',false);
                            jQuery("input[type='hidden'][name='gluu_new_role[]']").remove();
                            jQuery("#default_role").prop('disabled',false);
                        }
                        else if(jQuery(this).is(':checked') && jQuery(this).val() == '3'){
                            jQuery("#p_role").children().prop('disabled',true);
                            jQuery("#p_role *").prop('disabled',true);
                            jQuery("input[type='hidden'][name='gluu_new_role[]']").remove();
                            jQuery("input[name='gluu_new_role[]']").each(function(){
                                var striped = jQuery('#p_role');
                                var value =  jQuery(this).attr("value");
                                jQuery('<p><input type="hidden" name="gluu_new_role[]"  value= "'+value+'"/></p>').appendTo(striped);
                            });
                            jQuery("#default_role").prop('disabled',true);
                        }else{
                            jQuery("#p_role").children().prop('disabled',true);
                            jQuery("#p_role *").prop('disabled',true);
                            jQuery("input[type='hidden'][name='gluu_new_role[]']").remove();
                            jQuery("input[name='gluu_new_role[]']").each(function(){
                                var striped = jQuery('#p_role');
                                var value =  jQuery(this).attr("value");
                                jQuery('<p><input type="hidden" name="gluu_new_role[]"  value= "'+value+'"/></p>').appendTo(striped);
                            });
                            jQuery("#default_role").prop('disabled',false);
                        }
                    });
                jQuery("input[name='scope[]']").change(
                    function(){
                        var form=$("#scpe_update");
                        if (jQuery(this).is(':checked')) {
                            jQuery.ajax({
                                url: window.location,
                                type: 'POST',
                                data:form.serialize(),
                                success: function(result){
                                    if(result){
                                        return false;
                                    }
                                }});
                        }else{
                            jQuery.ajax({
                                url: window.location,
                                type: 'POST',
                                data:form.serialize(),
                                success: function(result){
                                    if(result){
                                        return false;
                                    }
                                }});
                        }
                    });
    
            });
        </script>
        <style>
            .form-control{
                width: 70% !important;
            }
            .form-control td{
                padding: 10px 10px !important;
            }
        </style>
        <div id="tab" style="margin-left: -6px; margin-bottom: 7px">
            <h2 class="nav-tab-wrapper" style="border: none">
                <a class="nav-tab nav-tab1 <?php  if($active_tab == 'register' or $active_tab == 'register_edit')  echo 'nav-tab-active nav-tab-active1'; ?>" href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">General</a>
                <?php if ( !gluu_is_oxd_registered()) {?>
                    <button class="nav-tab nav-tab1 not_checked_button" disabled >OpenID Connect Configuration</button>
                <?php }else {?>
                    <a class="nav-tab nav-tab1 <?php echo $active_tab == 'login_config' ? 'nav-tab-active nav-tab-active1' : ''; ?>" href="<?php echo add_query_arg( array('tab' => 'login_config'), $_SERVER['REQUEST_URI'] ); ?>">OpenID Connect Configuration</a>
                <?php }?>
                <a class="nav-tab nav-tab1" href="https://oxd.gluu.org/docs/plugin/wordpress/" target="_blank">Documentation</a>
            </h2>
        </div>
        <div id="oxd_openid_settings">
            <div class="oxd_container">
                <div id="oxd_openid_msgs" style="margin-left: -3px;"></div>
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
            <div class=" oxd_openid_table_layout">
                <br/>
                <div style="padding-left: 30px;">Register your site with any standard OpenID Provider (OP). If you need an OpenID Provider you can deploy the <a target="_blank" href="https://gluu.org/docs/deployment/"> free open source Gluu Server.</a></div>
                <hr>
                <div style="padding-left: 30px;">This plugin relies on the oxd mediator service. For oxd deployment instructions and license information, please visit the <a target="_blank" href="http://gluu.org">oxd website.</a></div>
                <hr>
                <div style="margin-left: 20px">
                    <h3 style="padding-left: 10px;padding-bottom: 20px; border-bottom: 2px solid black; width: 60% "> Server Settings</h3>
                    <table class="form-table" >
                        <tr>
                            <td style="width: 300px;"><b>URI of the OpenID Provider:</b></td>
                            <td>
                                <input class="oxd_openid_table_textbox form-control" type="url" name="gluu_server_url"  placeholder="Enter URI of the OpenID Provider" value="<?php if(get_option('gluu_op_host')){ echo get_option('gluu_op_host');} ?>" /></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><label for="gluu_custom_url"><b>Custom URI after logout:</b></label></td>
                            <td>
                                <input class="oxd_openid_table_textbox form-control"  type="url" name="gluu_custom_url"  placeholder="Enter custom URI after logout" value="<?php if(get_option('gluu_custom_url')){ echo get_option('gluu_custom_url');} ?>" /></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><label for="wp_custom_login_url"><b>Site Login URI: <?php echo site_url(); ?>/</b></label></td>
                            <td>
                                <input class="oxd_openid_table_textbox form-control"  type="text" name="wp_custom_login_url"  placeholder="Enter your site login URI" value="<?php if(get_option('wp_custom_login_url')){ echo get_option('wp_custom_login_url');} ?>" /></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b><font color="#FF0000">*</font>Redirect URL:</b></td>
                            <td>
                                <input class="oxd_openid_table_textbox form-control" type="url" name="gluu_redirect_url" disabled required value="<?php echo get_option('gluu_redirect_url');?>" /></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b><font color="#FF0000">*</font>Client ID:</b></td>
                            <td>
                                <input  class="form-control oxd_openid_table_textbox" type="text" name="gluu_client_id" required placeholder="Enter OpenID Provider client ID" value="" /></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b><font color="#FF0000">*</font>Client Secret:</b></td>
                            <td>
                                <br/>
                                <input class="form-control oxd_openid_table_textbox" type="text" name="gluu_client_secret" required placeholder="Enter OpenID Provider client secret" value="" /></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b><font color="#FF0000">*</font>oxd port:</b></td>
                            <td>
                                <input class="oxd_openid_table_textbox form-control" required type="number" name="oxd_host_port" value="<?php if($gluu_oxd_config['oxd_host_port']){ echo $gluu_oxd_config['oxd_host_port'];}else{ echo 8099;} ?>" placeholder="Please enter free port (for example 8099). (Min. number 0, Max. number 65535)" />
                            </td>
                        </tr>
                        
                    </table>
                </div>
                <div style="margin-left: 20px">
                    <h3 style="padding-left: 10px;padding-bottom: 20px; border-bottom: 2px solid black; width: 60% ">Enrollment and Access Management
                        <a data-toggle="tooltip" class="tooltipLink" data-original-title="Register new users when they login at an external identity provider. If you disable automatic registration, new users will need to be manually created">
                            <span class="glyphicon glyphicon-info-sign"></span>
                        </a>
                    </h3>
                    <div style="padding-left: 10px">
                        <p><label ><input name="gluu_users_can_register" type="radio" id="gluu_users_can_register_1" <?php if(get_option('gluu_users_can_register')==1){ echo "checked";} ?> value="1" style="margin-right: 3px"> Automatically register any user with an account in the OpenID Provider</label></p>
                    </div>
                    <div style="padding-left: 10px">
                        <p><label ><input name="gluu_users_can_register" type="radio" id="gluu_users_can_register" <?php if(get_option('gluu_users_can_register')==2){ echo "checked";} ?> value="2" style="margin-right: 3px"> Only register and allow ongoing access to users with one or more of the following roles in the OpenID Provider</label></p>
                        <div style="margin-left: 20px;">
                            <div id="p_role" >
                                <?php $k=0;
                                if(!empty(get_option('gluu_new_role'))) {
                                    foreach (get_option('gluu_new_role') as $gluu_new_role) {
                                        if (!$k) {
                                            $k++;
                                            ?>
                                            <p>
                                                <input class="form-control " type="text" name="gluu_new_role[]"  required style="width: 20% !important; display: inline"
                                                       placeholder="Input role name"
                                                       value="<?php echo $gluu_new_role; ?>"/>
                                                <a href="#" class="btn btn-xs" id="add_new_role" ><span class="glyphicon glyphicon-plus"></span></a>
                                            </p>
                                            <?php
                                        } else {
                                            ?>
                                            <p>
                                                <input class="form-control " type="text" name="gluu_new_role[]"  required style="width: 20% !important; display: inline"
                                                       placeholder="Input role name"
                                                       value="<?php echo $gluu_new_role; ?>"/>
                                                <a href="#" class="btn btn-xs" id="add_new_role" ><span class="glyphicon glyphicon-plus"></span></a>
                                                <a href="#" class="btn btn-xs" id="remRole" ><span class="glyphicon glyphicon-minus"></span></a>
                                            </p>
                                        <?php }
                                    }
                                }
                                else{
                                    ?>
                                    <p>
                                        <input class="form-control"  type="text" name="gluu_new_role[]"  required style="width: 20% !important; display: inline" placeholder="Input role name" value=""/>
                                        <a href="#" class="btn btn-xs" id="add_new_role" ><span class="glyphicon glyphicon-plus"></span></a>
                                    </p>
                                    <?php
                                }?>
                            </div>
                        </div>
                    </div>
                    <div style="padding-left: 10px">
                        <p>
                            <label >
                                <input name="gluu_users_can_register" type="radio" id="gluu_users_can_register_2" <?php if(get_option('gluu_users_can_register')==3){ echo "checked";} ?> value="3" style="margin-right: 3px">
                                Disable automatic registration
                            </label>
                        </p>
                    </div>
                    <table class="form-table" >
                        <tr>
                            <td style="width: 300px;"><label for="default_role"><b>New User Default Role:</b></label></td>
                            <td>
                                <select class="form-control" name="default_role" id="default_role"><?php wp_dropdown_roles( get_option('default_role') ); ?></select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="submit" name="submit" value="Register" style="float: left; margin-right: 15px " class="button button-primary button-large" />
                                <input type="button" onclick="delete_register('cancel','<?php echo $custom_nonce;?>')" name="cancel" value="Cancel" style="float: left; " class="button button-primary button-large" />
                            </td>
                            <td>
                            </td>
                        </tr>
                    </table>
                </div>
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
                <br/>
                <div  style="padding-left: 30px;">Register your site with any standard OpenID Provider (OP). If you need an OpenID Provider you can deploy the <a target="_blank" href="https://gluu.org/docs/deployment/"> free open source Gluu Server.</a></div>
                <hr>
                <div style="padding-left: 30px;">This plugin relies on the oxd mediator service. For oxd deployment instructions and license information, please visit the <a target="_blank" href="http://gluu.org">oxd website.</a></div>
                <hr>
                <div style="margin-left: 20px">
                    <h3 style="padding-left: 10px;padding-bottom: 20px; border-bottom: 2px solid black; width: 60% "> Server Settings</h3>
                    <table class="form-table">
                        <tr>
                            <td  style="width: 300px;"><b>URI of the OpenID Provider:</b></td>
                            <td><input class="oxd_openid_table_textbox form-control" type="url" name="gluu_server_url" placeholder="Enter URI of the OpenID Provider" value="<?php if(get_option('gluu_op_host')){ echo get_option('gluu_op_host');} ?>" /></td>
                        </tr>
                        <tr>
                            <td  style="width: 300px;"><label for="gluu_custom_url"><b>Custom URI after logout:</b></label></td>
                            <td><input class="oxd_openid_table_textbox form-control" type="url" name="gluu_custom_url"  placeholder="Enter custom URI after logout" value="<?php if(get_option('gluu_custom_url')){ echo get_option('gluu_custom_url');} ?>" /></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><label for="wp_custom_login_url"><b>Site Login URI: <?php echo site_url(); ?>/</b></label></td>
                            <td>
                                <input class="oxd_openid_table_textbox form-control"  type="text" name="wp_custom_login_url"  placeholder="Enter your site login URI" value="<?php if(get_option('wp_custom_login_url')){ echo get_option('wp_custom_login_url');} ?>" /></td>
                        </tr>
                        <tr>
                            <td  style="width: 300px;"><b><font color="#FF0000">*</font>oxd port:</b></td>
                            <td>
                                <input class="oxd_openid_table_textbox form-control" required type="number" name="oxd_host_port" value="<?php if($gluu_oxd_config['oxd_host_port']){ echo $gluu_oxd_config['oxd_host_port'];}else{ echo 8099;} ?>" placeholder="Please enter free port (for example 8099). (Min. number 0, Max. number 65535)" /><br/>
                            </td>
                        </tr>
                    </table>
                </div>
                <hr>
                <div style="margin-left: 20px">
                    <h3 style="padding-left: 10px;padding-bottom: 20px; border-bottom: 2px solid black; width: 60% ">Enrollment and Access Management
                        <a data-toggle="tooltip" class="tooltipLink" data-original-title="Register new users when they login at an external identity provider. If you disable automatic registration, new users will need to be manually created">
                            <span class="glyphicon glyphicon-info-sign"></span>
                        </a></h3>
                    <div style="padding-left: 10px;">
                        <p><label><input name="gluu_users_can_register" type="radio" id="gluu_users_can_register" <?php if(get_option('gluu_users_can_register')==1){ echo "checked";} ?> value="1" style="margin-right: 3px"> Automatically register any user with an account in the OpenID Provider</label></p>
                    </div>
                    <div style="padding-left: 10px;">
                        <p><label ><input name="gluu_users_can_register" type="radio" id="gluu_users_can_register" <?php if(get_option('gluu_users_can_register')==2){ echo "checked";} ?> value="2" style="margin-right: 3px"> Only register and allow ongoing access to users with one or more of the following roles in the OpenID Provider</label></p>
                        <div style="margin-left: 30px;">
                            <div id="p_role" >
                                <?php $k=0;
                                if(!empty(get_option('gluu_new_role'))) {
                                    foreach (get_option('gluu_new_role') as $gluu_new_role) {
                                        if (!$k) {
                                            $k++;
                                            ?>
                                            <p>
                                                <input class="form-control " type="text" name="gluu_new_role[]"  required style="width: 20% !important; display: inline"
                                                       placeholder="Input role name"
                                                       value="<?php echo $gluu_new_role; ?>"/>
                                                <a href="#" class="btn btn-xs" id="add_new_role" ><span class="glyphicon glyphicon-plus"></span></a>
                                            </p>
                                            <?php
                                        } else {
                                            ?>
                                            <p>
                                                <input class="form-control " type="text" name="gluu_new_role[]"  required style="width: 20% !important; display: inline"
                                                       placeholder="Input role name"
                                                       value="<?php echo $gluu_new_role; ?>"/>
                                                <a href="#" class="btn btn-xs" id="add_new_role" ><span class="glyphicon glyphicon-plus"></span></a>
                                                <a href="#" class="btn btn-xs" id="remRole" ><span class="glyphicon glyphicon-minus"></span></a>
                                            </p>
                                        <?php }
                                    }
                                }else{
                                    ?>
                                    <p>
                                        <input class="form-control " type="text" name="gluu_new_role[]"  required style="width: 20% !important; display: inline" placeholder="Input role name" value=""/>
                                        <a href="#" class="btn btn-xs" id="add_new_role" ><span class="glyphicon glyphicon-plus"></span></a>
                                    </p>
                                    <?php
                                }?>
                            </div>
                        </div>
                    </div>
                    <div style="padding-left: 10px;">
                        <p>
                            <label >
                                <input name="gluu_users_can_register" type="radio" id="gluu_users_can_register_2" <?php if(get_option('gluu_users_can_register')==3){ echo "checked";} ?> value="3" style="margin-right: 3px">
                                Disable automatic registration
                            </label>
                        </p>
                    </div>
                    <table class="form-table">
                        <tr>
                            <td  style="width: 300px;"><label for="default_role"><b>New User Default Role:</b></label></td>
                            <td>
                                <select class="form-control" name="default_role" id="default_role"><?php wp_dropdown_roles( get_option('default_role') ); ?></select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="submit" name="submit" value="Register" style="float: left; margin-right: 15px " class="button button-primary button-large" />
                                <?php if(get_option('gluu_op_host')){?>
                                    <input type="button" onclick="delete_register('cancel','<?php echo $custom_nonce;?>')" name="cancel" value="Cancel" style="float: left; " class="button button-primary button-large" />
                                <?php }?>
                            </td>
                            <td>
                            </td>
                        </tr>
                    </table>
                </div>
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
                    <legend style="width: inherit;">
                        <div class="about">
                            <img style=" height: 45px;" src="<?php echo plugins_url('includes/images/gl.png', __FILE__)?>" />
                        </div>
                    </legend>
                    <div>
                        <h3 style="margin-left: 35px;padding-left: 10px;padding-bottom: 20px; border-bottom: 2px solid black; width: 60% "> Server Settings</h3>
                        <table style="margin-left: 30px" class="form-table">
                            <tr>
                                <td  style="width: 300px;"><b>URI of the OpenID Provider:</b></td>
                                <td><input class="oxd_openid_table_textbox form-control" disabled type="url" name="gluu_server_url" placeholder="Enter URI of the OpenID Provider" value="<?php if(get_option('gluu_op_host')){ echo get_option('gluu_op_host');} ?>" /></td>
                            </tr>
                            <tr>
                                <td  style="width: 300px;"><label for="gluu_custom_url"><b>Custom URI after logout:</b></label></td>
                                <td><input class="oxd_openid_table_textbox form-control" disabled type="url" name="gluu_custom_url"  placeholder="Enter custom URI after logout" value="<?php if(get_option('gluu_custom_url')){ echo get_option('gluu_custom_url');} ?>" /></td>
                            </tr>
                            <tr>
                                <td style="width: 300px;"><label for="wp_custom_login_url"><b>Site Login URI: <?php echo site_url(); ?>/</b></label></td>
                                <td>
                                    <input class="oxd_openid_table_textbox form-control" disabled type="text" name="wp_custom_login_url"  placeholder="Enter your site login URI" value="<?php if(get_option('wp_custom_login_url')){ echo get_option('wp_custom_login_url');} ?>" /></td>
                            </tr>
                            <?php
                            if(!empty($gluu_oxd_config['gluu_client_id']) and !empty($gluu_oxd_config['gluu_client_secret'])){
                                ?>
                                <tr>
                                    <td><b>Client ID:</b></td>
                                    <td><input class="form-control oxd_openid_table_textbox" disabled type="text" name="gluu_client_id"  placeholder="Enter OpenID Provider client ID" value="<?php if($gluu_oxd_config['gluu_client_id']){ echo $gluu_oxd_config['gluu_client_id'];} ?>" /></td>
                                </tr>
                                <tr>
                                    <td><b>Client Secret:</b></td>
                                    <td><input class="form-control oxd_openid_table_textbox" disabled type="text" name="gluu_client_secret" required placeholder="Enter OpenID Provider client secret" value="<?php if($gluu_oxd_config['gluu_client_secret']){ echo $gluu_oxd_config['gluu_client_secret'];} ?>" /></td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td  style="width: 300px;"><b>oxd port:</b></td>
                                <td>
                                    <input class="oxd_openid_table_textbox form-control" disabled required type="number" name="oxd_host_port" value="<?php if($gluu_oxd_config['oxd_host_port']){ echo $gluu_oxd_config['oxd_host_port'];}else{ echo 8099;} ?>" placeholder="Please enter free port (for example 8099). (Min. number 0, Max. number 65535)" />
                                </td>
                            </tr>
                            <tr>
                                <td  style="width: 300px;"><b>oxd ID:</b></td>
                                <td>
                                    <input class="form-control oxd_openid_table_textbox" <?php echo 'disabled'?> type="text" name="oxd_id" value="<?php echo get_option('gluu_oxd_id'); ?>" />
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        <h3 style="margin-left: 35px; padding-left: 10px;padding-bottom: 20px; border-bottom: 2px solid black; width: 60% ">Enrollment and Access Management
                            <a data-toggle="tooltip" class="tooltipLink" data-original-title="Register new users when they login at an external identity provider. If you disable automatic registration, new users will need to be manually created">
                                <span class="glyphicon glyphicon-info-sign"></span>
                            </a>
                        </h3>
                        <div style="margin-left: 35px; margin-top: 20px">
                            <p><label ><input name="gluu_users_can_register" disabled type="radio" id="gluu_users_can_register_1" <?php if(get_option('gluu_users_can_register')==1){ echo "checked";} ?> value="1" style="margin-right: 3px"> Automatically register any user with an account in the OpenID Provider</label></p>
                        </div>
                        <div style="margin-left: 35px">
                            <p><label ><input name="gluu_users_can_register" type="radio" disabled id="gluu_users_can_register" <?php if(get_option('gluu_users_can_register')==2){ echo "checked";} ?> value="2" style="margin-right: 3px"> Only register and allow ongoing access to users with one or more of the following roles in the OpenID Provider</label></p>
                            <div style="margin-left: 30px;">
                                <div id="p_role_disabled" >
                                    <?php
                                    $k=0;
                                    if(!empty(get_option('gluu_new_role'))) {
                                        foreach (get_option('gluu_new_role') as $gluu_new_role) {
                                            if (!$k) {
                                                $k++;
                                                ?>
                                                <p>
                                                    <input class="form-control" disabled type="text" name="gluu_new_role[]"  style="width: 20% !important; display: inline"
                                                           placeholder="Input role name"
                                                           value="<?php echo $gluu_new_role; ?>"/>
                                                    <a href="#" disabled class="btn btn-xs" id="add_new_role_disabled" ><span class="glyphicon glyphicon-plus"></span></a>
                                                </p>
                                                <?php
                                            } else {
                                                ?>
                                                <p>
                                                    <input class="form-control" disabled type="text" name="gluu_new_role[]"  style="width: 20% !important; display: inline"
                                                           placeholder="Input role name"
                                                           value="<?php echo $gluu_new_role; ?>"/>
                                                    <a href="#" disabled class="btn btn-xs" id="add_new_role_disabled" ><span class="glyphicon glyphicon-plus"></span></a>
                                                    <a href="#" disabled class="btn btn-xs" id="remRole_disabled" ><span class="glyphicon glyphicon-minus"></span></a>
                                                </p>
                                            <?php }
                                        }
                                    }else{
                                        ?>
                                        <p>
                                            <input class="form-control" type="text" disabled name="gluu_new_role[]"  style="width: 20% !important; display: inline" placeholder="Input role name" value=""/>
                                            <a href="#" disabled class="btn btn-xs" id="add_new_role_disabled" ><span class="glyphicon glyphicon-plus"></span></a>
                                        </p>
                                        <?php
                                    }?>
                                </div>
                            </div>
                        </div>
                        <div style="margin-left: 35px; ">
                            <p>
                                <label >
                                    <input name="gluu_users_can_register" type="radio" disabled id="gluu_users_can_register_2" <?php if(get_option('gluu_users_can_register')==3){ echo "checked";} ?> value="3" style="margin-right: 3px">
                                    Disable automatic registration
                                </label>
                            </p>
                        </div>
                        <table style="margin-left: 30px" class="form-table">
                            <tr>
                                <td  style="width: 300px;"><label for="default_role"><b>New User Default Role:</b></label></td>
                                <td>
                                    <select class="form-control" disabled name="default_role" id="default_role"><?php wp_dropdown_roles( get_option('default_role') ); ?></select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a class="button button-primary button-large" style="float: left" href="<?php echo add_query_arg( array('tab' => 'register_edit'), $_SERVER['REQUEST_URI'] ); ?>">Edit</a>
                                    <input type="submit" onclick="return confirm('Are you sure that you want to remove this OpenID Connect provider? Users will no longer be able to authenticate against this OP.')" name="submit" style="float: left; margin-left: 20px" value="Delete" <?php if(!gluu_is_oxd_registered()) echo 'disabled'?> class="button button-primary button-large" />
                                </td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
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
        <script type="application/javascript">
            /*window.onbeforeunload = function(){
             return "You may have unsaved changes. Are you sure you want to leave this page?"
             }*/
            var formSubmitting = false;
            var setFormSubmitting = function() { formSubmitting = true; };
            var edit_cancel_function = function() { formSubmitting = true; };
            window.onload = function() {
                window.addEventListener("beforeunload", function (e) {
                    if (formSubmitting ) {
                        return undefined;
                    }
    
                    var confirmationMessage = "You may have unsaved changes. Are you sure you want to leave this page?";
    
                    (e || window.event).returnValue = confirmationMessage; //Gecko + IE
                    return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
                });
            };
        </script>
        <form name="f" method="post" action="" id="register-form" onsubmit="setFormSubmitting()">
            <input type="hidden" name="option" value="oxd_openid_edit_config" />
            <input type="hidden" name="custom_nonce" value="<?php echo $custom_nonce; ?>" />
            <div class="oxd_openid_table_layout">
                <fieldset style="border: 2px solid #53cc6b;">
                    <legend style="width: inherit;"><div class="about">
                            <img style=" height: 45px" src="<?php echo plugins_url('includes/images/gl.png', __FILE__)?>" />
                        </div>
                    </legend>
                    <div>
                        <h3 style="margin-left: 30px;padding-left: 10px;padding-bottom: 20px; border-bottom: 2px solid black; width: 60% ">Server Settings</h3>
                        <table style="margin-left: 35px;" class="form-table">
                            <tr>
                                <td  style="width: 300px;"><b>URI of the OpenID Provider:</b></td>
                                <td><input class="oxd_openid_table_textbox form-control" disabled type="url" name="gluu_server_url"  placeholder="Enter URI of the OpenID Provider" value="<?php if(get_option('gluu_op_host')){ echo get_option('gluu_op_host');} ?>" /></td>
                            </tr>
                            <tr>
                                <td style="width: 300px;"><label for="gluu_custom_url"><b>Custom URI after logout:</b></label></td>
                                <td><input class="oxd_openid_table_textbox form-control"  type="url" name="gluu_custom_url"  placeholder="Enter custom URI after logout" value="<?php if(get_option('gluu_custom_url')){ echo get_option('gluu_custom_url');} ?>" /></td>
                            </tr>
                            <tr>
                                <td style="width: 300px;"><label for="wp_custom_login_url"><b>Site Login URI: <?php echo site_url(); ?>/</b></label></td>
                                <td>
                                    <input class="oxd_openid_table_textbox form-control"  type="text" name="wp_custom_login_url"  placeholder="Enter your site login URI" value="<?php if(get_option('wp_custom_login_url')){ echo get_option('wp_custom_login_url');} ?>" /></td>
                            </tr>
                            <?php
                            if(!empty($gluu_oxd_config['gluu_client_id']) and !empty($gluu_oxd_config['gluu_client_secret'])){
                                ?>
                                <tr>
                                    <td style="width: 300px;"><b>Client ID:</b></td>
                                    <td><input class="oxd_openid_table_textbox form-control" type="text" name="gluu_client_id"  placeholder="Enter OpenID Provider client ID" value="<?php if($gluu_oxd_config['gluu_client_id']){ echo $gluu_oxd_config['gluu_client_id'];} ?>" /></td>
                                </tr>
                                <tr>
                                    <td style="width: 300px;"><b>Client Secret:</b></td>
                                    <td><input class="oxd_openid_table_textbox form-control"  type="text" name="gluu_client_secret"  placeholder="Enter OpenID Provider client secret" value="<?php if($gluu_oxd_config['gluu_client_secret']){ echo $gluu_oxd_config['gluu_client_secret'];} ?>" /></td>
                                </tr>
                                <?php
                            }
                            ?>
    
                            <tr>
                                <td style="width: 300px;"><b>oxd port:</b></td>
                                <td>
                                    <br/>
                                    <input class="oxd_openid_table_textbox form-control"  required type="number" name="oxd_host_port" value="<?php if($gluu_oxd_config['oxd_host_port']){ echo $gluu_oxd_config['oxd_host_port'];}else{ echo 8099;} ?>" placeholder="Please enter free port (for example 8099). (Min. number 0, Max. number 65535)" />
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 300px;"><b>oxd ID:</b></td>
                                <td>
                                    <input class="oxd_openid_table_textbox form-control" <?php echo 'disabled'?> type="text" name="oxd_id" value="<?php echo get_option('gluu_oxd_id'); ?>" /><br/>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        <h3 style="margin-left: 30px;padding-left: 10px;padding-bottom: 20px; border-bottom: 2px solid black; width: 60% ">Enrollment and Access Management
                            <a data-toggle="tooltip" class="tooltipLink" data-original-title="Register new users when they login at an external identity provider. If you disable automatic registration, new users will need to be manually created">
                                <span class="glyphicon glyphicon-info-sign"></span>
                            </a>
                        </h3>
                        <div style="margin-left: 43px; ">
                            <p>
                                <label >
                                    <input name="gluu_users_can_register" type="radio" id="gluu_users_can_register_1" <?php if(get_option('gluu_users_can_register')==1){ echo "checked";} ?> value="1" style="margin-right: 3px">
                                    Automatically register any user with an account in the OpenID Provider
                                </label>
                            </p>
                        </div>
                        <div style="margin-left: 43px; ">
                            <p>
                                <label >
                                    <input name="gluu_users_can_register" type="radio" id="gluu_users_can_register" <?php if(get_option('gluu_users_can_register')==2){ echo "checked";} ?> value="2" style="margin-right: 3px">
                                    Only register and allow ongoing access to users with one or more of the following roles in the OpenID Provider
                                </label>
                            </p>
                        </div>
                        <div style="margin-left: 43px; padding-left: 30px;">
                            <div  id="p_role" >
    
                                <?php
                                $k=0;
                                if(!empty(get_option('gluu_new_role'))) {
                                    foreach (get_option('gluu_new_role') as $gluu_new_role) {
                                        if (!$k) {
                                            $k++;
                                            ?>
                                            <p>
                                                <input class="form-control" type="text" name="gluu_new_role[]"  required style="width: 20% !important; display: inline"
                                                       placeholder="Input role name"
                                                       value="<?php echo $gluu_new_role; ?>"/>
                                                <a href="#" class="btn btn-xs" id="add_new_role" ><span class="glyphicon glyphicon-plus"></span></a>
                                            </p>
                                            <?php
                                        } else {
                                            ?>
                                            <p>
                                                <input  class="form-control"  type="text" name="gluu_new_role[]"  required style="width: 20% !important; display: inline"
                                                        placeholder="Input role name"
                                                        value="<?php echo $gluu_new_role; ?>"/>
                                                <a href="#" class="btn btn-xs" id="add_new_role" ><span class="glyphicon glyphicon-plus"></span></a>
                                                <a href="#" class="btn btn-xs" id="remRole" ><span class="glyphicon glyphicon-minus"></span></a>
                                            </p>
                                        <?php }
                                    }
                                }else{
                                    ?>
                                    <p>
                                        <input class="form-control" type="text" name="gluu_new_role[]"  required style="width: 20% !important; display: inline" placeholder="Input role name" value=""/>
                                        <a href="#" class="btn btn-xs" id="add_new_role" ><span class="glyphicon glyphicon-plus"></span></a>
                                    </p>
                                    <?php
                                }?>
                            </div>
                        </div>
                        <div style="margin-left: 43px; ">
                            <p>
                                <label >
                                    <input name="gluu_users_can_register" type="radio" id="gluu_users_can_register_2" <?php if(get_option('gluu_users_can_register')==3){ echo "checked";} ?> value="3" style="margin-right: 3px">
                                    Disable automatic registration
                                </label>
                            </p>
                        </div>
                        <table style="margin-left: 35px;" class="form-table">
                            <tr>
                                <td style="width: 300px;"><label for="default_role"><b>New User Default Role:</b></label></td>
                                <td>
                                    <select  class="form-control" name="default_role" id="default_role"><?php wp_dropdown_roles( get_option('default_role') ); ?></select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="submit" name="submit" value="Save" style="float: left; margin-right: 20px" class="button button-primary button-large" />
                                    <a class="button button-primary button-large" onclick="edit_cancel_function()" id="edit_cancel" href="<?php echo add_query_arg( array('tab' => 'register'), $_SERVER['REQUEST_URI'] ); ?>">Cancel</a>
                                </td>
                                <td></td>
    
                            </tr>
                        </table>
                    </div>
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
                    <legend style="width: inherit;">
                        <div class="about">
                            <img style=" height: 45px" src="<?php echo plugins_url('includes/images/gl.png', __FILE__)?>" />
                        </div>
                    </legend>
                    <div style="margin-left: 35px">
                        <p><label ><input name="gluu_users_can_register" type="radio" id="gluu_users_can_register_1" <?php if(get_option('gluu_users_can_register')==1){ echo "checked";} ?> value="1" style="margin-right: 3px"> Automatically register any user with an account in the OpenID Provider</label></p>
                    </div>
                    <div style="margin-left: 35px">
                        <p>
                            <label >
                                <input name="gluu_users_can_register" type="radio" id="gluu_users_can_register" <?php if(get_option('gluu_users_can_register')==2){ echo "checked";} ?> value="2" style="margin-right: 3px"> Only register and allow ongoing access to users with one or more of the following roles in the OpenID Provider</label></p>
                        <div style="margin-left: 20px;display:<?php if(get_option('gluu_users_can_register') == 1){ echo "none";} ?>;">
                            <div id="p_role" >
    
                                <?php $k=0;
                                if(!empty(get_option('gluu_new_role'))) {
                                    foreach (get_option('gluu_new_role') as $gluu_new_role) {
                                        if (!$k) {
                                            $k++;
                                            ?>
                                            <p>
                                                <input  class="form-control" type="text" name="gluu_new_role[]"  required style="width: 20% !important; display: inline"
                                                        placeholder="Input role name"
                                                        value="<?php echo $gluu_new_role; ?>"/>
                                                <a href="#" class="btn btn-xs" id="add_new_role" ><span class="glyphicon glyphicon-plus"></span></a>
                                            </p>
                                            <?php
                                        } else {
                                            ?>
                                            <p>
                                                <input  class="form-control" type="text" name="gluu_new_role[]" required style="width: 20% !important; display: inline"
                                                        placeholder="Input role name"
                                                        value="<?php echo $gluu_new_role; ?>"/>
                                                <a href="#" class="btn btn-xs" id="add_new_role" ><span class="glyphicon glyphicon-plus"></span></a>
                                                <a href="#" class="btn btn-xs" id="remRole" ><span class="glyphicon glyphicon-minus"></span></a>
                                            </p>
                                        <?php }
                                    }
                                }else{
                                    ?>
                                    <p>
                                        <input class="form-control" type="text" name="gluu_new_role[]" required style="width: 20% !important; display: inline" placeholder="Input role name" value=""/>
                                        <a href="#" class="btn btn-xs" id="add_new_role" ><span class="glyphicon glyphicon-plus"></span></a>
                                    </p>
                                    <?php
                                }?>
                            </div>
                        </div>
                    </div>
                    <div style="margin-left: 43px; ">
                        <p>
                            <label >
                                <input name="gluu_users_can_register" type="radio" id="gluu_users_can_register_2" <?php if(get_option('gluu_users_can_register')==3){ echo "checked";} ?> value="3" style="margin-right: 3px">
                                Disable automatic registration
                            </label>
                        </p>
                    </div>
                    <table style="margin-left: 35px" class="form-table">
                        <tr>
                            <td style="width: 300px;"> <label for="default_role"><b>New User Default Role:</b></label></td>
                            <td>
                                <select  class="form-control" name="default_role" id="default_role"><?php wp_dropdown_roles( get_option('default_role') ); ?></select>
                                <br/><br/>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>URI of the OpenID Provider:</b></td>
                            <td><input class="oxd_openid_table_textbox form-control" disabled type="url" name="gluu_server_url"  placeholder="Enter URI of the OpenID Provider" value="<?php if(get_option('gluu_op_host')){ echo get_option('gluu_op_host');} ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="gluu_custom_url"><b>Custom URI after logout:</b></label></td>
                            <td><input class="oxd_openid_table_textbox form-control"  type="url" name="gluu_custom_url"  placeholder="Enter custom URI after logout" value="<?php if(get_option('gluu_custom_url')){ echo get_option('gluu_custom_url');} ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="wp_custom_login_url"><b>Site Login URI: <?php echo site_url(); ?>/</b></label></td>
                            <td>
                                <input class="oxd_openid_table_textbox form-control"  type="text" name="wp_custom_login_url"  placeholder="Enter your site login URI" value="<?php if(get_option('wp_custom_login_url')){ echo get_option('wp_custom_login_url');} ?>" /></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>Client ID:</b></td>
                            <td><input  class="form-control oxd_openid_table_textbox"  type="text" name="gluu_client_id"  placeholder="Enter OpenID Provider client ID" value="<?php if($gluu_oxd_config['gluu_client_id']){ echo $gluu_oxd_config['gluu_client_id'];} ?>" /></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>Client Secret:</b></td>
                            <td><input class="form-control oxd_openid_table_textbox"  type="text" name="gluu_client_secret" placeholder="Enter OpenID Provider client secret" value="<?php if($gluu_oxd_config['gluu_client_secret']){ echo $gluu_oxd_config['gluu_client_secret'];} ?>" /></td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>oxd port:</b></td>
                            <td>
                                <br/>
                                <input class="form-control oxd_openid_table_textbox"  required type="number" name="oxd_host_port" value="<?php if($gluu_oxd_config['oxd_host_port']){ echo $gluu_oxd_config['oxd_host_port'];}else{ echo 8099;} ?>" placeholder="Please enter free port (for example 8099). (Min. number 0, Max. number 65535)" />
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"><b>oxd ID:</b></td>
                            <td>
                                <input class="form-control oxd_openid_table_textbox" <?php echo 'disabled'?> type="text" name="oxd_id" value="<?php echo get_option('gluu_oxd_id'); ?>" /><br/>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 300px;"> <input type="submit" style="float: right" name="submit" value="Save" class="button button-primary button-large" />
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
                <form action="" method="post" id="scpe_update">
                    <input type="hidden" name="option" value="oxd_openid_config_info_hidden" />
                    <input type="hidden" name="custom_nonce" value="<?php echo $custom_nonce; ?>" />
                    <br/>
    
                    <fieldset style="border: 2px solid #53cc6b;">
                        <legend style="width: inherit;"><div class="about">
                                <img style=" height: 45px" src="<?php echo plugins_url('includes/images/gl.png', __FILE__)?>" />
                            </div>
                        </legend>
                        <h3 style="margin-left: 30px;padding-bottom: 20px; border-bottom: 2px solid black; width: 60% ">User Scopes</h3>
                        <table style="margin-left: 30px" class="form-table">
                            <tbody>
                            <tr>
                                <th scope="col" >
                                    <p id="scop_section">
                                        Requested scopes
                                        <a data-toggle="tooltip" class="tooltipLink" data-original-title="Scopes are bundles of attributes that the OP stores about each user. It is recommended that you request the minimum set of scopes required">
                                            <span class="glyphicon glyphicon-info-sign"></span>
                                        </a>
                                    </p>
                                </th>
                                <?php $get_scopes = get_option('gluu_oxd_openid_scops');
                                ?>
                                <td>
                                    <div class="table-responsive">
                                        <table class="table table-striped" style="width: 200px">
                                            <tr >
                                                <td style="padding: 0px !important;">
                                                    <label  for="openid">
                                                        <input checked type="checkbox" name=""  id="openid" value="openid"  disabled />
                                                        <input type="hidden"  name="scope[]"  value="openid" />openid
                                                    </label>
                                                </td>
                                                <td style="padding: 0px !important; "><button  class="btn btn-danger btn-xs" style="margin: 5px; float: right" disabled><span class="glyphicon glyphicon-trash"></span></button></td>
                                            </tr>
                                            <tr >
                                                <td style="padding: 0px !important;"><label  for="profile">
                                                        <input checked type="checkbox" name=""  id="profile" value="profile"  disabled />
                                                        <input type="hidden"  name="scope[]"  value="profile" />profile
                                                    </label></td>
                                                <td style="padding: 0px !important;"><button class="btn btn-danger btn-xs" style="margin: 5px; float: right" disabled ><span class="glyphicon glyphicon-trash"></span></button></td>
                                            </tr >
                                            <tr >
                                                <td style="padding: 0px !important;">
                                                    <label  for="email">
                                                        <input checked type="checkbox" name="" id="email" value="email"  disabled />
                                                        <input type="hidden" name="scope[]" value="email" />email
                                                    </label>
                                                </td>
                                                <td style="padding: 0px !important; "><button class="btn btn-danger btn-xs" style="margin: 5px; float: right" disabled><span class="glyphicon glyphicon-trash"></span></button></td>
                                            </tr>
    
    
                                            <?php
                                                foreach($get_scopes as $scop) :?>
                                                <?php if ($scop == 'openid' or $scop == 'email' or $scop == 'profile'){?>
                                                <?php } else{?>
                                                    <tr >
                                                        <td style="padding: 0px !important;">
                                                            <p id="<?php echo $scop;?>">
                                                                <input <?php if($options && in_array($scop, $options['scope'])){ echo "checked";} ?> type="checkbox" name="scope[]"  id="<?php echo $scop;?>" value="<?php echo $scop;?>" <?php if (!gluu_is_oxd_registered()) echo ' disabled '; ?> />
                                                                <?php echo $scop;?>
                                                            </p>
                                                        </td>
                                                        <td style="padding: 0px !important; ">
                                                            <a href="#scop_section" class="btn btn-danger btn-xs" style="margin: 5px; float: right" onclick="delete_scopes('<?php echo $scop;?>','<?php echo $custom_nonce;?>')" ><span class="glyphicon glyphicon-trash"></span></a>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            endforeach;?>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr >
                                <th scope="row">
                                    Add scopes
                                </th>
                                <td>
                                    <div id="p_scents">
                                        <p>
                                            <input class="form-control" <?php if(!gluu_is_oxd_registered()) echo 'disabled'?> type="text" style="width: 200px !important;" id="new_scope_field" name="new_scope[]" placeholder="Input scope name" />
                                        </p>
                                        <p>
                                            <button type="button" onclick="add_scope_for_delete('<?php echo $custom_nonce?>')" id="add_new_scope"> Add</button>
                                        </p>
    
    
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <h3 style="margin-left: 30px;padding-bottom: 20px; border-bottom: 2px solid black; width: 60%">Authentication</h3>
                        <p style=" margin-left: 30px; font-weight:bold "><label ><input type="checkbox" name="send_user_check" id="send_user" value="1" <?php if(!gluu_is_oxd_registered()) echo 'disabled'?> <?php checked( get_option('gluu_send_user_check'));?> /> Bypass the local WordPress login page and send users straight to the OP for authentication</label>
                        </p>
                        <table style="margin-left: 30px" class="form-table">
                            <tbody>
                            <tr>
                                <th >
                                    Select ACR: <a data-toggle="tooltip" class="tooltipLink" data-original-title="The OpenID Provider may make available multiple authentication mechanisms. To signal which type of authentication should be used for access to this site you can request a specific ACR. To accept the OP's default authentication, set this field to none.">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                </th>
                                <td>
                                    <?php
                                    $custom_scripts = get_option('gluu_oxd_openid_custom_scripts');
                                    if(!empty($custom_scripts)){
                                        ?>
                                        <select class="form-control" style="width: 200px !important; margin-left: -15px" name="send_user_type" id="send_user_type" <?php if(!gluu_is_oxd_registered()) echo 'disabled'?>>
                                            <option value="default">none</option>
                                            <?php
                                            if($custom_scripts){
                                                foreach($custom_scripts as $custom_script){
                                                    if($custom_script != "default" and $custom_script != "none"){
                                                        ?>
                                                        <option <?php if(get_option('gluu_auth_type') == $custom_script) echo 'selected'; ?> value="<?php echo $custom_script;?>"><?php echo $custom_script;?></option>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <th >
                                    <input type="submit" class="button button-primary button-large" <?php if(!gluu_is_oxd_registered()) echo 'disabled'?> value="Save Authentication Settings" name="set_oxd_config" />
                                </th>
                                <td>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <h3 style="margin-left: 30px;padding-top: 20px; border-top: 2px solid black; width: 60%"></h3>
                    </fieldset>
                </form>
            </div>
        </div>
        <?php
    }




