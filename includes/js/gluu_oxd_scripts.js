/**
 * Created by Volodya on 11/27/2015.
 */

var tempHorSize = option.oxd_login_icon_custom_size;
var tempHorTheme = option.oxd_openid_login_theme;
var tempHorCustomTheme = option.oxd_openid_login_custom_theme;
var tempHorCustomColor = option.oxd_login_icon_custom_color;
var tempHorSpace = option.oxd_login_icon_space;
var tempHorHeight = option.oxd_login_icon_custom_height;
var customScript = option.oxd_openid_custom_scripts;
function oxdLoginIncrement(e,t,r,a,i){
    var h,s,c=!1,_=a;s=function(){
        "add"==t&&r.value<60?r.value++:"subtract"==t&&r.value>20&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}

function oxdLoginSpaceIncrement(e,t,r,a,i){
    var h,s,c=!1,_=a;s=function(){
        "add"==t&&r.value<60?r.value++:"subtract"==t&&r.value>0&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}

function oxdLoginWidthIncrement(e,t,r,a,i){
    var h,s,c=!1,_=a;s=function(){
        "add"==t&&r.value<1000?r.value++:"subtract"==t&&r.value>140&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}

function oxdLoginHeightIncrement(e,t,r,a,i){
    var h,s,c=!1,_=a;s=function(){
        "add"==t&&r.value<50?r.value++:"subtract"==t&&r.value>35&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}

oxdLoginIncrement(document.getElementById('oxd_login_size_plus'), "add", document.getElementById('oxd_login_icon_size'), 300, 0.7);
oxdLoginIncrement(document.getElementById('oxd_login_size_minus'), "subtract", document.getElementById('oxd_login_icon_size'), 300, 0.7);

oxdLoginSpaceIncrement(document.getElementById('oxd_login_space_plus'), "add", document.getElementById('oxd_login_icon_space'), 300, 0.7);
oxdLoginSpaceIncrement(document.getElementById('oxd_login_space_minus'), "subtract", document.getElementById('oxd_login_icon_space'), 300, 0.7);

oxdLoginWidthIncrement(document.getElementById('oxd_login_width_plus'), "add", document.getElementById('oxd_login_icon_width'), 300, 0.7);
oxdLoginWidthIncrement(document.getElementById('oxd_login_width_minus'), "subtract", document.getElementById('oxd_login_icon_width'), 300, 0.7);

oxdLoginHeightIncrement(document.getElementById('oxd_login_height_plus'), "add", document.getElementById('oxd_login_icon_height'), 300, 0.7);
oxdLoginHeightIncrement(document.getElementById('oxd_login_height_minus'), "subtract", document.getElementById('oxd_login_icon_height'), 300, 0.7);

function setLoginTheme(){return jQuery('input[name=oxd_openid_login_theme]:checked', '#form-apps').val();}
function setLoginCustomTheme(){return jQuery('input[name=oxd_openid_login_custom_theme]:checked', '#form-apps').val();}
function setSizeOfIcons(){

    if((jQuery('input[name=oxd_openid_login_theme]:checked', '#form-apps').val()) == 'longbutton'){
        return document.getElementById('oxd_login_icon_width').value;
    }else{
        return document.getElementById('oxd_login_icon_size').value;
    }
}
oxdLoginPreview(setSizeOfIcons(),tempHorTheme,tempHorCustomTheme,tempHorCustomColor,tempHorSpace,tempHorHeight);

function oxdLoginPreview(t,r,l,p,n,h){
    if(l == 'default'){
        if(r == 'longbutton'){
            var a = "btn-defaulttheme";
            jQuery("."+a).css("width",t+"px");
            jQuery("."+a).css("padding-top",(h-29)+"px");
            jQuery("."+a).css("padding-bottom",(h-29)+"px");
            jQuery(".fa").css("padding-top",(h-35)+"px");
            jQuery("."+a).css("margin-bottom",(n-5)+"px");
        }else{
            var a="oxd_login_icon_preview";
            jQuery("."+a).css("margin-left",(n-4)+"px");

            if(r=="circle"){
                jQuery("."+a).css({height:t,width:t});
                jQuery("."+a).css("borderRadius","999px");
            }else if(r=="oval"){
                jQuery("."+a).css("borderRadius","5px");
                jQuery("."+a).css({height:t,width:t});
            }else if(r=="square"){
                jQuery("."+a).css("borderRadius","0px");
                jQuery("."+a).css({height:t,width:t});
            }
        }
    }
    else if(l == 'custom'){
        if(r == 'longbutton'){

            var a = "btn-customtheme";
            jQuery("."+a).css("width",(t)+"px");
            jQuery("."+a).css("padding-top",(h-29)+"px");
            jQuery("."+a).css("padding-bottom",(h-29)+"px");
            jQuery(".fa").css("padding-top",(h-35)+"px");
            jQuery("."+a).css("margin-bottom",(n-5)+"px");
            jQuery("."+a).css("background","#"+p);
        }else{
            var a="oxd_custom_login_icon_preview";
            jQuery("."+a).css({height:t-8,width:t});
            jQuery("."+a).css("padding-top","8px");
            jQuery("."+a).css("margin-left",(n-4)+"px");

            if(r=="circle"){
                jQuery("."+a).css("borderRadius","999px");
            }else if(r=="oval"){
                jQuery("."+a).css("borderRadius","5px");
            }else if(r=="square"){
                jQuery("."+a).css("borderRadius","0px");
            }
            jQuery("."+a).css("background","#"+p);
            jQuery("."+a).css("font-size",(t-16)+"px");
        }
    }


    previewLoginIcons();

}

function checkLoginButton(){
    if(document.getElementById('iconwithtext').checked) {
        if(setLoginCustomTheme() == 'default'){
            jQuery(".oxd_login_icon_preview").hide();
            jQuery(".oxd_custom_login_icon_preview").hide();
            jQuery(".btn-customtheme").hide();
            jQuery(".btn-defaulttheme").show();
        }else if(setLoginCustomTheme() == 'custom'){
            jQuery(".oxd_login_icon_preview").hide();
            jQuery(".oxd_custom_login_icon_preview").hide();
            jQuery(".btn-defaulttheme").hide();
            jQuery(".btn-customtheme").show();
        }
        jQuery("#commontheme").hide();
        jQuery(".longbuttontheme").show();
    }else {

        if(setLoginCustomTheme() == 'default'){
            jQuery(".oxd_login_icon_preview").show();
            jQuery(".btn-defaulttheme").hide();
            jQuery(".btn-customtheme").hide();
            jQuery(".oxd_custom_login_icon_preview").hide();
        }else if(setLoginCustomTheme() == 'custom'){
            jQuery(".oxd_login_icon_preview").hide();
            jQuery(".oxd_custom_login_icon_preview").show();
            jQuery(".btn-defaulttheme").hide();
            jQuery(".btn-customtheme").hide();
        }
        jQuery("#commontheme").show();
        jQuery(".longbuttontheme").hide();
    }
    previewLoginIcons();
}

function previewLoginIcons() {
    var flag = 0;
    customScript.forEach(function(value) {
        if (document.getElementById(value.value+'_enable').checked) {
            flag = 1;
            if(document.getElementById('oxd_openid_login_default_radio').checked && !document.getElementById('iconwithtext').checked)
                jQuery("#oxd_login_icon_preview_"+value.value).show();
            if(document.getElementById('oxd_openid_login_custom_radio').checked && !document.getElementById('iconwithtext').checked)
                jQuery("#oxd_custom_login_icon_preview_"+value.value).show();
            if(document.getElementById('oxd_openid_login_default_radio').checked && document.getElementById('iconwithtext').checked)
                jQuery("#oxd_login_button_preview_"+value.value).show();
            if(document.getElementById('oxd_openid_login_custom_radio').checked && document.getElementById('iconwithtext').checked)
                jQuery("#oxd_custom_login_button_preview_"+value.value).show();
        } else if(!document.getElementById(value.value+'_enable').checked){
            jQuery("#oxd_login_icon_preview_"+value.value).hide();
            jQuery("#oxd_custom_login_icon_preview_"+value.value).hide();
            jQuery("#oxd_login_button_preview_"+value.value).hide();
            jQuery("#oxd_custom_login_button_preview_"+value.value).hide();
        }
    });


    if(flag) {
        jQuery("#no_apps_text").hide();
    } else {
        jQuery("#no_apps_text").show();
    }
}
checkLoginButton();