$(document).ready(function(){
		Member = Backbone.Model.extend({

            defaults : {
                id : "noid",
                image : "",
                username : "",
                country : "united states"
            },

            initialize : function Member() {
                console.log('User Constructor');
            }
        });
});

