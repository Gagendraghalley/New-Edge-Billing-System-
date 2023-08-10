try {
    Vue.mixin({
        methods: {
            remove_error(field_id){
                if($('#'+field_id).val()!=""){
                    $('#'+field_id).removeClass('is-invalid');
                    $('#'+field_id+'_err').html('');
                }
            },
        },
    })

} catch (e) {
    console.log('error in main common js: '+e);
}

