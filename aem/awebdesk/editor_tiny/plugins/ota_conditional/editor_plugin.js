// 1-2-All Conditional Content plugin.
//
// Create a list box which, when you click any of the items in it, will insert that item into
// the editor at the cursor's present point.  If you haven't clicked onto the editor yet, the
// item will be inserted at the first column of the first row of the content.

(function() {
	tinymce.create('tinymce.plugins.OTA_ConditionalContent', {
		/*
		init : function(ed, url) {
			this.stp_ed = ed;
			ed.addCommand('mceOTA_ConditionalContent', function() {
			});
		},
		*/

		createControl: function(n, cm) {
			if ( n == "ota_conditional" ) {
				var c = cm.createButton(
					'ota_conditional',
					{
						title : editorConditionalTitle,
						image : 'images/editor_conditional.gif',
						icons : false,
						onclick: ac_editor_conditional_click
					}
				);
				// Return the new button instance
				return c;
			} else {
				return null;
			}
		},

		getInfo : function() {
			return {
				longname : '1-2-All Conditional Content',
				author : 'Email Software, Inc.',
				authorurl : 'http://www.example.com/',
				infourl : '',
				version : "1.0.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('ota_conditional', tinymce.plugins.OTA_ConditionalContent);
})();
