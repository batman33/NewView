/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.config.filebrowserBrowseUrl = '/ckfinder/ckfinder.html';
CKEDITOR.config.filebrowserImageBrowseUrl = '/ckfinder/ckfinder.html?type=Images';
CKEDITOR.config.filebrowserFlashBrowseUrl = '/ckfinder/ckfinder.html?type=Flash';
CKEDITOR.config.filebrowserUploadUrl = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
CKEDITOR.config.filebrowserImageUploadUrl = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
CKEDITOR.config.filebrowserFlashUploadUrl = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
 
CKEDITOR.editorConfig = function(config) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.enterMode = CKEDITOR.ENTER_BR;
	config.shiftEnterMode = CKEDITOR.ENTER_P;
	
	config.resize_enabled = true;
	config.htmlEncodeOutput = false;
	config.entities = false;

    config.contentsLangDirection = 'ltr';
	// config.contentsLangDirection = 'rtl';
	config.skin = 'moono';
	// config.skin = 'kama';
	config.toolbar = 'full';
	// config.toolbar = 'Custom';
 
config.toolbar_Custom =
[
   { name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
   { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
   { name: 'forms', items : [ 'Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField' ] },
   { name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] },
   '/',
   { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
   { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
   { name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
   { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
   '/',
   { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
   { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
   { name: 'colors', items : [ 'TextColor','BGColor' ] }
];

};
