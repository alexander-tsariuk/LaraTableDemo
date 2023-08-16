$(document).ready(function() {
	$("body").on("change", 'input[type="file"]', function() {
		var e = $(this);
		var p = e.parents(".upload-file-block");
		if(!p.length) {
			return;
		}
		var form = e.parents("form");
		var files = [];
		if(e.attr("multiple")) {
			$.each(e.prop("files"), function(i, v) {
				files.push(v);
			});
		} else {
			files.push(e.prop("files")[0]);
		}
		var modal = e.parents(".modal");
		if(modal.length) {
			var block = modal.find("#image-block_" + e.data("type"));
		} else {
			var block = $("#image-block_" + e.data("type"));
		}
		block.append(loader.clone());
		var total = files.length;
		var action = "upload";
		var id = form.find("#item_id").val();
		if(e.parents(".comments").length) {
			id = form.find('input[name="id"]').val();
		}
		$.each(files, function(i, v) {
			var file_data = v;
			var form_data = new FormData();
			form_data.append("file", file_data);
			if(id) {
				form_data.append("id", id);
			}
			form_data.append("type", e.data("type"));
			form_data.append("count", block.find(".file").length);
			if(e.parents(".products").length && id) {
				form_data.append("_method", "PUT");
			}
			$.ajax({
				url: form.attr("action"),
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: "post",
				dataType: "json",
				success: function(resp) {
					var f = block.find(".file:first").clone();
					var link = f.find("a").attr("href", resp.file);
					f.find("input").val(resp.file_name);
					f.find("input.path").val(resp.file).removeAttr("disabled");
					if(!id) {
						f.find("input").removeAttr("disabled");
					}
					if(!f.find(".glyphicon").length) {
						if(resp.resize) {
							link.attr("data-fancybox", "images");
							f.find("img").attr("src", resp.resize);
							$('[data-fancybox="images"]').fancybox({});
						} else {
							if(resp.icon) {
								link.html('<svg class="' + resp.icon + '"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#' + resp.icon + '"></use><svg>');
								link.attr("target", "_blank");
							}
						}
					}
					block.find(".results").append(f);
					f.show();
					if(i + 1 == total) {
						block.find(".loader").remove();
					}
				},
			});
		});
	});
	
	$("body").on("click", ".remove-file", function() {
		var e = $(this);
		var form = e.parents("form");
		var inp = e.parent().find("input");
		var action = "removefile";
		var id = form.find("#item_id").val();
		if(e.parents(".comments").length) {
			action = "comment_removefile";
			id = form.find('input[name="id"]').val();
		}
		if(e.parents(".products").length) {
			var _method = "PUT";
		}
		$.ajax({
			url: form.attr("action"),
			data: {
				id: id,
				file: inp.val(),
				type: inp.data("type"),
				action: action,
				_method: _method,
			},
			type: "post",
			dataType: "json",
			success: function(resp) {
				if(resp.remove) {
					e.parents(".file").remove();
				}
			},
		});
	});
});