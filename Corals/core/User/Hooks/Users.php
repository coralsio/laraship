<?php

namespace Corals\User\Hooks;


class Users
{
    public function add_cookie_consent()
    {

        $cookieConsent = \Settings::get('cookie_consent', true);
        if (!$cookieConsent) {
            return false;
        }

        $default_config = '
        {
            type: "opt-in",
            position: "bottom",
            palette: { "popup": { "background": "#383b75" }, "button": { "background": "#f1d600", padding: "5px 50px" } }
             
        }';
        $current_request = \Request::route()->getName();
        //$is_auth = in_array($current_request, ['login', 'register']) ? 'true' : 'false';
        $has_token = \Cookie::get('XSRF-TOKEN');;
        $consent_config = \Settings::get('cookie_consent_config', $default_config);
        echo '<script type="text/javascript">
               $(document).ready(function () {
                        
                     var consent_config = ' . $consent_config . ';
                     //var is_auth = "";
                     
                     var has_token = "' . $has_token . '";
                     
                    consent_config.onInitialise =  function (status) {
                      var type = this.options.type;
                      var didConsent = this.hasConsented();
                      if (type == "opt-in" && !didConsent  ) {
                        $("form button[type=submit]").attr("disabled","disabled");
                      }

                    };
                     consent_config.onStatusChange =  function(status, chosenBefore) {
                          var type = this.options.type;
                          var didConsent = this.hasConsented();
                          if (type == "opt-in") {
                                if(didConsent ){
                                    if(has_token){
                                       $("form button[type=submit]").attr("disabled",false);
                                    }else{
                                       site_reload(); 
                                    }
                            
                                }else{
                                    $("form button[type=submit]").attr("disabled","disabled");
                                }
                          }

                      }
                      
                      ;
                     window.cookieconsent.initialise(consent_config);
                        
                 });
        </script>';

        echo \Html::style('assets/corals/plugins/cookieconsent2/cookieconsent.min.css');
        echo \Html::script('assets/corals/plugins/cookieconsent2/cookieconsent.min.js');
        echo \Html::script('assets/corals/plugins/cookieconsent2/js.cookie.min.js');

    }
}

