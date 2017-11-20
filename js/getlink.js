jQuery(document).ready(function () {
    jQuery('form').submit( function(e) {
        sendPost();
        e.preventDefault();
    });
});
    function addLinkDiv(shortLink)
    {
        if (shortLink !== 'duplicate')
            jQuery(
                "<div>\n" +
                "    <p>Ваша ссылка: <a href ='" + shortLink + "'>" + shortLink + "</a></p>\n" +
                "</div>"
            ).insertAfter('#form');
        else
            jQuery("<p>Такое имя ссылки уже занято</p>").insertAfter('#form');

    }
    function sendPost()
    {
        jQuery.post('/?ajax=true', {
            link: jQuery('[name = "link"]').val(),
            hash: jQuery('[name = "hash"]').val(),
            token: jQuery('[name = "token"]').val()
        })
            .done(function(data) {
                var arrjson = JSON.parse(data);
                var shortLink = arrjson['shortLink'];

                jQuery('[name = "token"]').attr({'value':arrjson['token']});
                addLinkDiv(shortLink);
            })
            .fail(function(){
                alert ("Что-то пошло не так. Повторите пожалуйста позже");
            });
    }

