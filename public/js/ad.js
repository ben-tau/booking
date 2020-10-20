$('#add-image').click(function(){
    // récup le numéro des futurs champs
    const index = $('#widget-count').val();
    
    // récup le prototype des entrées
    const tmpl = $('#annonce_images').data('prototype').replace(/__name__/g,index);

    //console.log(tmpl);
    // injecter le code dans la div

    $('#annonce_images').append(tmpl);

    // on ajoute 1 à la valeur initiale de la collection 

    $('#widget-count').val(parseInt(index)+1);


    deleteButtons();
});

function deleteButtons()
{
    $('button[data-action = "delete"]').click(function(){
        const target = this.dataset.target;

        $(target).remove();
    });
}

deleteButtons();