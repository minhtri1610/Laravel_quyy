$(document).on('click', '.btn-suguest-nickname', function (event) {
    event.preventDefault();
    let route = $(this).data('route-suggest');

    if ($('#modal-verify').length) {
        var data = {
            'route' : route,
            'full_name': $('#modal-verify #m_fullname').val(),
            'gender': $('#modal-verify #m_gender').html(),
            '_token': $('meta[name="csrf-token"]').attr('content')
        }
    } else{
        var data = {
            'route' : route,
            'full_name': $('#full_name').val(),
            'gender': $('select[name="gender"] option:selected').text(),
            '_token': $('meta[name="csrf-token"]').attr('content')
        }
    }
    
    gererateNames(data);
})    

function gererateNames(data){
    $('.loader').show();
    $.ajax({
        url: data.route,
        type: 'POST',
        data: data,
        success: function (result) {
            $('.loader').hide();
            if(result.success == true){
                console.log(result.data);
                showNickName(result.data);
            } else{
                toastr.error(result.message, "Lỗi");
            }
        },
        error: function (error) {
            $('.loader').hide();
            console.log(error);
        }
    });
}

function showNickName(data){
    let element = $('.m-list-nickname');
    let html = '';
    var nameArray = data.split(',').map(function(data) {
        return data.trim(); // loại bỏ khoảng trắng thừa
    });
    $.each(nameArray, function (key, value) {
        html += `<button type="button" onclick="setNickName(this)" class="btn btn-outline-success m-1" data-nickname="${value}">${value}</li>`;
    });
    element.html(html);
}

function setNickName(e){
    let nickname = $(e).data('nickname');
    $('#m_nick_name').val(nickname);
}
