function giveCard(id)
{
	$.ajax({
		type:'GET',
		url: Routing.generate('staff_give_card_ajax', {id:id}),
		before : function () {
		},
		success : function (data) {
			$('#rowcard-'+ id).remove();
		}


	});
}