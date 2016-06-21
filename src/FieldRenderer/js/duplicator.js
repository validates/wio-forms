$("div[data-wio-forms='previousProgram']").parent().uniqueId();
$("div[data-wio-forms='previousRole']").parent().uniqueId();

var one = "#"+$("div[data-wio-forms='previousProgram']").parent().attr('id');
var two = "#"+$("div[data-wio-forms='previousRole']").parent().attr('id');

$(one +','+ two).wrapAll('<div class="toDuplicate">');

$(document).ready(function() {
    $("#duplicate").on('click', function() {
        $(".toDuplicate")
            .clone()
            .removeClass('toDuplicate')
            .insertAfter('.toDuplicate')
            .find("input").attr("name",function(i,oldVal) {
            return oldVal.replace(/\[(\d+)\]/,function(_,m){
                return "[" + (+m + 1) + "]";
            });
        });
        return false;
    });
});
