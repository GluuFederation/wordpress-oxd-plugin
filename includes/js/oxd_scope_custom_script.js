/**
 * Created by Vlad Karapetyan on 12/25/2015.
 */
jQuery(function() {
    var scntDiv = jQuery('#p_scents');
    var striped = jQuery('.table-striped');
    var k = jQuery('#p_scents p').size() + 1;



    var roleDiv = jQuery('#p_role');
    var i = jQuery('#p_role p').size() + 1;

    jQuery('#add_new_role').live('click', function() {
        jQuery('<p><input type="text" name="gluu_new_role[]" placeholder="Input role name" style="margin-right: 3px"/><a href="#" class="btn btn-xs" id="add_new_role" ><span class="glyphicon glyphicon-plus"></span></a> <a href="#" class="btn btn-xs" id="remRole" ><span class="glyphicon glyphicon-minus"></span></a></p>').appendTo(roleDiv);
        i++;
        return false;
    });

    jQuery('#remRole').live('click', function() {
        if( i > 2 ) {
            jQuery(this).parents('p').remove();
            i--;
        }
        return false;
    });

    var scntDiv_script = jQuery('#p_scents_script');
    var j = jQuery('#p_scents_script p').size() + 1;

    jQuery('#add_new_suctom_script').live('click', function() {
        jQuery('<p>' +
            '<input type="text" style="margin-right: 5px; " name="acr_value[]" placeholder="ACR Value in the OP" />' +
            '<a href="#" class="btn btn-xs" id="add_new_suctom_script" ><span class="glyphicon glyphicon-plus"></span></a>' +
            '<a href="#" class="btn btn-xs" id="remScnt_script" ><span class="glyphicon glyphicon-minus"></span></a>' +
            '</p>').appendTo(scntDiv_script);
        j++;
        jQuery('#count_scripts').val(jQuery('#p_scents_script p').size());
        return false;
    });

    jQuery('#remScnt_script').live('click', function() {
        if( j > 2 ) {
            jQuery(this).parents('p').remove();
            j--;
            jQuery('#count_scripts').val(jQuery('#p_scents_script p').size());
        }
        return false;
    });


});

function delete_custom_script(val, nonce){
    if (confirm("Are you sure that you want to delete this ACR? You will no longer be able to request this authentication mechanism from the OP.")) {
        jQuery.ajax({
            url: window.location,
            type: 'POST',
            data:{option:'oxd_openid_config_info_hidden', custom_nonce:nonce, delete_value:val},
            success: function(result){
                if(result){
                    location.reload();
                }else{
                    alert('Error, please try again.')
                }
            }});
    }else{
        return false;
    }

}

function add_scope_for_delete(nonce) {
        var striped = jQuery('.table-striped');
        var k = jQuery('#p_scents p').size() + 1;
        var new_scope_field = jQuery('#new_scope_field').val();
        var m = true;
        if(new_scope_field){
            jQuery("input[name='scope[]']").each(function(){
                // get name of input
                var value =  jQuery(this).attr("value");
                if(value == new_scope_field){
                    m = false;
                }
            });
            if(m){
                jQuery('<tr >' +
                        '<td style="padding: 0px !important;">' +
                        '   <p  id="'+new_scope_field+'">' +
                        '     <input type="checkbox" name="scope[]" id="new_'+new_scope_field+'" value="'+new_scope_field+'"  /> &nbsp;'+new_scope_field+
                        '   </p>' +
                        '</td>' +
                        '<td style="padding: 0px !important; ">' +
                            '   <a href="#scop_section" class="btn btn-danger btn-xs" style="margin: 5px; float: right" onclick="delete_scopes(\''+new_scope_field+'\',\''+nonce+'\')" >' +
                                '<span class="glyphicon glyphicon-trash"></span>' +
                                '</a>' +
                        '</td>' +
                        '</tr>').appendTo(striped);
                jQuery('#new_scope_field').val('');

                jQuery.ajax({
                    url: window.location,
                    type: 'POST',
                    data:{option:'oxd_openid_config_new_scope', custom_nonce:nonce, new_value_scope:new_scope_field},
                    success: function(result){
                    }});
                jQuery("#new_"+new_scope_field).change(
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

                return false;
            }
            else{
                alert('The scope named '+new_scope_field+' is exist!');
                jQuery('#new_scope_field').val('');
                return false;
            }
        }else{
            alert('Please input scope name!');
            jQuery('#new_scope_field').val('');
            return false;
        }
}

function delete_scopes(val, nonce){
    if (confirm("Are you sure that you want to delete this scope? You will no longer be able to request this user information from the OP.")) {
        jQuery.ajax({
            url: window.location,
            type: 'POST',
            data:{option:'oxd_openid_config_info_hidden', custom_nonce:nonce, delete_scope:val},
            success: function(result){
                if(result){
                    location.reload();
                }else{
                    alert('Error, please try again.')
                }
            }});
    }
    else{
        return false;
    }

}
function delete_register(val, nonce){
    jQuery.ajax({
        url: window.location,
        type: 'POST',
        data:{option:'oxd_openid_reset_config', custom_nonce:nonce, delete_scope:val},
        success: function(result){
            if(result){
                location.reload();
            }else{
                alert('Error, please try again.')
            }
        }});
}