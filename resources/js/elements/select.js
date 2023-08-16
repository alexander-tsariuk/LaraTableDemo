//resources/js/select.js
$(document).ready(function() {	
	var all_selected = [];
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
		//автокомплит
		if(s.data("url") && !s.parent().find(".set-autocomplite").length) {
			setSelectAutoComplite(s);
		} else {
			//кнопочки
			setSelectBtns(s);
		}
	});
	$("body").on("loaded.bs.select", function(e) {
		var s = $(e.target);
		s.addClass("not-style");
	});
	//кнопочки в селекте
	$("body").on("click", ".bootstrap-select .btns a", function() {
		$(this).parents(".bootstrap-select").find("select").selectpicker("toggle");
	});	
	
});
$(document).on('removeElement', function(e, el) {
	 var s = $('.active-modal-btn').parents('.bootstrap-select').find('select');
	 if ($(el).hasClass('remove-select') && s.length) {
		s.find('option[value="'+$(el).data('id')+'"]').remove();
		s.selectpicker('refresh');
	 }
	
});
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

function setSelectAutoComplite(s) {
	var target = s.parent().find(".dropdown-menu ul");
	target.find("li:not(.selected)").remove();
	var min = s.data("min") ? s.data("min") : 0;
	s.parent().find('input[type="search"]').autocomplete({
		minLength: min,
		source: function(request, response) {
			var el = $(this.element);
			if(el.hasClass("load")) {
				return false;
			}
			el.addClass("load");
			var val = el.val();
			var data = {
				query: val,
			};
			if($('[name="date"]').length) {
				data.cur_date = $('[name="date"]').val();
			}
			if($('[name="cur_rates"]').length) {
				data.cur_rates = $('[name="cur_rates"]').val();
			}
			if($('[name="type"]').length) {
				data.doc_type = $('[name="type"]').val();
			}
			if($('select.area_id').length) {
				data.area_ids = $('select.area_id').val();
			}			
			if($('select[name="view_id"]').length) {
				var o = getSelectObject($('select[name="view_id"]'));
				if(o && o.product_types) {
					data.types = $.parseJSON(o.product_types);
				}
			}
			var id = s.attr("id");
			var url = s.data("url");
			data.selected = s.val();
			if(!el.attr("data-url")) {
				//s.find('option:not(selected):not([value=""])').remove();
			} else {
				url = el.attr("data-url");
				el.removeAttr("data-url");
				s.find("option.btns").remove();
			}
			target.find(".no-results").remove();
			target.append("<li>" + loader.clone().prop("outerHTML") + "</li>");
			sendAjax(url, data, "get").done(function(resp) {
				el.removeClass("load");
				target.find(".loader").parent().remove();
				if(Object.keys(resp.data).length) {
					$.each(resp.data, function(i, v) {
						var i_id = v.id ? v.id : v.value;
						if(!s.find('option[value="' + i_id + '"]').length) {
							var o = $("<option>").attr("value", i_id).text(v.name);
							if(v.attributes) {
								$.each(v.attributes, function(i2, v2) {
									o.attr(i2, v2);
								});
							}
							o.attr("data-content", v.name);
							o.attr("data-object", JSON.stringify(v));
							if (v.disabled) {
								o.attr("disabled", 'disabled');
							}
						}
						s.append(o);
					});
				} else {
					target.append($('<li class="no-results">').html("Не найдено"));
				}
				s.val(data.selected);
				s.selectpicker("refresh");
				if(resp.next_page_url) {
					var next = $('<a class="next notajax">').text(resp.next_text).attr("href", resp.next_page_url);
					next.click(function() {
						var inp = s.parents(".bootstrap-select").find('input[type="search"]');
						inp.attr("data-url", $(this).attr("href"));
						inp.autocomplete("search", inp.val());
						return false;
					});
					setSelectBtns(s, next);
				}
			});
			return;
		},
	});
	s.parent().find('input[type="search"]').autocomplete("search", "");
	s.addClass("set-autocomplite");
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

function getSelectObject(s) {
	if(!s.val()) {
		return false;
	}
	var o = s.find('option[value="' + s.val() + '"]');
	if(o.length && o.data("object")) {
		return o.data("object");
	}
	return false;
}