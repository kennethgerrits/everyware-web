$(document).ready(function () {
    let lastScriptSrc = null;

    $(".js-crud-table").on('click', function (e) {
        let object = $(e.target);

        if (object.is("td")) {
            object = object.parent();
        }

        if (object.is("tr")) {
            window.location = object.data("url");
        }
    });

    $(".js-partial-table").on('click', function (e) {
        let object = $(e.target);

        if (lastScriptSrc !== null) {
            console.log(lastScriptSrc);
            $(document).find('script[src="' + lastScriptSrc + '"]').remove();
        }

        if (object.is("td")) {
            object = object.parent();
        }

        if (object.is("tr")) {
            $('.tab-pane').removeClass('last-pane');
            $('.tab-pane.active').addClass('last-pane');
            $('.tab-pane').removeClass('active');
            $('#crud-pane').tab('show');

            $.ajax({
                url: object.data("url"),
                method: 'get',
                success: function (response) {
                    $('#crud-body').empty();
                    $('#crud-body').append(response);
                    lastScriptSrc = object.data("script");
                    loadScript(object.data("script"), function () {
                    });
                }
            })
        }
    });

    $(".js-partial-create").on('click', function (e) {
        let object = $(e.target);

        if (object.is("i")) {
            object = object.parent();
        }

        if (lastScriptSrc !== null) {
            $(document).find('script[src="' + lastScriptSrc + '"]').remove();
        }

        $('.tab-pane').removeClass('last-pane');
        $('.tab-pane.active').addClass('last-pane');
        $('.tab-pane').removeClass('active');
        $('#crud-pane').tab('show');

        $.ajax({
            url: object.data("url"),
            method: 'get',
            success: function (response) {
                $('#crud-body').empty();
                $('#crud-body').append(response);
                lastScriptSrc = object.data("script");
                loadScript(object.data("script"), function () {
                });
            }
        })
    });

    $("#crud-cancel-btn").on('click', function () {
        $('.tab-pane').removeClass('active');
        $('#crud-body').empty();
        $('.last-pane').tab('show');
    });
});

function loadScript(url, callback) {

    var script = document.createElement("script")
    script.type = "text/javascript";

    if (script.readyState) { //IE
        script.onreadystatechange = function () {
            if (script.readyState == "loaded" || script.readyState == "complete") {
                script.onreadystatechange = null;
                callback();
            }
        };
    } else { //Others
        script.onload = function () {
            callback();
        };
    }
    console.log(url);
    script.src = url;
    document.getElementsByTagName("head")[0].appendChild(script);
}
