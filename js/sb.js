
function imgError(image){
    image.onerror = "";
    image.src = "/sbimgoooooo/img/default_news_error.jpg";
    return true;
}

$( document ).ready(function() {
    
    // top real donor link
    $('.d_host').attr('href',  'http://'+$('.d_host').attr('rel') );
    
    // <add url link to copy post>
    var source_link = '<p>Источник: <a href="' + location.href + '">' + location.href + '</a></p>';
    $(
        function($)
        {
            if (window.getSelection) $('.copy_url').bind(
                'copy',
                function()
                {
                    var selection = window.getSelection();
                    var range = selection.getRangeAt(0);

                    var magic_div = $('<div>').css({ overflow : 'hidden', width: '1px', height : '1px', position : 'absolute', top: '-10000px', left : '-10000px' });
                    magic_div.append(range.cloneContents(), source_link);
                    $('body').append(magic_div);

                    var cloned_range = range.cloneRange();
                    selection.removeAllRanges();

                    var new_range = document.createRange();
                    new_range.selectNode(magic_div.get(0));
                    selection.addRange(new_range);

                    window.setTimeout(
                        function()
                        {
                            selection.removeAllRanges();
                            selection.addRange(cloned_range);
                            magic_div.remove();
                        }, 0
                    );
                }
            );
        }
    );
    // </add url link to copy post>
    
    
    $('.top_slider').bxSlider(
        {
            speed: 800,
            pause: 5000,
            auto: true,
            randomStart: true,
            pager: false
        });
});