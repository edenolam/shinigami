
function generateCard()
{
    var center = $("#appbundle_card_center").val();
    console.log(center);
    $.ajax({
        type: 'get',
        url: Routing.generate('staff_generate_new_card_ajax', { center: center }),
        beforeSend: function(){
            console.log('Loading');
        },
        success: function(data){
            $('#appbundle_card_number').val(data.number);
            $('#appbundle_card_modulo').val(data.modulo);
        }
    });
}