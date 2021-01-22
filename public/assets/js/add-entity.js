// setup an "add a entity" link
var $addEntityLink = $('<a href="#" class="btn btn-outline-primary add_entity_link mt-3"><i class="fas fa-camera"></i> Nuevo snapshot</a>');
var $newLinkLi = $('<span></span>').append($addEntityLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of entitys
   var $collectionHolder = $('div[data-prototype]');
   
   $collectionHolder.find('div[id]').each(function() {
        addEntityFormDeleteLink($(this));
    });
    
    // add the "add a entity" anchor and li to the entitys ul
    $collectionHolder.append($newLinkLi);
    
    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    
    $addEntityLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        
        // add a new entity form (see code block below)
        addEntityForm($collectionHolder, $newLinkLi);
    });
    
    
});

function addEntityForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');
    
    // get the new index
    var index = $collectionHolder.data('index');
    
    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);
    
    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);
    
    // Display the form in the page in an li, before the "Add a entity" link li
    var $newFormLi = $('<div class="form-entity mt-2"></div>').append(newForm);
    
    // also add a remove button, just for this example
    $newFormLi.append('<a href="#" class="btn btn-danger remove-entity"><i class="far fa-trash-alt"></i> Eliminar</a>');
    
    $newLinkLi.before($newFormLi);
    
    // handle the removal, just for this example
    $('.remove-entity').click(function(e) {
        e.preventDefault();
        
        $(this).parent().remove();
        
        return false;
    });
}

function addEntityFormDeleteLink($entityFormLi) {
    var $removeFormButton = $('<button class="btn btn-danger" type="button"><i class="far fa-trash-alt"></i> Eliminar</button>');
    $entityFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $entityFormLi.remove();
    });
}