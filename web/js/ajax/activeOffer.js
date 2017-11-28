function active(id){
    $.ajax({
        type: 'get',
        url: Routing.generate('staff_offers_active_ajax', { id: id }),
        beforeSend: function(){
            console.log('Loading');
        },
        success: function(data){
            button = $("#active-" + id);
            if(data.result){
                button.removeClass('red');
                button.addClass('green');
                button.find("i").remove();
                button.append('<i class=\"material-icons\">check</i>');
            }else{
                button.removeClass('green');
                button.addClass('red');
                button.find("i").remove();
                button.append('<i class=\"material-icons\">close</i>');
            }
        }
    });
}