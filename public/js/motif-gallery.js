$(function(){
    var fixations = [];
    var metaData = {};
    var numberFields = 0;

    function uniqKey(){
        var random = Math.floor(Math.random() * 1000000); 
        var time = new Date().getTime();  
        return String(random)+String(time);
    }

    function inputChange(file, key){
        //var find = findMeta(uniq);
        var fileReader = new FileReader();

        fileReader.onload = function(fileLoadedEvent) {
            var srcData = fileLoadedEvent.target.result; // <--- data: base64
                if(key == 'black'){
                    metaData.black = srcData;
                }else if(key == 'white'){
                    metaData.white = srcData;
                }else if(key == 'gold'){
                    metaData.gold = srcData;
                }
                console.log(metaData); 
                
        }
        fileReader.readAsDataURL(file);
    }

    function encodeMeta(){
        $('#motifgallery_metaData').val(JSON.stringify(metaData));
    }

    /*function restore(){
        metaData = JSON.parse(JSON.parse($('#motif_gallery_metaData').val()));
        
        var count = metaData.length;
        
        for(var j = 0; count > j; j++){
            uniq = metaData[j].uniqId;
            var div = '<div class="row"><div class="col-12 d-flex justify-content-center p-2"><div class="form-group"><div class="form-widget">'
            var endDiv = '</div></div></div></div>'
            var select = '<select data-id="'+uniq+'" class="form-control frfu-select-fixation">';
            var options = '<option value="">Selectionner une fixation</option>';
            var endSelect = '</select>';
            var file = '<input type="file" data-id="'+uniq+'" class="form-control frfu-file-fixation p-2">';
            var del = '<span id="frfu-remove-fixation" data-id="'+uniq+'" class="btn btn-danger">Supprimer</span>';
            var countFix = fixations.length;

            if(countFix > 0 && countFix > numberFields ){
                for(var i = 0; i < countFix; i++){
                    var selected = '';
                    if(fixations[i].id == metaData[j].id){
                        selected = 'selected';
                    }
                    options += '<option value="'+fixations[i].id+'" '+selected+'>'+fixations[i].name+'</option>'
                }
                var element = div+select+options+endSelect+endDiv+div+file+endDiv+div+del+endDiv;
                $($('#edit-gallery-form .row')[0]).find('#frfu-fixation-block').append(element);
                numberFields++;
            }
        }

    }*/

    /*function findMeta(uniqId){
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
    }*/

    function addField(){
        var div = '<div class="row"><div class="col-12 d-flex justify-content-center p-2"><div class="form-group"><div class="form-widget">'
        var endDiv = '</div></div></div></div>'
        var black = '<label class="form-label">Couleur Noir</label><input type="file" class="form-control frfu-pics-black-motifgallery p-1">';
        var white = '<label class="form-label">Couleur Blanc</label><input type="file" class="form-control frfu-pics-white-motifgallery p-1">';
        var gold = '<label class="form-label">Couleur Doré</label><input type="file" class="form-control frfu-pics-gold-motifgallery p-1">';
        
        var element = div+black+white+gold+endDiv;
        $($('#new-motifgallery-form .row, #edit-motifgallery-form .row')[0]).find('#frfu-motifgallery-block').append(element);
        numberFields++;
    }

    if($('#new-motifgallery-form').length){
        $($('#new-motifgallery-form .row')[0]).append('<div id="frfu-motifgallery-block" class="col-12 p-2 bg-white border">\
            </div class="row">\
                <div class="col-6"><span id="frfu-add-motifgallery" class="btn btn-primary d-none">Ajouter une fixation</span></div>\
                <div class="col-6"><span id="frfu-submit-motifgallery" class="btn btn-primary">Enregistrer les modifications</span></div>\
            </div>\
        </div>');
    }

    if($('#edit-motifgallery-form').length){
        $($('#edit-motifgallery-form .row')[0]).append('<div id="frfu-motifgallery-block" class="col-12 p-2 bg-white border">\
            </div class="row">\
                <div class="col-6"><span id="frfu-add-motifgallery" class="btn btn-primary">Ajouter une fixation</span></div>\
                <div class="col-6"><span id="frfu-submit-motifgallery" class="btn btn-primary">Enregistrer les modifications</span></div>\
            </div>\
        </div>');

        metaData = JSON.parse(JSON.parse($('#motifgallery_metaData').val()));
        //console.log(metaData);
    }

    $('#new-motifgallery-form, #edit-motifgallery-form').delegate('#frfu-submit-motifgallery', 'click', function(){
        encodeMeta();
    });

    $('#new-motifgallery-form, #edit-motifgallery-form').delegate('#frfu-add-motifgallery', 'click', function(){

        //var uniq = uniqKey();
        addField();
    });

    

    $('#new-motifgallery-form, #edit-motifgallery-form').delegate('.frfu-pics-black-motifgallery', 'change', function(e){
        var uniq = $(this).attr('data-id');
        var file = $(this)[0].files[0];
        inputChange(file, 'black');
    });

    $('#new-motifgallery-form, #edit-motifgallery-form').delegate('.frfu-pics-white-motifgallery', 'change', function(){
        console.log('caught !!!');
        var uniq = $(this).attr('data-id');
        var file = $(this)[0].files[0];
        inputChange(file, 'white');
    });

    $('#new-motifgallery-form, #edit-motifgallery-form').delegate('.frfu-pics-gold-motifgallery', 'change', function(){
        console.log('caught !!!');
        var uniq = $(this).attr('data-id');
        var file = $(this)[0].files[0];
        inputChange(file, 'gold');
    });
    
        $('#new-motifgallery-form, #edit-motifgallery-form').find('#frfu-add-motifgallery').trigger('click');
    
});