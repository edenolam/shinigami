
function playerNameAutocompletion(i){
    $("#appbundle_gamesession_gameScores_"+ i +"_card").keyup(function () {
        $("#appbundle_gamesession_gameScores_"+ i +"_card").parent().find('.error-autocomplete').remove();
        if($(this).val().length === 9){
            $.ajax({
                type: 'get',
                url: Routing.generate('staff_autocomplete_customer_nickname_ajax', { cardNumber: $(this).val() }),
                beforeSend: function(){
                    console.log('Loading');
                },
                success: function(data){
                    console.log(data);
                    if(data){
                        $("#appbundle_gamesession_gameScores_"+ i +"_playerName").val(data.playerNickname);
                    }else{
                        $("#appbundle_gamesession_gameScores_"+ i +"_card").parent().append("<p class='error-autocomplete'>Card not found</p>");
                    }
                }
            });
        }
    });
}