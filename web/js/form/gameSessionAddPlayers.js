$(document).ready(function() {
    $('#appbundle_gamesession_numberPlayers').keyup(function(e) {
        e.preventDefault();

        $('#appbundle_gamesession_gameScores').empty();

        var numberPlayer = $('#appbundle_gamesession_numberPlayers').val();
        var gamescoreList = jQuery('#appbundle_gamesession_gameScores');
        for(var i = 0; i < numberPlayer; i++) {
            var newWidget = gamescoreList.attr('data-prototype');
            newWidget = newWidget.replace(/__name__/g, i);
            var newLi = jQuery('<li class="row card"></li>').html(newWidget);
            newLi.appendTo(gamescoreList);

            playerNameAutocompletion(i);
        }

    });
});