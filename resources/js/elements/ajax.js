//resources/js/ajax.js
//аякс setup
$.ajaxSetup({
	headers: {
		"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
	},
	error: function(xhr, status, error) {
		$(".loader").remove();
		$(".load").removeClass('load');
	    $(".apply").removeClass("disabled");
		if (typeof xhr.responseJSON != 'undefined') {
			alert(xhr.responseJSON.message)
		} else {
			alert(error);
		}
	},
});

$(document).ajaxComplete(function(event, xhr) {
	if (typeof xhr.responseJSON != 'undefined') {
		showMsgs(xhr.responseJSON);
	}
	$(".loader").remove();
	$(".load").removeClass('load');
    $(".apply").removeClass("disabled");
    setAllElements();
});
function sendAjax(url, data, method, el) {
	if(el) {
		if(el.hasClass("preload")) {
			el.addClass("active_load");
			el.parent().append($('<div class="loader_sm">'));
		}
	}

	method = method ? method : "POST";
	var options = {
		url: url,
		type: method,
		data: data,
		dataType: "json",
		success: function(resp) {
			$(".loader").remove();
			if(el) {
				el.removeClass("active_load");
				$(".loader_sm").remove();
			}

		},
		error: function(err) {
			if(el) {
				el.removeClass("active_load");
				$(".loader_sm").remove();
			}
		},
	}
	if (typeof data == 'object') {
		if (data instanceof FormData) {
			options.contentType = false;
			options.processData = false;
		}
	}
	return $.ajax(options);
};

function afterAjaxLoad(data) {
	if(data) {
		if(data.redirect) {
			window.location = data.redirect;
			return;
		}
		if(data.table && $("table.data").length) {
			$(".container_content table.data").replaceWith($(data.content).find("table.data"));
		} else {
			if(data.content) {
				var active_tab = false;
				if($(".container_content .nav-tabs").length) {
					var active_tab = $(".container_content .nav-tabs .active").index();
				}
				$(".container_content").html(data.content);
				if(active_tab) {
					$(".container_content .nav-tabs li:eq(" + active_tab + ") a").trigger("click");
				} else {}
				$("body").removeClass("modal-open");
				if(data.title) {
					document.title = data.title;
					doc_title = data.title;
					window.history.pushState({}, data.title, data.url);
				}
			}
		}
		if(check_new) {
			var el = $(".container_content").find("#" + check_new);
			if(el.length) {
				el.trigger("click");
			}
			check_new = false;
		}
	}
	//если есть модалка
	var modal_load = $(".modal.load");

	if(modal_load.length) {
		modal_load.removeClass("load");
		modal_load.find("button").removeClass("disabled");
		modal_load.modal("hide");
	}
	$(".dropdown-toggle").dropdown();

}

