// oometaboxdemo.js
console.log("loaded oometaboxdemo.js");

function OOMetaBoxDemo()
{
	this.form_field = null;
	this.$form_field_name = null;
	this.original_send_to_editor = null;
}

var oometaboxdemo = new OOMetaBoxDemo();


/*
 * 
 * @returns {Boolean}
 */
OOMetaBoxDemo.prototype.upload_media = function()
{
console.log("inside upload_media()");
	jQuery("html").addClass("Image");
	this.form_field = jQuery("#oomb_image").attr("name");
	tb_show("", "media-upload.php?type=image&TB_iframe=true");
	return (false);
};


/*
 * Replacement method for window.send_to_editor
 * @param {element} html The HTML element
 */
OOMetaBoxDemo.prototype.send_to_editor = function(html)
{
	var fileurl = "";

	if (null !== this.formfield) {
		fileurl = jQuery("img", html).attr("src");

		jQuery("#oomb_image").val(fileurl);

		tb_remove();

		jQuery("html").removeClass("Image");
		formfield = null;
	} else {
		oometaboxdemo.original_send_to_editor(html);
	}
};


/*
 * Initialize on document.ready
 */
jQuery(document).ready(function($)
{
console.log("initializing...");
	// Initialize
	$("#oomb_upload_image").click(function(e) {
console.log("inside click callback");
		oometaboxdemo.upload_media();
	} );

	// user inserts file into post
	oometaboxdemo.original_send_to_editor = window.send_to_editor;
	window.send_to_editor = oometaboxdemo.send_to_editor;
});
