$(document).ready(function () {

    let allGenres = new Set();
    let searchTimer = null;
    let watchlistIds = new Set();
    let currentView = 'browse';

    function renderMovieCard(movie) {

        const rating = movie.average_rating
            ? '<span class="rating">★ ' + parseFloat(movie.average_rating).toFixed(1) + '</span>'
            : '<span class="unrated">— unrated</span>';

        const year = movie.release_date
            ? new Date(movie.release_date).getFullYear()
            : '';

        const inList = watchlistIds.has(String(movie.id));

        const card = $('<div class="movie-item">');

        card.append(
            $('<img>')
                .attr('src', movie.poster_url)
                .attr('alt', movie.title)
        );

        const listBtn = $('<button class="list-btn">')
            .html(inList ? '<i class="fas fa-check"></i>' : '<i class="fas fa-plus"></i>')
            .toggleClass('active', inList)
            .attr('title', inList ? 'Remove from My List' : 'Add to My List')
            .on('click', function (e) {
                e.stopPropagation();
                toggleWatchlist(movie.id, $(this));
            });

        card.append(listBtn);

        card.append(
            $('<div class="card-body">').append(
                $('<h3>').text(movie.title),
                $('<div class="movie-meta">').append(
                    $('<span>').text(
                        (movie.genre || '') +
                        (year ? ' · ' + year : '')
                    ),
                    $(rating)
                )
            )
        );

        card.on('click', function () {
            window.location.href = 'movie_detail.php?id=' + movie.id;
        });

        return card;
    }

    function toggleWatchlist(movieId, $btn) {
        const inList = $btn.hasClass('active');
        const action = inList ? 'remove' : 'add';

        $.ajax({
            url: 'watchlist.php?action=' + action,
            method: 'POST',
            data: { movie_id: movieId },
            dataType: 'json',
            success: function (response) {
                if (!response.success) {
                    alert('Could not update your list.');
                    return;
                }

                if (action === 'add') {
                    watchlistIds.add(String(movieId));
                    $btn.addClass('active')
                        .html('<i class="fas fa-check"></i>')
                        .attr('title', 'Remove from My List');
                } else {
                    watchlistIds.delete(String(movieId));
                    $btn.removeClass('active')
                        .html('<i class="fas fa-plus"></i>')
                        .attr('title', 'Add to My List');

                    if (currentView === 'mylist') {
                        $btn.closest('.movie-item').fadeOut(200, function () {
                            $(this).remove();
                        });
                    }
                }
            },
            error: function () {
                alert('Could not update your list.');
            }
        });
    }

    function renderMovies(movies) {

        const container = $('#movies-container');

        container.empty();

        $('#results-count').text(
            movies.length +
            (movies.length === 1 ? ' title' : ' titles')
        );

        if (!movies.length) {
            container.append(
                '<div class="no-results">' +
                (currentView === 'mylist'
                    ? 'Your list is empty.'
                    : 'No titles match your search.') +
                '</div>'
            );
            return;
        }

        movies.forEach(function (movie) {
            container.append(renderMovieCard(movie));

            if (movie.genre) allGenres.add(movie.genre);
        });

        const currentGenre = $('#genre-filter').val();

        $('#genre-filter')
            .empty()
            .append('<option value="">All Genres</option>');

        Array.from(allGenres).sort().forEach(function (genre) {
            $('#genre-filter').append($('<option>').val(genre).text(genre));
        });

        $('#genre-filter').val(currentGenre);
    }

    function loadMovies() {
        $.ajax({
            url: 'movies.php?action=fetchMovies',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                renderMovies(response);
            }
        });
    }

    function loadMyList() {
        $.ajax({
            url: 'watchlist.php?action=list',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                renderMovies(response);
            }
        });
    }

    function loadWatchlistIds(callback) {
        $.ajax({
            url: 'watchlist.php?action=ids',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                watchlistIds = new Set((response || []).map(String));
                if (callback) callback();
            },
            error: function () {
                if (callback) callback();
            }
        });
    }

    function runSearch() {

        const q = $('#search-input').val().trim();
        const genre = $('#genre-filter').val();

        if (!q && !genre) {
            loadMovies();
            return;
        }

        $.ajax({
            url: 'movies.php?action=searchMovies',
            method: 'GET',
            data: { q: q, genre: genre },
            dataType: 'json',
            success: function (response) {
                renderMovies(response);
            }
        });
    }

    $('#search-input').on('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(runSearch, 300);
    });

    $('#genre-filter').on('change', runSearch);

    // Browse / My List tab switching
    $('#browse-tab').on('click', function (e) {
        e.preventDefault();
        currentView = 'browse';
        $(this).addClass('active');
        $('#mylist-tab').removeClass('active');
        $('.trending-movies').show();
        $('#search-input, #genre-filter').prop('disabled', false).show();
        loadMovies();
    });

    $('#mylist-tab').on('click', function (e) {
        e.preventDefault();
        currentView = 'mylist';
        $(this).addClass('active');
        $('#browse-tab').removeClass('active');
        $('.trending-movies').hide();
        $('#search-input, #genre-filter').hide();
        loadMyList();
    });

    // Trending carousel
    $.ajax({
        url: 'movies.php?action=fetchTrendingMovies',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            const carousel = $('.carousel');
            carousel.empty();

            $.each(response, function (index, movie) {
                const item = $('<div class="carousel-item">').append(
                    $('<img>').attr('src', movie.poster_url).attr('alt', movie.title),
                    $('<h3>').text(movie.title)
                );

                item.on('click', function () {
                    window.location.href = 'movie_detail.php?id=' + movie.id;
                });

                carousel.append(item);
            });
        }
    });

    // Initial load: fetch which movies are already listed, then render
    loadWatchlistIds(loadMovies);

    $('#change-password-btn').on('click', function () {
        $('.movie-listing').toggle();
        $('.trending-movies').toggle();
        $('#change-password-section').toggle();
    });

    $('#change-password-form').on('submit', function (event) {
        event.preventDefault();

        $.ajax({
            url: 'change_password.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function () {
                alert('Password changed successfully!');
                $('#change-password-section').hide();
                $('.movie-listing').show();
                $('.trending-movies').show();
            },
            error: function () {
                alert('An error occurred while changing the password.');
            }
        });
    });

});
