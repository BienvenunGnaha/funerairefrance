$(function(){
    var fixations = [];
    var componets =  [];
    var metaData = [];
    var numberFields = 0;

    $.ajax({
        url: '/component/all',
        type: 'get',
        success: function(data){
            components = data;
            if($('#new-gallery-form').length || $('#new-product-form').length){
                $($('#new-gallery-form .row, #new-product-form .row')[0]).append('<div id="frfu-component-block" class="col-12 p-2 bg-white border">\
                    </div class="row">\
                        <div class="col-6"><span id="frfu-add-component" class="btn btn-primary">Ajouter une fixation</span></div>\
                        <div class="col-6"><span id="frfu-submit-component" class="btn btn-primary">Enregistrer les modifications</span></div>\
                    </div>\
                </div>');
            }

            if($('#edit-gallery-form').length  || $('#edit-product-form').length){
                $($('#edit-gallery-form .row, #edit-product-form .row')[0]).append('<div id="frfu-fixation-block" class="col-12 p-2 bg-white border">\
                    </div class="row">\
                        <div class="col-6"><span id="frfu-add-fixation" class="btn btn-primary">Ajouter une fixation</span></div>\
                        <div class="col-6"><span id="frfu-submit-fixation" class="btn btn-primary">Enregistrer les modifications</span></div>\
                    </div>\
                </div>');

                restore();
            }
            
        },
        error: function(error){
            console.log(error);
        }
    });

    function uniqKey(){
        var random = Math.floor(Math.random() * 1000000); 
        var time = new Date().getTime();  
        return String(random)+String(time);
    }

    function encodeMeta(){
        $('#gallery_metaData').val(JSON.stringify(metaData));
    }

    function restore(){
        md = JSON.parse(JSON.parse($('#gallery_metaData').val()));

        if(md && md.length > 0 && !md[0].id){
            metaData = md;
        
            var count = metaData.length;
            
            for(var j = 0; count > j; j++){
                uniq = metaData[j].uniqId;
                var div = '<div  data-id="'+uniq+'" class="row">'
                var formGroupNumber = '<div class="form-group field-integer">';
                var formGroupText = '<div class="form-group field-text">';
                var formGroupFile = '<div class="form-group field-file">';
                var formGroup = '<div class="col-12 d-flex justify-content-center p-2">';
                var endFormGroup = '</div></div>';
                var formWidget = '<div class="form-widget">';
                var endFormWidget = '</div>';
                var endDiv = '</div>'
                var nameLabel = '<label class="form-control-label">Nom</label>';
                var priceLabel = '<label class="form-control-label">Prix motif</label>';
                var priceproLabel = '<label class="form-control-label">Prix motif(compte professionnel)</label>';
                var fileLabel = '<label class="form-control-label">Photo de la stele</label>';
                var name = '<input  type="text" data-id="'+uniq+'" class="form-control frfu-name-stele" value="'+metaData[j].name+'">';
                var price = '<input type="number" data-id="'+uniq+'" class="form-control frfu-price-stele" value="'+metaData[j].price+'">';
                var pricepro = '<input type="number" data-id="'+uniq+'" class="form-control frfu-pricepro-stele" value="'+metaData[j].pricepro+'">';
                var file = '<input type="file" data-id="'+uniq+'" class="form-control frfu-file-stele " >';
                var del = '<span data-id="'+uniq+'" class="btn btn-danger frfu-remove-stele">Supprimer</span>';

                var formPrice = formGroup+formGroupNumber+priceLabel+formWidget+price+endFormWidget+endFormGroup;
                var formName = formGroup+formGroupText+nameLabel+formWidget+name+endFormWidget+endFormGroup;
                var formPricepro = formGroup+formGroupNumber+priceproLabel+formWidget+pricepro+endFormWidget+endFormGroup;
                var formfile = formGroup+formGroupFile+fileLabel+formWidget+file+endFormWidget+endFormGroup;
                
                var element = div+formName+formPrice+formPricepro+formfile+endDiv+div+del+endDiv;
                $('#edit-gallery-form .row').find('#frfu-stele-block').append(element);
            }
        
        }

    }

    function findMeta(uniqId){
        var count = metaData.length;
        var index = null;
        var found = false;
        for(var i = 0; count > i; i++){
            if(metaData[i].uniqId == uniqId){
                index = i;
                found = true;
            }
        }

        return found ? index : false;
    }

    function findId(id){
        var count = metaData.length;
        var index = null;
        var found = false;
        for(var i = 0; count > i; i++){
            if(metaData[i].id == id){
                index = i;
                found = true;
            }
        }

        return found ? index : false;
    }

    function addField(uniq){
        var div = '<div data-id="'+uniq+'" class="row">'
        var formGroupNumber = '<div class="form-group field-integer">';
        var formGroupText = '<div class="form-group field-text">';
        var formGroupFile = '<div class="form-group field-file">';
        var formGroup = '<div class="col-12 d-flex justify-content-center p-2">';
        var endFormGroup = '</div></div>';
        var formWidget = '<div class="form-widget">';
        var endFormWidget = '</div>';
        var endDiv = '</div>'
        var nameLabel = '<label class="form-control-label">Nom</label>';
        var priceLabel = '<label class="form-control-label">Prix motif</label>';
        var priceproLabel = '<label class="form-control-label">Prix motif(compte professionnel)</label>';
        var fileLabel = '<label class="form-control-label">Photo de la stele</label>';
        var name = '<input  type="text" data-id="'+uniq+'" class="form-control frfu-name-stele p-2">';
        var price = '<input type="number" data-id="'+uniq+'" class="form-control frfu-price-stele p-2">';
        var pricepro = '<input type="number" data-id="'+uniq+'" class="form-control frfu-pricepro-stele p-2">';
        var file = '<input type="file" data-id="'+uniq+'" class="form-control frfu-file-stele p-2">';
        var del = '<span data-id="'+uniq+'" class="btn btn-danger frfu-remove-stele">Supprimer</span>';

        var formPrice = formGroup+formGroupNumber+priceLabel+formWidget+price+endFormWidget+endFormGroup;
        var formName = formGroup+formGroupText+nameLabel+formWidget+name+endFormWidget+endFormGroup;
        var formPricepro = formGroup+formGroupNumber+priceproLabel+formWidget+pricepro+endFormWidget+endFormGroup;
        var formfile = formGroup+formGroupFile+fileLabel+formWidget+file+endFormWidget+endFormGroup;
        
        var element = div+formName+formPrice+formPricepro+formfile+endDiv+div+del+endDiv;
        $('#new-gallery-form .row, #edit-gallery-form .row').find('#frfu-stele-block').append(element);
            
    }

    if($('#edit-gallery-form').length || $('#new-gallery-form').length){
        if($('#new-gallery-form').length){
            $($('#new-gallery-form .row')[0]).append('<div id="frfu-stele-block" class="col-12 p-2 bg-white border">\
                </div class="row">\
                    <div class="col-6"><span id="frfu-add-stele" class="btn btn-primary">Ajouter un motif de stèle</span></div>\
                    <div class="col-6"><span id="frfu-submit-stele" class="btn btn-primary">Enregistrer les modifications</span></div>\
                </div>\
            </div>');
        }

        if($('#edit-gallery-form').length){
            $($('#edit-gallery-form .row')[0]).append('<div id="frfu-stele-block" class="col-12 p-2 bg-white border">\
                </div class="row">\
                    <div class="col-6"><span id="frfu-add-stele" class="btn btn-primary">Ajouter un motif de stèle</span></div>\
                    <div class="col-6"><span id="frfu-submit-stele" class="btn btn-primary">Enregistrer les modifications</span></div>\
                </div>\
            </div>');
            restore();
        }
    }

    $('#new-gallery-form, #edit-gallery-form').delegate('#frfu-submit-stele', 'click', function(){
        encodeMeta();
    });

    $('#new-gallery-form, #edit-gallery-form').delegate('#frfu-add-stele', 'click', function(){
        console.log('caught !!!');
        var uniq = uniqKey();
        addField(uniq);
    });

    $('#new-gallery-form, #edit-gallery-form').delegate('.frfu-price-stele', 'change keyup', function(e){
        var uniq = $(this).attr('data-id');
        var find = findMeta(uniq);
        
        console.log(find);

        if(find !== false){
            metaData[find].price = $(this).val();
        }else{
            metaData.push({'price' : $(this).val(), 'uniqId' : uniq});
        }
    });

    $('#new-gallery-form, #edit-gallery-form').delegate('.frfu-pricepro-stele', 'change keyup', function(e){
        var uniq = $(this).attr('data-id');
        var find = findMeta(uniq);
        
        console.log(find);

        if(find !== false){
            metaData[find].pricepro = $(this).val();
        }else{
            metaData.push({'pricepro' : $(this).val(), 'uniqId' : uniq});
        }
    });//

    $('#new-gallery-form, #edit-gallery-form').delegate('.frfu-remove-stele', 'click', function(e){
        var uniq = $(this).attr('data-id');
        var find = findMeta(uniq);
        var tmp_md = [];
        console.log(find);

        if(find !== false){
            for(var i = 0; metaData.length > i; i++){
                if(metaData[i].uniqId != uniq){
                    tmp_md.push(metaData[i]);
                }
            }
            metaData = tmp_md;
            $('#new-gallery-form, #edit-gallery-form').find($('.row[data-id='+uniq+']')[0]).remove();
            $(this).remove();
        }
    });

    $('#new-gallery-form, #edit-gallery-form').delegate('.frfu-name-stele', 'change keyup', function(e){
        var uniq = $(this).attr('data-id');
        var find = findMeta(uniq);
        
        console.log(find);

        if(find !== false){
            metaData[find].name = $(this).val();
        }else{
            metaData.push({'name' : $(this).val(), 'uniqId' : uniq});
        }
    });

    $('#new-gallery-form, #edit-gallery-form').delegate('.frfu-file-stele', 'change', function(){
        var uniq = $(this).attr('data-id');
        var file = $(this)[0].files[0];
        var find = findMeta(uniq);
        var fileReader = new FileReader();

        fileReader.onload = function(fileLoadedEvent) {
            var srcData = fileLoadedEvent.target.result; // <--- data: base64
            if(find !== false){
                metaData[find].file = srcData;
                console.log(metaData);
            }else{

                metaData.push({'uniqId' : uniq, 'file' : srcData});
                console.log(metaData);
            }
        }
        fileReader.readAsDataURL(file);
        console.log(find);

    });


});