$(document).ready(function(){
    var deletes = $('.delete');
    var edits = $('.edit');

    deletes.on('click',function () {
        deleteNews($(this).data());
    });

    edits.on('click',function () {
        editNews($(this).data());
    });

    $('#logout').on('click', function () {
        redirect('/logout');
    });

    $('#cancel-icon').on('click', function () {
        cancelEdit();
    });

});

function cancelEdit() {
    $('#title').val('');
    $('#content').val('');
    $('input.button').prop('value', 'Create');
    $('form').prop('action', '/news/add');
    $('#news-form').text('Create News');
    $('#cancel').addClass('hidden');
}

function editNews(dataValues)
{
    var data = {};
    data['id'] = dataValues.id;
    sendAjax('/news/getsingle', data).done(function (data) {
        if (data.status === 'OK') {
            $('#title').val(data.title);
            $('#content').val(data.content);
            $('input.button').prop('value', 'Edit');
            $('form').prop('action', '/news/edit?id=' + data.id);
            $('#news-form').text('Edit News');
            $('#cancel').removeClass('hidden');
        } else {
            refreshPage();
        }
    })
}

function deleteNews(dataValues)
{
    var data = {};
    data['id'] = dataValues.id;
    sendAjax('/news/delete', data).done(function (data) {
        refreshPage();
    });
}

function sendAjax(url, data)
{
    return $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json"
    });
}

function refreshPage()
{
    location.reload();
}

function redirect(url)
{
    location.replace(url);
}