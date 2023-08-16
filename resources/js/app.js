//аякс setup
$.ajaxSetup({
	headers: {
		"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
	},
	error: function(xhr, status, error) {
		$(".loader").remove();
		$(".load").removeClass('load');
		$(".apply").removeClass("disabled");
		if(typeof xhr.responseJSON != 'undefined') {
			alert(xhr.responseJSON.message)
		} else {
			alert(error);
		}
	},
});
$(document).ajaxComplete(function(event, xhr) {
	if(typeof xhr.responseJSON != 'undefined') {
		showMsgs(xhr.responseJSON);
	}
	$(".loader").remove();
	$(".load").removeClass('load');
	$(".apply").removeClass("disabled");
	setAllElements();
});
window.addEventListener('contentChanged', event => {
	setAllElements();
});


function sendAjax(url, data, method, el) {
	method = method ? method : "POST";
	var options = {
		url: url,
		type: method,
		data: data,
		dataType: "json",
		success: function(resp) {
			$(".loader").remove();
		},
	}
	if(typeof data == 'object') {
		if(data instanceof FormData) {
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
		if(data.content) {
			$("#main-content").html(data.content);
			console.log(data.content)
			if(data.title) {
				document.title = data.title;
				doc_title = data.title;
				window.history.pushState({}, data.title, data.url);
			}
		}
	}
	$('.modal').modal('hide');
}

function showMsgs(data) {
	if(data.messages && data.messages.length) {
		$.each(data.messages, function(i, v) {
			window.flasher.flash(v.type, v.text, v.title, {
				timeout: v.time,
			});
		});
	}
}
showMsgs({});
//select
function setSelect() {
	var els = $("select[readonly]");
	if(els.length) {
		$.each(els, function(i, v) {
			var s = $(v);
			var v = s.val() ? s.val() : "";
			s.after('<input type="hidden" name="' + s.attr("name") + '" value="' + v + '">');
			s.attr("disabled", "disabled");
			s.removeAttr("name");
		});
	}
	$("select:not(.not-style)").selectpicker({});
}

function setSelectBtns(s, next) {
	var btns = [];
	var p = s.parent();
	p.find(".btns").remove();
	if(next) {
		btns.push(next);
	}
	if(s.data("list")) {
		var n = $("<a href='" + s.data("list") + "' class='notajax open-modal list'>" + s.data("list_lbl") + "</a>");
		btns.push(n);
	}
	if(s.data("new")) {
		var n = $("<a href='" + s.data("new") + "' class='notajax open-modal new'>" + s.data("new_lbl") + "</a>");
		btns.push(n);
	}
	if(btns.length) {
		$.each(btns, function(i, v) {
			p.find("ul.dropdown-menu").append($('<li class="btns">').append(v));
		});
	}
}
//маска для цен и кол-ва
function setNumberMask(el) {
	if(!el) {
		el = $("body");
	}
	el.find("input.number:not(.has_mask)").inputmask("currency", {
		radixFocus: false,
		placeholder: "0",
		prefix: "",
		groupSeparator: " ",
		rightAlign: true,
		digits: 2,
		onBeforeMask: function(value, opts) {
			$(this.el).addClass("has_mask");
		},
	});
	el.find("input.numeric:not(.has_mask)").inputmask("numeric", {
		prefix: "",
		groupSeparator: "",
		alias: "numeric",
		placeholder: "0",
		autoGroup: !0,
		digits: 2,
		digitsOptional: !1,
		clearMaskOnLostFocus: !1,
		rightAlign: false,
		clearMaskOnLostFocus: false,
		onBeforeMask: function(value, opts) {
			$(this.el).addClass("has_mask");
		},
	});
}

function setAllElements() {
	setSelect();
	setNumberMask();
}
$(document).ready(function() {
	setAllElements();
	$("select").on("refreshed.bs.select", function(e, clickedIndex, isSelected, previousValue) {
		var btns = $(e.target).parent().find("a.btns");
		if(btns.length) {
			$.each(btns, function(i, v) {
				$(v).parents("li").addClass("btns");
				$(v).replaceWith($(v).html());
			});
		}
	});
	//селекты
	$("body").on("show.bs.select", function(e) {
		var s = $(e.target);
		setSelectBtns(s);
	});
	$("body").on("loaded.bs.select", function(e) {
		var s = $(e.target);
		s.addClass("not-style");
	});
	//кнопочки в селекте
	$("body").on("click", ".bootstrap-select .btns a", function() {
		$(this).parents(".bootstrap-select").find("select").selectpicker("toggle");
	});
	//открытие модалки
	$("body").on("click", ".open-modal", function(e) {
		var el = $(this);
		if(el.hasClass("disabled")) {
			return false;
		}
		var data = {};
		data.ajax = 1;
		data.modal = 1;
		if(el.parents(".modal").length) {
			var target = $('<div class="modal fade" role="dialog"></div>');
			el.parents(".modal .active-modal-btn").removeClass("active-modal-btn");
		} else {
			var target = $("#myModal");
			$(".active-modal-btn").removeClass("active-modal-btn");
		}
		if(!el.hasClass("not_set_active")) {
			el.addClass("active-modal-btn");
		}
		if(el.hasClass("form-data")) {
			data = $(this).parents("form").serializeObject();
			data.ajax = 1;
			delete data.action;
		}
		sendAjax($(this).attr("href"), data, "get").done(function(resp) {
			if(resp.content) {
				target.html(resp.content);
				if(el.hasClass("backdrop")) {
					target.modal({
						backdrop: "static",
					});
				}
				target.modal("show");
			}
		});
		return false;
	});
	//кнопка фильтра
	$("body").on("click", ".filter-btn", function() {
		var p = $(this).parents(".table-form");
		if(!p.length) {
			return;
		}
		var t = p.find(".filters");
		if(t.is(":visible")) {
			t.slideUp();
		} else {
			t.slideDown();
		}
		return false;
	});
	//кнопка подтверждения в модалке
	$("body").on("click", ".submit-modal", function() {
		var btn = $(this);
		var modal = $(this).parents(".modal");
		var f = modal.find("form");
		var data = f.serialize();
		sendAjax(f.attr("action"), data, "post").done(function(resp) {
			var e = $(".active-modal-btn");
			var p = e.parents(".form-group");
			if(p.find("select").length) {
				var s = p.find("select");
				var o = $('<option value="' + resp.data, id + '">' + resp.data.name + "</option>");
				s.append(o);
				s.selectpicker("refresh");
				s.selectpicker("val", resp.data.id);
				modal.modal("hide");
			}
			modal.modal("hide");
		});
		return false;
	});
	$("body").on("submit", ".container form", function(e) {
		var f = $(this);
		if(f.attr("target") == "_blank" || f.hasClass("notajax")) {
			return;
		}
		var data = f.serialize();

		var type = f.attr("method") ? f.attr("method") : "POST";
		sendAjax(f.attr("action"), data, type).done(function(resp){
			afterAjaxLoad(resp);
		})
		return false;
	});
	
	$("body").on("click", ".checkall", function() {
		var t = $(this).parents("table:first");
		if($(this).is(":checked")) {
			$.each(t.find("tbody tr:visible"), function(i, v) {
				$(v).find('input[type="checkbox"]:first').prop("checked", true).trigger("change");
				$(v).addClass("checked");
			});
		} else {
			$.each(t.find("tbody tr:visible"), function(i, v) {
				$(v).find('input[type="checkbox"]:first').prop("checked", false).trigger("change");
				$(v).removeClass("checked");
			});
		}
	});	
    //удаление
    $("body").on("click", ".table-form .delete", function () {
        var f = $(this).prev();
        $(".delete-common-confirm").data("table_form", f.attr("id"));
    });
    $("body").on("click", ".delete-common-confirm", function () {
        var e = $(this);
        var modal = $(this).parents(".modal");
        var form = $("#" + e.data("table_form"));
        var action = form.attr("action");
        var table = form.parents(".table-form").find("tbody");
        var inps = table.find("input:checked");
        if (!inps.length) {
            return false;
        }
        modal.addClass("load");
        //e.addClass('disabled');
        var ids = [];
        $.each(inps, function (i, v) {
            ids.push($(v).val());
        });
        form.attr("action", action.replace("0", ids.join("|")));
        form.submit();
        form.attr("action", action);
        return false;
    });	
    
	//ссылки
	$("body").on("click", "#main-content a:not(.page-link):not(.notajax)", function() {
		var el = $(this);
		if(el.attr("href") == "#" || el.attr("target") == "_blank" || el.data("toggle") == "tab" || el.parents(".items_block").length || el.parents(".typeahead").length || el.parents(".bootstrap-select").length || (el.hasClass("close-form-edit") && el.parents(".changes").length)) {
			return;
		}
		if(el.parents(".pages").length) {
			var data = $(".table-form").serialize();
		}
		sendAjax(el.attr("href"), data, "get", el).done(function(resp) {
			var m = el.parents(".modal");
			if(m.length) {
				if(m.find(".modal-ajax-content").length) {
					m.find(".modal-ajax-content").replaceWith($(resp.content).find(".modal-ajax-content"));
				} else {
					m.find(".modal-body").replaceWith($(resp.content).find(".modal-body"));
				}
				return;
			}
			afterAjaxLoad(resp);
		});
		return false;
	});    
});