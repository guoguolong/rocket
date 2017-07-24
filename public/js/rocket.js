var html = '';
var baseUrl = 'http://rocket-api.kaulware.me';

html+= '<div class="goods">\
<style>\
rocket {\
    display: block;\
}\
rocket .goods {\
    height: 360px;\
    background: #efefef;\
    text-align: center;\
    overflow: auto;\
}\
</style>\
<img src="'+ baseUrl + '/img/tshirt.png" />\
<div>\
    <a href="'+ baseUrl +'"><span>【购买】</span></a>\
</div>\
</div>\
';

$('rocket').append(html);