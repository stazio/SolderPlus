$(function() {

    $('#side-menu').metisMenu();

});

//Loads the correct sidebar on window load
$(function() {

    $(window).bind("load", function() {
        console.log($(this).width());
        if ($(this).width() < 768) {
            $('div.sidebar-collapse').addClass('collapse')
        } else {
            $('div.sidebar-collapse').removeClass('collapse')
        }
    })
});

//Collapses the sidebar on window resize
$(function() {

    $(window).bind("resize", function() {
        console.log($(this).width());
        if ($(this).width() < 768) {
            $('div.sidebar-collapse').addClass('collapse')
        } else {
            $('div.sidebar-collapse').removeClass('collapse')
        }
    })
});


// Does an AJAX call to the platform and responds appropriately

function ajax(data){
$.ajax({
        type: data.type ? data.type : "POST",
        url: data.url,
        data: data.data,
        success: function (response) {
            if (response.status === "success") {
                if (data.success)
                    data.success(response);

                if (data.growlSuccess)
                    $.jGrowl(data.growlSuccess(data), {group: 'alert-success'});
            }else if (response.status === "warning") {
                if (data.warning)
                    data.warning(response);

                if (data.growlWarning)
                    $.jGrowl(data.growlWarning(data), {group: 'alert-warning'});
            }else {
                if (data.error)
                    data.error(null, 'error', data.reason);
                if (data.growlError)
                    $.jGrowl(data.growlError(data), {group: 'alert-danger'});
                $.jGrowl('Error: ' + data.reason, {group: 'alert-danger'});
            }
        },
        error: data.error ? data.error : function (xhr, textStatus, errorThrown) {
            textStatus = {
                "timeout" : "Connection Timeout",
                "error" : "Error",
                "abort" : "Connection Aborted",
                "parseerror" : "Invalid Response from Server"
            }[textStatus];
            $.jGrowl(textStatus + ': ' + errorThrown, {group: 'alert-danger'});
        }
    })
}