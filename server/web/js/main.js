function ajax(path, parameters, success_function, failure_function, element)
{
    $.ajax({
        url: path,
        type:'post',
        data:parameters,
        dataType:'text',
        success:function(response){

            if (success_function != undefined)
            {
                success_function(response, element);
            }
        },
        error:function(response) {

            if (failure_function != undefined)
            {
                failure_function(response, element);
            }else{
                console.log(response);
            }
        }
    });
}