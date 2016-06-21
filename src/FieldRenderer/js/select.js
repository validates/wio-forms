$('select[name^='+parentSelector+']').change(function() {
    var selectorValue = $(this).val();
    var options = jQuery.parseJSON(lists);
    addDynamicOptions(options[selectorValue]);
});

function addDynamicOptions(options)
{
    var child = $('select[name^='+childSelector+']');
    child.html("");
    for (var key in options) {
        if (options.hasOwnProperty(key)) {
            child.append("<option value=\""+key+"\">"+options[key]+"</option>");
        }
    }
}