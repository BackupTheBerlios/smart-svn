function TinyMCE_noneditable_getInfo() {
	return {
		longname : 'Non editable elements',
		author : 'Moxiecode Systems',
		authorurl : 'http://tinymce.moxiecode.com',
		infourl : 'http://tinymce.moxiecode.com/tinymce/docs/plugin_noneditable.html',
		version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
	};
};

function TinyMCE_noneditable_initInstance(inst) {
	tinyMCE.importCSS(inst.getDoc(), tinyMCE.baseURL + "/plugins/noneditable/css/noneditable.css");

	// Ugly hack
	if (tinyMCE.isMSIE5_0)
		tinyMCE.settings['plugins'] = tinyMCE.settings['plugins'].replace(/noneditable/gi, 'Noneditable');

	if (tinyMCE.isGecko) {
		tinyMCE.addEvent(inst.getDoc(), "keyup", TinyMCE_noneditable_fixKeyUp);
//		tinyMCE.addEvent(inst.getDoc(), "keypress", TinyMCE_noneditable_selectAll);
//		tinyMCE.addEvent(inst.getDoc(), "mouseup", TinyMCE_noneditable_selectAll);
	}
}

function TinyMCE_noneditable_fixKeyUp(e) {
	var inst = tinyMCE.selectedInstance;
	var sel = inst.getSel();
	var rng = inst.getRng();
	var an = sel.anchorNode;

	// Move cursor outside non editable fields
	if ((e.keyCode == 38 || e.keyCode == 37 || e.keyCode == 40 || e.keyCode == 39) && (elm = TinyMCE_noneditable_isNonEditable(an)) != null) {
		rng = inst.getDoc().createRange();
		rng.selectNode(elm);
		rng.collapse(true);
		sel.removeAllRanges();
		sel.addRange(rng);
		tinyMCE.cancelEvent(e);
	}
}

function TinyMCE_noneditable_selectAll(e) {
	var inst = tinyMCE.selectedInstance;
	var sel = inst.getSel();
	var doc = inst.getDoc();

	if ((elm = TinyMCE_noneditable_isNonEditable(sel.focusNode)) != null) {
		inst.selectNode(elm, false);
		tinyMCE.cancelEvent(e);
		return;
	}

	if ((elm = TinyMCE_noneditable_isNonEditable(sel.anchorNode)) != null) {
		inst.selectNode(elm, false);
		tinyMCE.cancelEvent(e);
		return;
	}
}

function TinyMCE_noneditable_isNonEditable(elm) {
	var editClass = tinyMCE.getParam("noneditable_editable_class", "mceItemEditable");
	var nonEditClass = tinyMCE.getParam("noneditable_noneditable_class", "mceItemNonEditable");

	if (!elm)
		return;

	do {
		var className = elm.className ? elm.className : "";

		if (className.indexOf(editClass) != -1)
			return null;

		if (className.indexOf(nonEditClass) != -1)
			return elm;
	} while (elm = elm.parentNode);

	return null;
}

function TinyMCE_noneditable_cleanup(type, content, inst) {
	switch (type) {
		case "insert_to_editor_dom":
			var nodes = tinyMCE.getNodeTree(content, new Array(), 1);
			var editClass = tinyMCE.getParam("noneditable_editable_class", "mceItemEditable");
			var nonEditClass = tinyMCE.getParam("noneditable_noneditable_class", "mceItemNonEditable");

			for (var i=0; i<nodes.length; i++) {
				var elm = nodes[i];

				// Convert contenteditable to classes
				var editable = tinyMCE.getAttrib(elm, "contenteditable");
				if (new RegExp("true|false","gi").test(editable))
					TinyMCE_noneditable_setEditable(elm, editable == "true");

				if (tinyMCE.isMSIE) {
					var className = elm.className ? elm.className : "";

					if (className.indexOf(editClass) != -1)
						elm.contentEditable = true;

					if (className.indexOf(nonEditClass) != -1)
						elm.contentEditable = false;
				}
			}

			break;

		case "insert_to_editor":
			if (tinyMCE.isMSIE) {
				var editClass = tinyMCE.getParam("noneditable_editable_class", "mceItemEditable");
				var nonEditClass = tinyMCE.getParam("noneditable_noneditable_class", "mceItemNonEditable");

				content = content.replace(new RegExp("<(.*?)class=\"(.*?)(" + editClass + ")(.*?)\"(.*?)>", "gi"), '<$1class="$2$3$4" contenteditable="true"$5>');
				content = content.replace(new RegExp("<(.*?)class=\"(.*?)(" + nonEditClass + ")(.*?)\"(.*?)>", "gi"), '<$1class="$2$3$4" contenteditable="false"$5>');
			}

			break;

		case "get_from_editor_dom":
			if (tinyMCE.getParam("noneditable_leave_contenteditable", false)) {
				var nodes = tinyMCE.getNodeTree(content, new Array(), 1);

				for (var i=0; i<nodes.length; i++)
					nodes[i].removeAttribute("contenteditable");
			}

			break;
	}

	return content;
}

function TinyMCE_noneditable_setEditable(elm, state) {
	var editClass = tinyMCE.getParam("noneditable_editable_class", "mceItemEditable");
	var nonEditClass = tinyMCE.getParam("noneditable_noneditable_class", "mceItemNonEditable");

	var className = elm.className ? elm.className : "";

	if (className.indexOf(editClass) != -1 || className.indexOf(nonEditClass) != -1)
		return;

	if ((className = tinyMCE.getAttrib(elm, "class")) != "")
		className += " ";

	className += state ? editClass : nonEditClass;

	elm.setAttribute("class", className);
	elm.className = className;
}
