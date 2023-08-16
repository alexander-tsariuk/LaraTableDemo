//resources/js/table.js
window.addEventListener('contentChanged', event => {
    setAllElements();
});
jQuery(document).ready(function () {
	//чекбоксы
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
    $("body").on("change", '.table-form input[type="checkbox"]', function () {
		var table = $(this).parents('table');
		if (table.data('checked')) {
			var f = table.parents(table.data('checked'));
		} else {
			var f = table.parents("form");
		}
       
        if (f.find('input[type="checkbox"]:checked').length > 0) {
            $(".check-checked").removeClass("disabled");
        } else {
            $(".check-checked").addClass("disabled");
        }
    });

    //кнопка фильтра
    $("body").on("click", ".filter-btn", function () {
        var p = $(this).parents(".table-form");
        console.log(p.length);
        if (!p.length) {
            return;
        }
        var t = p.find(".filters");
        if (t.is(":visible")) {
            t.slideUp();
        } else {
            t.slideDown();
        }
        return false;
    });

    //удаление
    $("body").on("click", ".table-form .delete", function () {
        var f = $(this).prev();
        $(".delete-common-confirm").data("table_form", f.attr("id"));
    });
    $("body").on("click", ".delete-common-confirm", function () {
        var e = $(this);
        if (!e.data("table_form")) {
            return;
        }
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

    //клик по строке открываем запись
    $("body").on("click", ".table-form table:not(.analitics) tr", function (e) {
        var el = $(e.target);
        if (
            e.target.tagName == "A" ||
            el.hasClass("jq-radio") ||
            el.parents(".mods").length
        ) {
            return;
        }

        if (e.target.tagName == "TH") {
            if (el.find("a").length) {
                el.find("a").trigger("click");
            }
            return;
        }
        if (e.target.tagName == "TD" || e.target.tagName == "DIV") {
            if (el.find('input[type="checkbox"]').length) {
                var prop = true;
                if (el.find('input[type="checkbox"]:checked').length) {
                    prop = false;
                }
                el.find('input[type="checkbox"]')
                    .prop("checked", prop)
                    .trigger("change");
                if (prop) {
                    el.parents("tr").addClass("checked");
                } else {
                    el.parents("tr").removeClass("checked");
                }
                return;
            }
        }
        if ($(this).parents(".modal").length) {
            return;
        }
        if (e.target.tagName == "INPUT" || e.target.tagName == "LABEL") {
            var td = el.parents("td");
            var tr = el.parents("tr");
            if (td.find('input[type="checkbox"]:checked').length) {
                tr.addClass("checked");
            } else {
                tr.removeClass("checked");
            }
            return;
        }
        if (el.parents(".mods-table").length) {
            return;
        }
        $(this).find("a:first").trigger("click");
    });

    $("body").on("click", ".modal .documents table tr", function (e) {
        var el = $(this);
        var o = el.data("object");
        if (!o) {
            return;
        }
        var block = $(".doc_base .table_block");
        var id = Math.random().toString(16).slice(2);
        var tr = block.find(".d-none").clone();
        tr.removeClass("d-none");
        tr.find(".number").html(o.number);
        tr.find(".date").html(o.date);
        var name = $(
            '<a target="_blank" href="' +
                o.link +
                '" class="notajax red">' +
                o.type_name +
                "</a>"
        );
        tr.find(".name").html(name);
        tr.find(".name").append(
            $(
                '<input type="hidden" name="bases[' +
                    id +
                    '][base_id]" value="' +
                    o.id +
                    '">'
            )
        );
        tr.find(".name").append(
            $(
                '<input type="hidden" name="bases[' +
                    id +
                    '][base_type]" value="' +
                    o.table +
                    '">'
            )
        );
        $(".doc_base table tbody").append(tr);
        block.show();
        $(".modal").modal("hide");
        return false;
    });



    //поверка что нужно выбрать
    $("body").on("click", ".check-checked", function (e) {
        if ($(this).hasClass("disabled")) {
            var data = {};
            var message = {};
            message.type = "error";
            message.text = locale["CHECK_CHECKED"];
            data.messages = [];
            data.messages.push(message);
            showMsgs(data);
            return false;
        }
    });

    $("body").on("click", ".remove-row", function () {
        if ($(this).parents("tr").length) {
            $(this).parents("tr").remove();
        }
        if ($(this).parents(".item-block").length) {
            $(this).parents(".item-block").remove();
        }
        return false;
    });

    //редактируемая таблица
    $("body").on("click", ".edit-history", function () {
        var tr = $(this).parents("tr");
        tr.addClass("edit");
        return false;
    });
    $("body").on("click", ".close-edit-history", function () {
        var tr = $(this).parents("tr");
        tr.removeClass("edit");
        return false;
    });

    $("body").on("click", ".items-table .save", function () {
        var e = $(this);
        if (e.hasClass("mod_notify")) {
            $("#price-mod-notify").addClass("history");
            $("#price-mod-notify").modal("show");
            return false;
        }

        var tr = $(this).parents("tr");
        var formData = new FormData();
        $.each(tr.find("input, select"), function (i, v) {
            var val = $(v).val();
            if ($(v).attr("type") == "file" && val) {
                val = v.files[0];
            }
            if ($.isArray(val)) {
                $.each(val, function (i2, v2) {
                    formData.append($(v).attr("name"), v2);
                });
            } else {
                formData.append($(v).attr("name"), val);
            }
        });
        formData.append("_method", "PUT");
        sendAjax(e.attr("href"), formData, "post").done(function (resp) {
            if (resp.data) {
                $.each(tr.find("td"), function (i, v) {
                    if (!$(v).find("input, select").length) {
                        return;
                    }
                    if ($(v).find('input[type="file"]').length) {
                        if (resp.data.file) {
                            val = $('<img src="' + data.file + '">');
                        }
                    } else {
                        var val = $(v).find('input[type="text"]').val();
                    }
                    var s = $(v).find("select");
                    if (s.length) {
                        var s_val = s.val();
                        if (!Array.isArray(s_val)) {
                            s_val = [s_val];
                        }

                        val = [];
                        $.each(s_val, function (i2, v2) {
                            val.push(
                                s.find('option[value="' + v2 + '"]').text()
                            );
                        });
                        val = val.join(", ");
                    }
                    $(v).find("span.val").html(val);
                });
                tr.removeClass("edit");
                var s = $(".active-modal-btn")
                    .parents(".bootstrap-select")
                    .find("select");
                var name = resp.data.name;

                if (resp.data.id && s.length && name) {
                    var o = s.find('[value="' + resp.data.id + '"]');
                    if (o.length) {
                        o.attr("data-content", name);
                        s.selectpicker("destroy");
                        s.selectpicker();
                    }
                }
            }
        });

        return false;
    });

    //сброс настроек таблиц
    $("body").on("click", ".reset_table", function () {
		$(this).parents('form').find('[name="reset_table"]').val(1);
    })

    //лимит таблиц
    $("body").on("change", ".limits select", function () {
		$(this).parents('.table-form').find('.table-outer').addClass('load');
    })   
    
    //пагинация таблиц
    $("body").on("click", ".table-form .pagination a", function () {
		$(this).parents('.table-form').find('.table-outer:first').addClass('load');
    })   
})

//фикс для Livewire что бы не менял ссылку в браузере
document.addEventListener('DOMContentLoaded', () => {
    Livewire.hook('component.initialized', (component) => {
		component.effects.path = false;
    });
});
