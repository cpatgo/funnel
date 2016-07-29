// 1-2-All Personalization plugin.
//
// Create a list box which, when you click any of the items in it, will insert that item into
// the editor at the cursor's present point.  If you haven't clicked onto the editor yet, the
// item will be inserted at the first column of the first row of the content.

(function() {
	tinymce.create('tinymce.plugins.OTA_Template', {
		/*
		init : function(ed, url) {
			this.stp_ed = ed;
			ed.addCommand('mceOTA_Personalize', function() {
			});
		},
		*/

		createControl: function(n, cm) {
			if ( n == "ota_template" ) {
				var c = cm.createMenuButton(
					'ota_template',
					{
						title : editorTemplateTitle,
						image : 'images/editor_template.gif',
						icons : false
					}
				);
				c.onRenderMenu.add(ac_editor_template_render);
				// Return the new menu button instance
				return c;
			} else {
				return null;
			}
		},

		getInfo : function() {
			return {
				longname : '1-2-All Template',
				author : 'Email Software, Inc.',
				authorurl : 'http://www.example.com/',
				infourl : '',
				version : "1.0.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('ota_template', tinymce.plugins.OTA_Template);
})();
