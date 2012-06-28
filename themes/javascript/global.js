var shortcode_mcp = {

    bind_events: function() {
        $('select[name=type]').change(shortcode_mcp.update_macro_type)
    },
    
    update_macro_type: function() {
        var $type = $('select[name=type]');
        if($type.length > 0)
        {
            var type = $('select[name=type]').val();
            switch(type)
            {
                case 'text':
                    var $textarea = $('textarea[name=value]');
                    if($textarea.length > 0)
                    {
                        var val = $textarea.val();
                        $($textarea.parents()[0]).append('<input type="text" name="value" />');
                        $('input[name=value]').val(val);
                        $textarea.remove();
                        
                    }
                    break;
                case 'textarea':
                    var $text = $('input[name=value]');
                    if($text.length > 0)
                    {
                        var val = $text.val();
                        $($text.parents()[0]).append('<textarea name="value" rows="30"></textarea>');
                        $('textarea[name=value]').val(val);
                        $text.remove();
                        
                    }
                    break;
            }
        }
    }

}


$(document).ready(function() {
    shortcode_mcp.bind_events();
    shortcode_mcp.update_macro_type();
});