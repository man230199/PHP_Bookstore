$(document).ready(function() {
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        let result = confirm('Are you sure?');
        if (result) {
            window.location.href = $(this).attr('href');
        }
    })
});

function changePage(page){
    alert(page);
	$('input[name=filter_page]').val(page);
	$('#table-form').submit();
};


$('#apply-button').on('click',function() {
    let url = $(this).data('url');
    let selectVal = $('#action-select').val();
    if(selectVal == '') {
        alert('vui lòng chọn action');
    }else {
        let checkboxVal = $('input[name="cid[]"]:checked').length;
        if(checkboxVal) {
            url = url.replace('new_action', selectVal);
            $('#table-form').attr('action',url);
            $('#table-form').submit();
        }else {
            alert("Vui lòng chọn ít nhất 1 dòng dữ liệu");
        }   
    }
});


//CHANGE CATEGORY ID
$('select[name="category_id"]').on('change', function() {
    let selectValue = $(this).val();
    let url = $(this).data("url");
    window.location.href = url.replace('new_category_id', selectValue);
})


function randomString(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * 
 charactersLength));
   }
   return result;
   
}

$('#generate-password').on('click', function() {
    let random_string = randomString(8);
    console.log('random: ' + random_string);
    let inputPassword = $('input[name="form[password]"]').val();
    let newPassword = $('input[name="form[password]"]').val().replace(inputPassword,random_string);
    $('input[name="form[password]"]').val(newPassword);
    //console.log($('input[name="form[password]"]').val());
    
});

$('input[name="picture"]').on('change', function(event) {
    var output = document.getElementById('preview_picpure');
    output.src = URL.createObjectURL(event.target.files[0]);
});


function getURLParameters(url){

    var result = {};
    var hashIndex = url.indexOf("#");
    if (hashIndex > 0)
       url = url.substr(0, hashIndex);        
    var searchIndex = url.indexOf("?");
    if (searchIndex == -1 ) return result;
    var sPageURL = url.substring(searchIndex +1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {       
        var sParameterName = sURLVariables[i].split('=');      
        result[sParameterName[0]] = sParameterName[1];
    }
    return result;
}

$(document).on("click", ".ajax-attr-btn", function () {
    let new_url = $(this).data("url");
    let parent = $(this).parent();
    console.log(new_url);
    $.ajax({
        type: "GET",
        url: new_url,
        //data: param,
        dataType: "json",
        success: function (response) {
            if(response.status == 'success') {
                parent.html(response.data.item);
                console.log(response);
                parent.find('button').notify(
                    "Cập nhật thành công", 
                    { 
                        position:"top center",
                        className: 'success',
                     }
                  );
            }
            
        },
    });
});

$(document).on("change", ".ajax-attr-slb", function () {
    let new_url = $(this).data("url");
    let parent = $(this).parent();
    let selectValue = $(this).val();
    new_url = new_url.replace('new_value',selectValue);
    console.log(new_url);
    $.ajax({
        type: "GET",
        url: new_url,
        //data: param,
        dataType: "json",
        success: function (response) {
            if(response.status == 'success') {
                parent.find('select').notify(
                    "Cập nhật thành công", 
                    { 
                        position:"top center",
                        className: 'success',
                     }
                  );
            }
        },
    });
});

$(document).on("change", ".ajax-attr-input", function () {
    let new_url = $(this).data("url");
    let parent = $(this).parent();
    let selectValue = $(this).val();
    new_url = new_url.replace('new_value',selectValue);
    console.log(new_url);
    $.ajax({
        type: "GET",
        url: new_url,
        //data: param,
        dataType: "json",
        success: function (response) {
            if(response.status == 'success') {
                parent.find('input').notify(
                    "Cập nhật thành công", 
                    { 
                        position:"top center",
                        className: 'success',
                     }
                  );
            }
        },
    });
});


$('select[name="special"]').on('change', function() {
    this.form.submit();
});

$('select[name="filter_category_id"]').on('change', function() {
    this.form.submit();
});

/* $('input[type=number][name="form[ordering]"]').on('change', function() {
    let orderingValue = $(this).val();
    let url = $(this).data("url");
    console.log(orderingValue);
    window.location.href = url.replace('new_ordering_value', orderingValue);
}); */


