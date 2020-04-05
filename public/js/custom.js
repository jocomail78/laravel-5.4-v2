//global variables
var ajaxSearch = null;
var ajaxPaging = null;

$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $(document).on('click','.js-delete-user',function(){
        if(!confirm("Are you sure that you want to delete this user?")){
            return;
        }
        var href = $(this).attr('href');
        var tthis = $(this);

        $.ajax({
            url: href,
            type: 'DELETE',
            success: function(response) {
                if(response.success){
                    //remove line
                    tthis.closest('tr').fadeOut(300, function(){ $(this).remove();});
                }else{
                    //show error message
                    alert(response.message);
                }
            },error: function(error) {
                console.log('Ajax error: '+error);
            }
        });
        return false;
    });

    $(document).on('click','.js-unverify-user',function(){
        var href = $(this).attr('href');
        var tthis = $(this);

        $.ajax({
            url: href,
            type: 'GET',
            success: function(response) {
                if(response.success){
                    //remove line
                    tthis.fadeOut(300, function(){ $(this).remove();});
                }else{
                    //show error message
                    alert(response.message);
                }
            },error: function(error) {
                console.log('Ajax error: '+error);
            }
        });
        return false;
    });

    $(document).on('keyup','.js-user-search',function(){
        var val = $(this).val();
        var url = '';
        if(val.length){
            url = '/users/search/'+encodeURI(val);
        }else{
            url = 'users';
        }

        if(ajaxSearch && ajaxSearch.readyState != 4){
            ajaxSearch.abort();
        }
        ajaxSearch = $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('.user-list-holder').html(response);
            },error: function(error) {
                console.log('Ajax error: '+error);
            }
        });
        return false;
    });

    $(document).on('click','.isSearch .pagination li a',function(e){
        e.preventDefault();

        var url = $(this).attr('href');

        if(ajaxPaging && ajaxPaging.readyState != 4){
            ajaxPaging.abort();
        }
        ajaxPaging = $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('.user-list-holder').html(response);
            },error: function(error) {
                console.log('Ajax error: '+error);
            }
        });

        return false;
    });

    $(document).on('click','.js-delete-term',function(){
        if(!confirm("Are you sure that you want to delete this terms of services?")){
            return;
        }
        var href = $(this).attr('href');
        var tthis = $(this);

        $.ajax({
            url: href,
            type: 'DELETE',
            success: function(response) {
                if(response.success){
                    //remove line
                    tthis.closest('tr').fadeOut(300, function(){ $(this).remove();});
                }else{
                    //show error message
                    alert(response.message);
                }
            },error: function(error) {
                console.log('Ajax error: '+error);
            }
        });
        return false;
    });

});
