$(function(){
    var fixations = [];
    var metaData = [];
    var numberFields = 0;

    function uniqKey(){
        var random = Math.floor(Math.random() * 1000000); 
        var time = new Date().getTime();  
        return String(random)+String(time);
    }

    function encodeMeta(){
        $('#gallery_metaData, #product_metaData').val(JSON.stringify(metaData));
    }

    function restore(){
        var md = JSON.parse(JSON.parse($('#gallery_metaData, #product_metaData').val()));
        
        if(md && md[0] && md[0].id ){
            metaData = md;
            var count = metaData.length;
        
            for(var j = 0; count > j; j++){
                uniq = metaData[j].uniqId;
                price = metaData[j].price;
                pricepro = metaData[j].pricepro;
                var div = '<div data-id="'+uniq+'" class="row"><div class="col-12 d-flex justify-content-center p-2"><div class="form-group"><div class="form-widget">'
                var endDiv = '</div></div></div></div>'
                var select = '<select data-id="'+uniq+'" class="form-control frfu-select-fixation" required>';
                var options = '<option value="">Selectionner une fixation</option>';
                var endSelect = '</select>';
                var file = '<input type="file" data-id="'+uniq+'" class="form-control frfu-file-fixation p-2" required>';
                var price = '<input type="number" data-id="'+uniq+'" class="form-control frfu-price-fixation p-2" value="'+price+'" required>';
                var pricepro = '<input type="number" data-id="'+uniq+'" class="form-control frfu-pricepro-fixation p-2" value="'+pricepro+'" required>';
                var del = '<span data-id="'+uniq+'" class="btn btn-danger frfu-remove-fixation">Supprimer</span>';
                var countFix = fixations.length;

                if(countFix > 0 && countFix > numberFields ){
                    for(var i = 0; i < countFix; i++){
                        var selected = '';
                        if(fixations[i].id == metaData[j].id){
                            selected = 'selected';
                        }
                        options += '<option value="'+fixations[i].id+'" '+selected+'>'+fixations[i].name+'</option>'
                    }

                    var element = '';
                    if($('#edit-product-form, #new-product-form').length){
                        element = div+select+options+endSelect+endDiv+div+file+endDiv+div+del+endDiv+div+price+endDiv+div+pricepro+endDiv;
                    }
                    else if($('#new-gallery-form, #edit-gallery-form').length){
                        element = div+select+options+endSelect+endDiv+div+file+endDiv+div+del+endDiv;
                    }
                    
                    $($('#edit-gallery-form .row, #edit-product-form .row')[0]).find('#frfu-fixation-block').append(element);
                    numberFields++;
                }
            }
        }
    }

    function findMeta(uniqId){
        
        var index = null;
        var found = false;
        if(metaData){
            var count = metaData.length;
            for(var i = 0; count > i; i++){
                if(metaData[i].uniqId == uniqId){
                    index = i;
                    found = true;
                }
            }
        }
        return found ? index : false;
    }

    function findId(id){
        
        var index = null;
        var found = false;
        if(metaData){
            var count = metaData.length;
            for(var i = 0; count > i; i++){
                if(metaData[i].id == id){
                    index = i;
                    found = true;
                }
            }
        }
        return found ? index : false;
    }

    function addField(uniq){
        var div = '<div data-id="'+uniq+'" class="row"><div class="col-12 d-flex justify-content-center p-2"><div class="form-group"><div class="form-widget">'
        var endDiv = '</div></div></div></div>'
        var select = '<select data-id="'+uniq+'" class="form-control frfu-select-fixation" required>';
        var options = '<option value="">Selectionner une fixation</option>';
        var endSelect = '</select>';
        var file = '<input type="file" data-id="'+uniq+'" class="form-control frfu-file-fixation p-2" required>';
        var price = '<input type="number" data-id="'+uniq+'" class="form-control frfu-price-fixation p-2" required>';
        var pricepro = '<input type="number" data-id="'+uniq+'" class="form-control frfu-pricepro-fixation p-2" required>';
        var del = '<span data-id="'+uniq+'" class="btn btn-danger frfu-remove-fixation">Supprimer</span>';
        var countFix = fixations.length;
        if(countFix > 0 && countFix > numberFields ){
            for(var i = 0; i < countFix; i++){
                options += '<option value="'+fixations[i].id+'">'+fixations[i].name+'</option>'
            }
            var element = '';
            if($('#edit-product-form, #new-product-form').length){
                element = div+select+options+endSelect+endDiv+div+file+endDiv+div+del+endDiv+div+price+endDiv+div+pricepro+endDiv;
            }
            else if($('#new-gallery-form, #edit-gallery-form').length){
                element = div+select+options+endSelect+endDiv+div+file+endDiv+div+del+endDiv;
            }
            $('#new-gallery-form .row, #edit-gallery-form .row, #new-product-form .row, #edit-product-form .row').find('#frfu-fixation-block').append(element);
            numberFields++;
        }
    }

    if($('#edit-gallery-form').length || $('#new-gallery-form').length || $('#edit-product-form').length || $('#new-product-form').length){
        $.ajax({
            url: '/fixation/all',
            type: 'get',
            success: function(data){
                fixations = data;
                if($('#new-gallery-form').length || $('#new-product-form').length){
                    $($('#new-gallery-form .row, #new-product-form .row')[0]).append('<div id="frfu-fixation-block" class="col-12 p-2 bg-white border">\
                        </div class="row">\
                            <div class="col-6"><span id="frfu-add-fixation" class="btn btn-primary">Ajouter une fixation</span></div>\
                            <div class="col-6"><span id="frfu-submit-fixation" class="btn btn-primary">Enregistrer les modifications</span></div>\
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
                console.log(fixations);
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    $('#new-gallery-form, #edit-gallery-form, #edit-product-form, #new-product-form').delegate('#frfu-submit-fixation', 'click', function(){
        console.log('Hi !!');
        encodeMeta();
    });

    $('#new-gallery-form, #edit-gallery-form, #edit-product-form, #new-product-form').delegate('#frfu-add-fixation', 'click', function(){
        console.log('Hi !!');
        var uniq = uniqKey();
        addField(uniq);

    });

    $('#new-gallery-form, #edit-gallery-form, #edit-product-form, #new-product-form').delegate('.frfu-select-fixation', 'change', function(e){
        var uniq = $(this).attr('data-id');
        var find = findMeta(uniq);
        
        console.log(find);

        if(find !== false){
            if(findId($(this).val()) == false){
                metaData[find].id = $(this).val();
            }else{
                $(this).prop('selectedIndex', 0);
                alert('fixation déjà attribué')
            }
            
            console.log(metaData);
        }else{
            if(findId($(this).val()) == false){
                metaData.push({'id' : $(this).val(), 'uniqId' : uniq});
            }else{
                $(this).prop('selectedIndex', 0);
                alert('fixation déjà attribué')
            }
            console.log(metaData);
        }
    });

    $('#new-gallery-form, #edit-gallery-form').delegate('.frfu-remove-fixation', 'click', function(e){
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

    $('#edit-product-form, #new-product-form').delegate('.frfu-price-fixation', 'change keyup', function(e){
        var uniq = $(this).attr('data-id');
        var find = findMeta(uniq);
        
        console.log(find);

        if(find !== false){
            metaData[find].price = $(this).val();
        }else{
            metaData.push({'uniqId' : uniq, 'price' : $(this).val()});
        }
    });

    $('#edit-product-form, #new-product-form').delegate('.frfu-pricepro-fixation', 'change keyup', function(e){
        var uniq = $(this).attr('data-id');
        var find = findMeta(uniq);
        
        console.log(find);

        if(find !== false){
            metaData[find].pricepro = $(this).val();
        }else{
            metaData.push({'uniqId' : uniq, 'pricepro' : $(this).val()});
        }
    });

    $('#new-gallery-form, #edit-gallery-form, #edit-product-form, #new-product-form').delegate('.frfu-file-fixation', 'change', function(){
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