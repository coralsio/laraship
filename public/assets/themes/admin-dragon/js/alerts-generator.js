(function($){
    var $alertCode = $('.alert-code'),
        $generateTrigger = $('.generate-alert'),
        $autoClose = $('#autoClose'),
        $lifetime = $('#lifetime'),
        $fade = $('#fade'),
        $fadeDelay = $('#fadeDelay'),
        $template = $('#template'),
        $paragraph = $('#paragraph'),
        $timestamp = $('#timestamp'),
        $imgSrc = $('#imgSrc'),
        $iconClass = $('#iconClass'),
        $buttonSrc = $('#buttonSrc'),
        $buttonText = $('#buttonText');

        $buttonText.prop('disabled', true);
        $buttonSrc.prop('disabled', true);

    $generateTrigger.on('click', generateAlert);
    $fade.on('change', toggleFadeDelay);
    $template.on('change', toggleTemplateOptions);
    $autoClose.on('change', toggleLifetime);

    function generateAlert() {
        var template = $('#template').val(),
            fade = $('#fade').val(),
            autoClose = $('#autoClose').val(),
            $code = ["<p>$('body').xmalert({ <br>"];

        var options = [
            {
                name: 'x',
                type: 'string',
                valid: true
            },
            {
                name: 'y',
                type: 'string',
                valid: true
            },
            {
                name: 'xOffset',
                type: 'number',
                valid: true
            },
            {
                name: 'yOffset',
                type: 'number',
                valid: true
            },
            {
                name: 'alertSpacing',
                type: 'number',
                valid: true
            }
        ];

        if(autoClose == 'true') {
            options
                .push({
                    name: 'lifetime',
                    type: 'number',
                    valid: true
                });
        } else {
            options
                .push({
                    name: 'lifetime',
                    type: 'number',
                    valid: false
                });
        }

        if(fade == 'true') {
            options
                .push({
                    name: 'fade',
                    type: 'boolean',
                    valid: false
                })
            options
                .push({
                    name: 'fadeDelay',
                    type: 'decimal',
                    valid: true
                });
        } else {
            options
                .push({
                    name: 'fade',
                    type: 'boolean',
                    valid: true
                })
            options
                .push({
                    name: 'fadeDelay',
                    type: 'number',
                    valid: false
                });
        }

         if(autoClose == 'true') {
            options
                .push({
                    name: 'autoClose',
                    type: 'boolean',
                    valid: false
                })
         } else {
            options
                .push({
                    name: 'autoClose',
                    type: 'boolean',
                    valid: true
                })
         }

         options.push({
            name: 'template',
            type: 'string',
            valid: true
         });

         options.push({
            name: 'title',
            type: 'string',
            valid: true
         });

         if(template == 'item') {
            options.push({
                name: 'paragraph',
                type: 'string',
                valid: false
            });

             options.push({
                name: 'timestamp',
                type: 'string',
                valid: true
            });

            options.push({
                name: 'imgSrc',
                type: 'string',
                valid: true
            });

            options.push({
                name: 'iconClass',
                type: 'string',
                valid: true
            });

            options.push({
                name: 'buttonSrc',
                type: 'array',
                valid: false
            });

            options.push({
                name: 'buttonText',
                type: 'string',
                valid: false
            });
         } else {
            options.push({
                name: 'paragraph',
                type: 'string',
                valid: true
            });
         }

         if(template == 'review') {
             options.push({
                name: 'timestamp',
                type: 'string',
                valid: true
            });

            options.push({
                name: 'imgSrc',
                type: 'string',
                valid: false
            });

            options.push({
                name: 'iconClass',
                type: 'string',
                valid: false
            });

            options.push({
                name: 'buttonSrc',
                type: 'array',
                valid: true
            });

            options.push({
                name: 'buttonText',
                type: 'string',
                valid: false
            });
         }

         if(template == 'survey') {
             options.push({
                name: 'timestamp',
                type: 'string',
                valid: true
            });

            options.push({
                name: 'imgSrc',
                type: 'string',
                valid: true
            });

            options.push({
                name: 'iconClass',
                type: 'string',
                valid: false
            });

            options.push({
                name: 'buttonSrc',
                type: 'array',
                valid: true
            });

            options.push({
                name: 'buttonText',
                type: 'string',
                valid: true
            });
         }

         if(template == 'messageSuccess' || 
            template == 'messageInfo' || 
            template == 'messageError') {
            options.push({
                name: 'timestamp',
                type: 'string',
                valid: false
            });

            options.push({
                name: 'imgSrc',
                type: 'string',
                valid: false
            });

            options.push({
                name: 'iconClass',
                type: 'string',
                valid: false
            });

            options.push({
                name: 'buttonSrc',
                type: 'array',
                valid: false
            });

            options.push({
                name: 'buttonText',
                type: 'string',
                valid: false
            });
         }

        for(var i = 0; i < options.length; i++) {
            var val = $('#'+options[i].name).val(),
                line = "\t"+options[i].name+": ";
            
            // string to boolean
            if(options[i].type == 'boolean')
                val = (val == 'true');

            if(options[i].type == 'number')
                val = parseInt(val);

            options[i].val = val;

            if(options[i].type == 'array') {
                var arrayItems = val.split(','),
                    val = '',
                    valArray = [];

                val += '[ ';
                for(var j = 0; j < arrayItems.length; j++) {
                    val += '\''+arrayItems[j]+'\'';
                    valArray.push(arrayItems[j]);
                    if( j < arrayItems.length - 1 ) val += ',';
                }
                 val += ' ]';

                 options[i].val = valArray;
            }

            if( options[i].name == 'title' ||
                options[i].name == 'paragraph' ||
                options[i].name == 'buttonText' ) {
                val = val.replace(/</g, "&lt;").replace(/>/g, "&gt;")
            }

            if(!options[i].valid) continue;
            
            if(options[i].type == 'string') 
                line += "'"+val+"'";
            else 
                line += val;
            
            line += ",<br>";

            $code.push(line);
        }

        $code.push("});</p>");

        $alertCode
            .empty()
            .append($code.join(""));

        $('body').xmalert({
            x: options[0].val,
            y: options[1].val,
            xOffset: options[2].val,
            yOffset: options[3].val,
            alertSpacing: options[4].val,
            lifetime: options[5].val,
            fade: options[6].val,
            fadeDelay: options[7].val,
            autoClose: options[8].val,
            template: options[9].val,
            title: options[10].val,
            paragraph: options[11].val,
            timestamp: options[12].val,
            imgSrc: options[13].val,
            iconClass: options[14].val,
            buttonSrc: options[15].val,
            buttonText: options[16].val
        });
    }

    function toggleFadeDelay() {
        if($fade.val() != 'true') {
            $fadeDelay.prop('disabled', true);
        } else {
            $fadeDelay.prop('disabled', false);
        }
    }

    function toggleLifetime() {
        if($autoClose.val() != 'true') {
            $lifetime.prop('disabled', true);
        } else {
            $lifetime.prop('disabled', false);
        }
    }

    function toggleTemplateOptions() {
        if($template.val() == 'item') {
            $paragraph.prop('disabled', true);
            $buttonSrc.prop('disabled', true);
            $buttonText.prop('disabled', true);
        } else {
            $paragraph.prop('disabled', false);
        }

        if( $template.val() == 'messageSuccess' || 
            $template.val() == 'messageInfo' || 
            $template.val() == 'messageError') {
            $timestamp.prop('disabled', true);
            $imgSrc.prop('disabled', true);
            $iconClass.prop('disabled', true);
            $buttonSrc.prop('disabled', true);
            $buttonText.prop('disabled', true);
        } else {
            $timestamp.prop('disabled', false);
            $imgSrc.prop('disabled', false);
            $iconClass.prop('disabled', false);
        }

        if( $template.val() == 'review' ) {
            $buttonSrc.prop('disabled', false);
            $imgSrc.prop('disabled', true);
            $iconClass.prop('disabled', true);
            $buttonText.prop('disabled', true);
        }

        if( $template.val() == 'survey' ) {
            $buttonSrc.prop('disabled', false);
            $iconClass.prop('disabled', true);
            $buttonText.prop('disabled', false);
        }
    }
})(jQuery);