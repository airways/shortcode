WysiHat.addButton('shortcode', {
    label:  EE.rte.shortcode.add,

    init: function() {
        this.parent.init.apply(this, arguments);

        this.$dialog = this._setupDialog();
//         this.$error = $('<div class="notice"/>').text(EE.rte.shortcode.dialog.url_required);

         this.origState;
//         this.link_node;

        return this;
    },

    handler: function(state, finalize)
    {
        this.origState = state;
        this.finalize = finalize;
        this.$editor.select();

        // reselect for FF
        this.Selection.set(state.selection);

        var sel     = window.getSelection(),
            link    = true,
            test_el, s_el, e_el;

        // get the elements
        s_el = sel.anchorNode;
        e_el = sel.focusNode;

        this.range = sel.getRangeAt(0);
        this.$dialog.dialog('open');
        this.$dialog.bind('dialogclose', function() {
            setTimeout(function() {
                finalize();
            }, 50);
        });

        return false;

    },

    query: function($editor)
    {
        return this.is('linked');
    },

    _is: function(node, name)
    {
        return (node.tagName && node.tagName.toLowerCase() == name);
    },

    _clearErrors: function()
    {
        this.$dialog.find('.notice').remove();
    },

    _dialogOpen: function()
    {
        this._clearErrors();
//         this._editLinkNode(
//             function($el) {
//                 this.$url.val( $el.attr('href'));
//                 this.$title.val( $el.attr('title'));
//                 this.$external.prop('checked', $el.attr('target') == '_blank');
//                 this.$submit.val(EE.rte.link.dialog.update_link);
//                 $('#rte-remove-link').show();
//             },
//             function() {
//                 this.$submit.val(EE.rte.link.dialog.add_link);
//                 $('#rte-remove-link').hide();
//             }
//         );

//         this.$url.focus();
    },

    _dialogClose: function()
    {
//         var title = $('#rte_shortcode_title-').val();
//
//         if (title != '')
//         {
//             this._editLinkNode(function($el) {
//                 $el.attr('title', title);
//             });
//         }

        // empty the fields
        //this.$dialog.find('input[type=text],select').val('');
        this.$macro.val(this.$macro.find('option:first').attr('value'))
        this.update_settings_form();
    },

    _keyEvent: function(e)
    {
        if (e.which == 13) // enter
        {
            this._validateDialog();
            return false;
        }
    },

    _removeLink: function()
    {
        this.Commands.deleteElement(this.link_node);

        this.$dialog.dialog('close');
        this.Selection.set(this.origState.selection);
    },

    _submit: function()
    {
        this._validateDialog();
    },

    _setupDialog: function()
    {
        var dialog =
            '<div id="rte-shortcode-dialog">' +
            SHORTCODE_SELECT +
            '</p>';
//         $(SHORTCODE_OPTS).each(function() {
//             console.log(this.form);
//             dialog += this.form;
//         });
        dialog +=
            '<p class="buttons">' +
            '   <input class="submit" type="submit" value="Insert Macro" /></p>' +
            '</div>';

        var $dialog = $(dialog), that = this;

        $dialog
            .appendTo('body')
            .dialog({
                width: 400,
                resizable: false,
                position: ["center","center"],
                modal: true,
                draggable: true,
                title: 'Insert Macro',
                autoOpen: false,
                zIndex: 99999,
                open: function() {
                    setTimeout(function() {
                        that._dialogOpen();
                    }, 10)
                },
                close: $.proxy(this, '_dialogClose')
            })
            .on('keypress', 'input', $.proxy(this, '_keyEvent'))                // Close on Enter
//             .on('click', '#rte-remove-macro', $.proxy(this, '_removeMacro'))      // Remove macro
            .on('click', '#rte-shortcode-dialog .submit', $.proxy(this, '_submit')); // Add macro

        this.$macro     = $dialog.find('select[name=macro]');
        this.$submit    = $dialog.find('input.submit');
        this.$external  = $dialog.find('#rte-shortcode-dialog-external');

        
        this.$macro.change(this.update_settings_form);
        $('.edit_settings').hide();
        this.update_settings_form();

        return $dialog;
    },
    
    update_settings_form: function() {
        $('.edit_settings').hide();
        var settings_form = 'macro_'+$('select[name=macro]').val();
        $('#'+settings_form).show();
    },

    _validateDialog: function()
    {
        this._clearErrors();

        var macro = this.$macro.val();
        var out = '['+macro;

        var $settings = $('#macro_'+macro+'.edit_settings input, #macro_'+macro+'.edit_settings select, #macro_'+macro+'.edit_settings textarea');
        $settings.each(function() {
            var setting = $(this).attr('name').replace('macro_'+macro+'_', '');
            out += ' '+setting+'="'+$(this).val()+'"';
        });

        out += ']';

        WysiHat.Commands.insertHTML(out);

        // Finalize undo state and close
        this.finalize();
        this.$dialog.dialog('close');
    }
});
