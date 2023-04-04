$(function () {
    $(".js-table").each(function (t, a) {
        var e = $(a).find(".js-table-row"), o = $(a).find(".js-call");
        $(a).find(".js-table-scroll");
        e.off("openBottom").on("click.openBottom", function (t) {
            o.is(t.target) ? alert("popup") : ($(this).toggleClass("is-active"), $(this).find(".js-table-bottom").slideToggle(300))
        })
    }), $(".js-filter").each(function (t, a) {
        $(a).find(".js-filter-list").mCustomScrollbar({
            axis: "y",
            contentTouchScroll: !0,
            scrollInertia: 120
            // setHeight: 70
        })
    }), $(".js-select").each(function (t, a) {
        $(a).select2({minimumResultsForSearch: -1, width: "100%"})
    }), $(".js-tabs").each(function (t, a) {
        var o = $(a).find(".js-tab-trigger"), n = $(a).find(".js-tab");
        o.off("tab").on("click.tab", function (t) {
            var a = $(this).data("tab"), e = n.filter("[data-tab=" + a + "]");
            $(this).addClass("is-active"), o.not($(this)).removeClass("is-active"), e.fadeIn(300), n.not(e).fadeOut(0)
        })
    }), $(".js-switcher").each(function (t, a) {
        $(a).off("switch").on("click.switch", function (t) {
            $(this).toggleClass("is-active")
        })
    }), $(".js-filter-button").each(function (t, a) {
        var e = $(".js-filter-modal");
        $(a).on("click", function (t) {
            e.hasClass("is-active") ? e.removeClass("is-active").fadeOut("300") : e.addClass("is-active").fadeIn("300")
        })
    }), $(".js-chat-button").each(function (t, a) {
        var e = $(".js-chat");
        $(a).on("click", function (t) {
            e.hasClass("is-active") ? e.removeClass("is-active").fadeOut("300") : e.addClass("is-active").fadeIn("300")
        })
    }), $(".js-napr-button").each(function (t, a) {
        var e = $(".js-napr-modal");
        $(a).on("click", function (t) {
            e.hasClass("is-active") ? e.removeClass("is-active").fadeOut("300") : e.addClass("is-active").fadeIn("300")
        })
    }), $("[data-fancybox]").fancybox({autoFocus: !1, touch: !1}), $(".js-settings").each(function (t, a) {
        var e = $(a).find(".js-new-org"), o = $(a).find(".js-add-org"), n = $(a).find(".js-new-clear"),
            s = $(a).find(".js-new-input");
        o.off("newOrg").on("click.newOrg", function (t) {
            t.preventDefault(), e.slideToggle(300), $(this).toggleClass("is-active")
        }), n.off("clear").on("click.clear", function (t) {
            t.preventDefault(), s.val("")
        })
    }), $(".js-org-block").each(function (t, a) {
        var e = $(a).find(".js-org-del"), o = $(a).find(".js-org-edit"), n = $(a).find(".js-org-input");
        e.off("delOrg").on("click.delOrg", function (t) {
            t.preventDefault(), $(a).remove()
        }), o.off("editOrg").on("click.editOrg", function (t) {
            t.preventDefault(), n.removeAttr("disabled").select(), $(this).parent().addClass("is-active")
        })
    }), $(".js-org-choise").each(function (t, a) {
        var e = $(a).find(".js-button-org"), o = $(a).find(".js-modal-org");
        e.off("choiseModal").on("click.choiseModal", function (t) {
            o.fadeToggle(300).toggleClass("is-active")
        })
    }), $(".js-header").each(function (t, a) {
        var e = $(a).find(".js-nav-burger"), o = $(a).find(".js-nav");
        e.off("menu").on("click.menu", function (t) {
            $(this).toggleClass("is-active"), o.toggleClass("is-active")
        })
    }), $(".js-nav-item").each(function (t, a) {
        var e = $(a).find(".js-nav-title"), o = $(a).find(".js-nav-list");
        e.off("list").on("click.list", function (t) {
            o.slideToggle(300)
        })
    }), $(".js-file-input").each(function (t, a) {
        var e = $(a).find(".js-file-button"), o = $(a).find(".js-file-text"), n = $(a).find(".js-file");
        e.off("file").on("click.file", function (t) {
            t.preventDefault(), n.click()
        }), n.change(function (t) {
            o.text($(this)[0].files[0].name)
        })
    }), $(".js-timetable").each(function (t, a) {
        $(a).mCustomScrollbar({axis: "yx", contentTouchScroll: !0, scrollInertia: 120})
    }), Chart.pluginService.register({
        beforeRender: function (e) {
            e.config.options.showAllTooltips && (e.pluginTooltips = [], e.config.data.datasets.forEach(function (t, a) {
                e.getDatasetMeta(a).data.forEach(function (t, a) {
                    e.pluginTooltips.push(new Chart.Tooltip({
                        _chart: e.chart,
                        _chartInstance: e,
                        _data: e.data,
                        _options: e.options.tooltips,
                        _active: [t]
                    }, e))
                })
            }), e.options.tooltips.enabled = !1)
        }, afterDraw: function (t, a) {
            if (t.config.options.showAllTooltips) {
                if (!t.allTooltipsOnce) {
                    if (1 !== a) return;
                    t.allTooltipsOnce = !0
                }
                t.options.tooltips.enabled = !0, Chart.helpers.each(t.pluginTooltips, function (t) {
                    t.initialize(), t.update(), t.pivot(), t.transition(a).draw()
                }), t.options.tooltips.enabled = !1
            }
        }
    }), $(".js-dg1").each(function (t, a) {
        var e = $(a);
        new Chart(e, {
            type: "doughnut",
            data: {
                datasets: [{data: [768, 4272], backgroundColor: ["#FD5577", "#7D7F9B"]}],
                labels: ["не явилось, чел", "явилось, чел"]
            },
            options: {
                showAllTooltips: !0,
                legend: {
                    display: !0,
                    labels: {
                        fontColor: "#616161",
                        fontSize: 17,
                        fontFamily: 'Lato, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Ubuntu, Arial, sans-serif',
                        padding: 20
                    }
                },
                tooltips: {
                    intersect: !0,
                    bodyFontSize: 14,
                    bodyFontStyle: "bold",
                    xPadding: 12,
                    yPadding: 12,
                    callbacks: {
                        label: function (t, a) {
                            for (var e = a.datasets[t.datasetIndex].data[t.index], o = a.labels[t.index], n = 0, s = 0; s < a.datasets[t.datasetIndex].data.length; s++) n += a.datasets[t.datasetIndex].data[s];
                            return o + ": " + e + ",  " + (100 * e / n).toFixed(2) + "%"
                        }
                    }
                }
            }
        })
    }), $(".js-dg2").each(function (t, a) {
        var e = $(a);
        new Chart(e, {
            type: "doughnut",
            data: {
                datasets: [{data: [130, 637, 345], backgroundColor: ["#FDAE55", "#0FC789", "#0FA9C7"]}],
                labels: ["ЗНО верифицировано с нарушением срока", "отменено подозрений", "ЗНО верифицировано в срок"]
            },
            options: {
                showAllTooltips: !0,
                legend: {
                    display: !0,
                    labels: {
                        fontColor: "#616161",
                        fontSize: 15,
                        fontFamily: 'Lato, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Ubuntu, Arial, sans-serif',
                        padding: 20
                    }
                },
                tooltips: {
                    intersect: !0,
                    bodyFontSize: 13,
                    bodyFontStyle: "bold",
                    xPadding: 12,
                    yPadding: 12,
                    callbacks: {
                        label: function (t, a) {
                            for (var e = a.datasets[t.datasetIndex].data[t.index], o = a.labels[t.index], n = 0, s = 0; s < a.datasets[t.datasetIndex].data.length; s++) n += a.datasets[t.datasetIndex].data[s];
                            return o + ": " + e + ",  " + (100 * e / n).toFixed(2) + "%"
                        }
                    }
                }
            }
        })
    }), $(".js-dg-turn").each(function (t, a) {
        var e = $(a);
        new Chart(e, {
            type: "doughnut",
            data: {
                datasets: [{data: [130, 43, 11], backgroundColor: ["#0FA9C7", "#FDAE55", "#0FC789"]}],
                labels: ["всего, чел", "более 30 дней, чел", "более 60 дней, чел"]
            },
            options: {
                showAllTooltips: !0,
                legend: {
                    display: !1,
                    labels: {
                        fontColor: "#616161",
                        fontSize: 15,
                        fontFamily: 'Lato, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Ubuntu, Arial, sans-serif',
                        padding: 20
                    }
                },
                tooltips: {
                    intersect: !0,
                    bodyFontSize: 13,
                    bodyFontStyle: "bold",
                    xPadding: 12,
                    yPadding: 12,
                    callbacks: {
                        label: function (t, a) {
                            for (var e = a.datasets[t.datasetIndex].data[t.index], o = a.labels[t.index], n = 0, s = 0; s < a.datasets[t.datasetIndex].data.length; s++) n += a.datasets[t.datasetIndex].data[s];
                            return o + ": " + e + ",  " + (100 * e / n).toFixed(2) + "%"
                        }
                    }
                }
            }
        })
    }), $(".js-dg-slots").each(function (t, a) {
        var e = $(a);
        new Chart(e, {
            type: "doughnut",
            data: {
                datasets: [{data: [54, 39, 23, 9], backgroundColor: ["#4A4D76", "#FD5577", "#0FA9C7", "#85CDB5"]}],
                labels: ["детское отделение", "офтальмологическое №1", "офтальмологическое №3", "офтальмологическое №2"]
            },
            options: {
                showAllTooltips: !0,
                legend: {
                    display: !1,
                    labels: {
                        fontColor: "#616161",
                        fontSize: 15,
                        fontFamily: 'Lato, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Ubuntu, Arial, sans-serif',
                        padding: 20
                    }
                },
                tooltips: {
                    intersect: !0,
                    bodyFontSize: 13,
                    bodyFontStyle: "bold",
                    xPadding: 12,
                    yPadding: 12,
                    callbacks: {
                        label: function (t, a) {
                            for (var e = a.datasets[t.datasetIndex].data[t.index], o = a.labels[t.index], n = 0, s = 0; s < a.datasets[t.datasetIndex].data.length; s++) n += a.datasets[t.datasetIndex].data[s];
                            return o + ": " + e + ",  " + (100 * e / n).toFixed(2) + "%"
                        }
                    }
                }
            }
        })
    }), $(".js-dg-transfers").each(function (t, a) {
        var e = $(a);
        new Chart(e, {
            type: "doughnut",
            data: {
                datasets: [{data: [41, 32], backgroundColor: ["#4A4D76", "#FD5577"]}],
                labels: ["офтальмологическое №2", "офтальмологическое №1"]
            },
            options: {
                showAllTooltips: !0,
                legend: {
                    display: !1,
                    labels: {
                        fontColor: "#616161",
                        fontSize: 15,
                        fontFamily: 'Lato, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Ubuntu, Arial, sans-serif',
                        padding: 20
                    }
                },
                tooltips: {
                    intersect: !0,
                    bodyFontSize: 13,
                    bodyFontStyle: "bold",
                    xPadding: 12,
                    yPadding: 12,
                    callbacks: {
                        label: function (t, a) {
                            for (var e = a.datasets[t.datasetIndex].data[t.index], o = a.labels[t.index], n = 0, s = 0; s < a.datasets[t.datasetIndex].data.length; s++) n += a.datasets[t.datasetIndex].data[s];
                            return o + ": " + e + ",  " + (100 * e / n).toFixed(2) + "%"
                        }
                    }
                }
            }
        })
    }), $(".js-calendar-scroll").each(function (t, a) {
        $(a).mCustomScrollbar({axis: "y", contentTouchScroll: !0, scrollInertia: 120, setHeight: 200})
    }), $(".js-chat-list").each(function (t, a) {
        $(a).mCustomScrollbar({axis: "y", contentTouchScroll: !0, scrollInertia: 120, setHeight: 366})
    }), $(".js-attach-table").each(function (t, a) {
        $(a).mCustomScrollbar({axis: "y", contentTouchScroll: !0, scrollInertia: 120, setHeight: 235})
    })
});

// /**
//  * Расширяем JQuery
//  */
// jQuery.fn.extend({
//     /**
//      * Прокрутка вниз
//      */
//     scrollBottom: function() {
//         return this.scrollTop(this.scrollHeight);
//     },
//     // check: function() {
//     //     return this.each(function() { this.checked = true; });
//     // },
//     // uncheck: function() {
//     //     return this.each(function() { this.checked = false; });
//     // }
// });
/**
 * Расширяем JQuery
 */
jQuery.fn.extend({
    /**
     * Прокрутка вниз
     */
    scrollBottom: function() {
        this.each (function (index) {
            let object = $(this);
            // console.log(this);
            // console.log(object);

            let height = object.prop('scrollHeight');
            //Высота нулевая - вероятно объект скрыт - применим специальную функцию
            if (height == 0) {
                height = object.getHiddenElementHeight(this);
            }
            // object.prop('visibility','hidden');
            // console.log(height);
            object.scrollTop(height);
            // object.pro('visibility','hidden');
            // return $(this);
        })
    },

    /**
     * Найти высоту скрытого элемента. Создаётся клон объекта, а потом удаляется
     * @param element
     * @returns {*|jQuery}
     */
    getHiddenElementHeight: function(element){
        let tempId = 'tmp-'+Math.floor(Math.random()*99999);//generating unique id just in case
        $(element).clone()
            .css('position','absolute')
            .css('height','auto')
            .css('width','1000px')
            //inject right into parent element so all the css applies (yes, i know, except the :first-child and other pseudo stuff..
            .appendTo($('body'))
            .css('left','-10000em')
            .css('z-index','-1')
            .addClass(tempId).show()

        let h = $('.'+tempId).height()
        // console.log($('.'+tempId));
        // console.log(h);
        $('.'+tempId).remove()
        return h;
    }
    // check: function() {
    //     return this.each(function() { this.checked = true; });
    // },
    // uncheck: function() {
    //     return this.each(function() { this.checked = false; });
    // }
});
