// SupportTrio Personalization plugin.
//
// Create a list box which, when you click any of the items in it, will insert that item into
// the editor at the cursor's present point.  If you haven't clicked onto the editor yet, the
// item will be inserted at the first column of the first row of the content.

(function() {
	tinymce.create('tinymce.plugins.ST_Personalize', {
		init : function(ed, url) {
			this.stp_ed = ed;
			ed.addCommand('mceST_Personalize', function() {
			});
		},

		createControl: function(n, cm) {
			if (n == "st_personalize") {
				var box = cm.createListBox("st_personalize_box", {
						ed: tinyMCE.activeEditor,
						title: "something",
						onselect: function(v) {
							this.ed.execCommand("mceInsertContent", false, v);
						}
					});

				//
				box.add("%RELATEDKB%", "%RELATEDKB%");
				return box;
			} else {
				return null;
			}
		},

		getInfo : function() {
			return {
				longname : 'SupportTrio Personalize',
				author : 'Email Software, Inc.',
				authorurl : 'http://www.example.com/',
				infourl : '',
				version : "1.0.0",
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('st_personalize', tinymce.plugins.ST_Personalize);
})();
