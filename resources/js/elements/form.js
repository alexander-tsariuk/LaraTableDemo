//resources/js/edit-form.js
$(document).ready(function () {
    //фиксируем изменения в форме
    $("body").on("change", "input, select, textarea", function () {
        var el = $(this);
        if (el.hasClass("not_change")) {
            return;
        }
        el.parents("form").addClass("changes");
    });
    //кнопки в модалке
    $("body").on("click", "#edit-notify .btn", function () {
        if ($(this).hasClass("save-changes")) {
            $("form.changes").addClass("save");
            $("form.changes").submit();
        }
        if ($(this).hasClass("not-save")) {
            var link = $(".close-form-edit");
            link.removeClass("close-form-edit");
            link.trigger("click");
            $(".modal").modal("hide");
        }
        $(".modal-backdrop").remove();
        return false;
    });
    //кнопка сохранить
    $("body").on(
        "click",
        ".container_content .apply, .save-changes",
        function () {
            if ($(this).hasClass("disabled")) {
                return false;
            }
            $(this).addClass("disabled", "disabled");
            if ($(this).hasClass("save-changes")) {
                var f = $(".edit-form");
            } else {
                var f = $(this).parents("form");
            }
            $.each(f.find("input[required]"), function (e, v) {
                CustomValidation(v);
            });
            //если мы не прошли проверку в форме
            if (!f.get(0).checkValidity()) {
                $(this).removeClass("disabled");
                $.each(
                    f.find(
                        "input[required]:not([disabled]), select[required]:not([disabled])"
                    ),
                    function (e, v) {
                        if (!$(v).val() || $(v).hasClass("error")) {
                            if (
                                $(v).parents(".tab-pane").not(":visible").length
                            ) {
                                var tab = $(v)
                                    .parents(".tab-pane")
                                    .not(":visible");
                                $(
                                    '.nav-tabs a[href="#' +
                                        tab.attr("id") +
                                        '"]'
                                ).trigger("click");
                            }
                            if ($(v).parents(".modal").not(":visible").length) {
                                $(v).parents(".modal").modal("show");
                            }
                            return false;
                        }
                    }
                );
                if ($(this).hasClass("save-changes")) {
                    f.get(0).reportValidity();
                }
                return;
            }
            f.addClass("load");
            if ($(this).hasClass("save-changes")) {
                $(".modal").modal("hide");
            }
        }
    );
    //кнопка закрыть
    $("body").on("click", ".close-form-edit", function () {
        var e = $(this);
        if (e.parents(".changes").length && $("#edit-notify").length) {
            $("#edit-notify").modal("show");
            return false;
        }
    });
    $("body").on(
        "submit",
        ".container_content form, .modal form",
        function (e) {
            var f = $(this);
            if (f.attr("target") == "_blank" || f.hasClass("notajax")) {
                return;
            }
            $(".head .progress").css("display", "inline-block");
            var stop = false;
            if (f.find(".need-select").length) {
                $.each(f.find(".need-select"), function (i, v) {
                    if (!$(v).next().val()) {
                        $(v).addClass("error");
                        if ($(v).parents(".tab-pane").length) {
                            var tab = $(v).parents(".tab-pane");
                            $(
                                '.nav-tabs a[href="#' + tab.attr("id") + '"]'
                            ).trigger("click");
                        }
                        stop = true;
                    } else {
                        $(v).removeClass("error");
                    }
                });
            }
            if (stop) {
                return false;
            }
            if (f.find(".number").length) {
                $.each(f.find(".number"), function (i, v) {
                    var v = $(v).val().replace("'", "");
                    $(v).val(v);
                });
            }
            var data = f.serialize();
            if (f.hasClass("save")) {
                data += "&save=1";
            }

            if (f.hasClass("reset")) {
                data = "reset_filters=reset_filters";
            }

            var type = f.attr("method") ? f.attr("method") : "POST";
            $.ajax({
                url: f.attr("action"),
                type: type,
                data: data,
                dataType: "json",
                success: function (data) {
                    f.removeClass("load");
                    if (data.redirect) {
                        document.location.href = data.redirect;
                        return;
                    }
                    if (f.parents(".modal").length) {
                        if (f.hasClass("filters")) {
                            if (f.data("target")) {
                                var m = f.parents(".modal");
                            } else {
                                var m = $(".modal:visible");
                                f.parents(".modal").modal("hide");
                            }
                            m.find(".modal-content").replaceWith(
                                $(data.content).find(".modal-content")
                            );
                            afterAjaxLoad();
                            return false;
                        }
                        if (!f.hasClass("submit_close")) {
                            var m = f.parents(".modal");
                            if (m.find(".modal-ajax-content").length) {
                                m.find(".modal-ajax-content").replaceWith(
                                    $(data.content).find(".modal-ajax-content")
                                );
                            } else {
                                f.parents(".modal")
                                    .find(".modal-body")
                                    .replaceWith(
                                        $(data.content).find(".modal-body")
                                    );
                            }
                            data.content = null;
                        } else {
                            f.parents(".modal").modal("hide");
                            $(".modal-backdrop").remove();
                        }
                    }
                    afterAjaxLoad(data);
                },
            });
            return false;
        }
    );
    //кнопка подтверждения в модалке
    $("body").on("click", ".submit-modal", function () {
        var btn = $(this);
        var modal = $(this).parents(".modal");
        var f = modal.find("form");
        if (!f.get(0).checkValidity()) {
            f.get(0).reportValidity();
            return false;
        }
        if (modal.find(".modal_items").length) {
            var child_id = $('input[name="child_id"]').length
                ? $('input[name="child_id"]').val()
                : 0;
            var date = f.find('input[name="date"]').length
                ? f.find('input[name="date"]').val()
                : 0;
            var data = {
                uslugs_price: modal_items,
                child_id: child_id,
                date: date,
            };
            var data = jQuery.param(data);
            f.attr("action", "/client/users?action=set_uslugs_price");
        } else {
            var data = f.serialize();
        }

        sendAjax(f.attr("action"), data, "post").done(function (resp) {
            var e = $(".active-modal-btn");
            var p = e.parents(".form-group");
            if (p.find("select").length) {
                var s = p.find("select");
                var o = $(
                    '<option value="' + resp.id + '">' + resp.name + "</option>"
                );
                s.append(o);
                s.selectpicker("refresh");
                s.selectpicker("val", resp.id);
                modal.modal("hide");

                // if($('name*="mod_"').length){
                $('select[name*="mod_"]').trigger("change");
                // }
            }
            e.removeClass("active-modal-btn");
            if (f.hasClass("update-modal")) {
                if (resp.content) {
                    modal.html(resp.content);
                }
            }
            var target = f.data("target");
            if (!target) {
                target = btn.data("target");
            }

            if (target && resp.content) {
                console.log(target);

                $(target).replaceWith($(resp.content).find(target));
            }

            var event = btn.data("event");
            if (event == "comments" && resp.count) {
                var count = parseInt($(".badge.comment_count").text());
                // console.log(count)
                count = resp.count + count;
                // var badge = $(target).find('.badge.comment_count');

                $(".badge.comment_count").text(count);
            }
            modal.modal("hide");
        });

        return false;
    });
});
//кастомная валидация для инпутов
function CustomValidation(input) {
    var inp = $(input);
    if (inp.hasClass("numeric") || inp.hasClass("number")) {
        input.setCustomValidity("");
        if (!parseFloat(inp.val())) {
            input.setCustomValidity(locale["NUMBER_ERROR"]);
        } else {
            if (
                $("#check_product_counts:visible").length &&
                inp.hasClass("count") &&
                inp.hasClass("error")
            ) {
                input.setCustomValidity(
                    $("#check_product_counts").data("text")
                );
            }
            if ($("#check_sum:visible").length && inp.hasClass("error")) {
                input.setCustomValidity($("#check_sum").data("text"));
            }
        }
    }
}
