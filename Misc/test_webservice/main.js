$(document).ready(function(){

    $('.formulaire').on('submit', function(e){
        e.preventDefault();
    });

    $('.trigger_event').on('click', function(){
        var objects = {
            'authentificateDevelopper' : {
                'methode': 'authentificateDevelopper',
                'params' : {
                    'developper_name': 'bucket_team',
                    'developper_secret': 'dc3b22c53f62d1722c18127658f816cd',
                }
            },
            'saveScore' : {
                'module': 'score',
                'action': 'saveScore',
                'params' : {
                    'player_name': $('.player_name').val(),
                    'player_highscore': $('.player_score').val(),
                }
            },
            'getHighScores' : {
                'module': 'score',
                'action': 'getHighScores',
            },
        };

        $('.results').text('');
        var appenTo = $(this).attr('display');
        var methode = $(this).attr('called-method').toString();
        var datas = objects[methode];
        callApi(datas);
    })

    function callApi(datas) {

        var dev = {'developper_token': '6c3a235a40020e288792b158896db4bc'};

        var finalDatas = objectMerge(dev, datas);

        $.ajax({
            url: 'http://vacherot.etudiant-eemi.com/perso/ar_project/',
            type: 'GET',
            crossDomain: true,
            datatype: 'json',
            data: finalDatas,
            beforeSend: function () {
                //   $('.loader').show();
            },
            success: function(data) {
              console.log(data);
            },
        });
    }


    function objectMerge(first, second){
        var finalObject = {};

        $.each(first, function(key, value){
            finalObject[key] = value;
        });
        $.each(second, function(key, value){
            finalObject[key] = value;
        });

        return finalObject;
    }
});
