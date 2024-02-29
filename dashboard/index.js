///////////////////////////////////////////////////////////////
//                                                           //
//              JQUERY NATIVE FRAMEWORK                      //
//                  Author: Elzgar                           //
//           Version 2.0.1 LTS (27-06-2021)                  //
//                                                           //
///////////////////////////////////////////////////////////////

    /// Local Storage define token and frontend authentication
    var local = localStorage;
    
    /// Quick Item Binding ///
    var click_path;
    var path;
    var main_content;
    const base_url = 'router';
$(document).ready(function() {
    bind_view();
});

    function bind_view() {      
        main_content  = $('div[content-loader="true"]');
        $('[view="true"]').on('click',function(e) {
            var caller = $(this);
            click_path = "path="+$(this).attr('path');
            sender(base_url, "method=view&"+click_path, 'post', 'HTML',function(res) {
                main_content.html(res);
                $('.active').removeClass('active');
                caller.addClass('active');
                bind_form();
            });
        });
        return;
    }
    function bind_form() {
        $('[callrouter="true"]').on('submit',function(e) {
            e.preventDefault();
            path = $(this).attr('action');
            var post_data = $(this).serialize()+"&method=control&path="+path;
            sender(base_url, post_data, 'post', 'JSON', function(res){
                if(res.success == 'true') {
                    if(res.next_action !== null) {
                        location.replace(res.next_action);
                    }
                    
                    Swal.fire({
                        title: "Success!",
                        text: res.msg,
                        icon: 'success',
                        showCancelButton: false,
                    })
                    
                } else {
                    Swal.fire({
                        title: "ERROR!",
                        text: res.msg,
                        icon: 'error',
                        showCancelButton: false,
                    })
                }
            });
        })
        return;
    }
    
    function sender(url, data, type, dataType, callback) {
        $.ajax({
            url: url,
            data: data,
            type: type,
            headers: {
                'Elzgar': 'Its Lord'
            },
            success: function(r, textStatus, request) {
                
                if(dataType == "JSON") {
                    callback(JSON.parse(r));
                } else {
                    callback(r);
                }
                if(request.getResponseHeader('ELZ-CODE') == "ERROR") {
                    r = JSON.parse(r);
                    switch(r.action) {
                        case "login":
                            location.replace('signin');
                            break;
                            
                        case "refresh":
                            location.replace('');
                            break;
                        default: 
                            alert(r.msg);
                    }
                }
            },
            error: function(e) {
                console.log(e);
            }
        })
    }
   