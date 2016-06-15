$(function () {

    var $books = $('#books');

    $.ajax('./api/books.php', {
        dataType: 'json',
        type: 'GET',
        success: function (books) {
            $.each(books, function (i, book) {
                $books.append('<li data-id="' + book.id + '">' + book.name + '   <a class="delete" href="" data-id="' + book.id + '">Usuń</a></li><div></div>');
            });
        },
        error: function () {
            alert('Błąd ładowania strony');
        }
    });

    $books.on('click', 'li', function () {
        var _this = $(this);

        $.ajax("./api/books.php?id=" + $(this).data('id'), {
            type: 'GET',
            dataType: 'json',
            success: function (book) {
                _this.next().show().text('Autor:' + book.author + ' Opis:' + book.desc).append(' <br> <a class="close" href="">Zamknij</a>'
                        + '<form>'
                        + '<br>'
                        + '<label>Edytuj książkę</label><br>'
                        + '<input id="up_title" type="text" placeholder="Tytuł"/><br>'
                        + '<input id="up_author" type="text" placeholder="Autor"/><br>'
                        + '<textarea id="up_desc" cols="25" rows="5"  placeholder="Opis"/><br><br>'
                        + '<button id ="update" data-id="' + book.id + '" type="submit">Zmień</button>'
                        + '</form><br>');


                $('.close').on('click', function (e) {
                    e.preventDefault();
                    $(this).parent().hide();
                })
            }
        });
    });

    $books.on('click', '.delete', function () {

        if (confirm('Czy na pewno chcesz usunąć')) {
            $.ajax("./api/books.php?id=" + $(this).data('id'), {
                type: 'DELETE',
                dataType: 'json',
                success: function () {
                    location.reload();
                }
            });
        }
    });


    $books.on('click', '#update', function (e) {
        e.preventDefault();

        var title = $('#up_title').val();
        var author = $('#up_author').val();
        var desc = $('#up_desc').val();
        var id = $(this).data('id');

        $.ajax("./api/books.php", {
            dataType: 'json',
            type: 'PUT',
            data: {
                id: id,
                name: title,
                author: author,
                description: desc
            },
            success: function () {
                alert('Udana edycja książki');
                location.reload();
            },
            error: function () {
                alert('Błąd edycji książki');
            }
        });
    });

    $('#add').on('click', function (e) {
        e.preventDefault();

        var title = $('#title').val();
        var author = $('#author').val();
        var desc = $('#desc').val();

        $.ajax('./api/books.php', {
            dataType: 'json',
            type: 'POST',
            data: {
                name: title,
                author: author,
                desc: desc
            },
            success: function (newBook) {
                $books.append('<li>' + newBook.name + '</li><div></div>');
                location.reload();
            },
            error: function () {
                alert('Błąd dodania książki');
            }
        });
    });


});