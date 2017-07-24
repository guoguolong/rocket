var html = '';
var baseUrl = '';

html+= '<div class="goods">';
html+= '<style>';
html+= 'rocket {';
html+= '    display: block;';
html+= '}';
html+= 'rocket .goods {';
html+= '    height: 360px;';
html+= '    background: #efefef;';
html+= '    text-align: center;';
html+= '    overflow: auto;';
html+= '}';
html+= '</style>';
html+= '<img src="/img/tshirt.png" />';
html+= '<div>';
html+= '    <a href="/"><span>【购买】</span></a>';
html+= '</div>';
html+= '</div>';
html+= '';

$('rocket').append(html);