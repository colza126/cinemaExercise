//funzione che crea la finestra di conferma
function conferma(){
    var ris =confirm("Sei sicuro di voler eliminare il film?");
    if(ris){
        return true;
    }
    else{
        return false;
    }
}
//funzione che crea un opzione per un select (per il filtro)
function aggiungiOpzione(selectId, valore, testo) {
    var select = document.getElementById(selectId);
    var option = document.createElement("option");
    option.value = valore;
    option.text = testo;
    select.add(option);
}

//funzione che carica i film
function caricaFilm(genere, permessi_admin) {
    //fai una richiesta ajax per ottenere i film
    $.ajax({
        type: 'POST',
        url: 'ajax/getFilm.php',
        dataType: 'json',
        //nel caso la risposta abbia successo
        success: function(response) {
            if (response.auth == 1) {
                if (response.film) {
                    //per ogni film
                    $.each(response.film, function(index, film) {
                        //controlla il genere e i permessi
                        if (permessi_admin == true && (genere == "Tutti" || genere == film.genere)) {
                            //visualizza il film con i pulsanti per eliminare e modificare
                            var filmCard = `
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${film.nome}</h5>
                                        <p class="card-text">
                                            Anno Produzione: ${film.anno_produzione}<br>
                                            Genere: ${film.genere}<br>
                                            Bio: ${film.bio}
                                        </p>
                                        <a href="dettaglioFilm.php?ID=${encodeURIComponent(film.ID)}" class="btn btn-primary mr-2">Dettagli</a>
                                        <button id="eliminaFilm${film.ID}" class="btn btn-danger" value="${encodeURIComponent(film.ID)}">Elimina</button>
                                    </div>
                                </div>
                            </div>`;
                            //inserisci il div contente il film all'interno del container
                            $('#filmContainer').append(filmCard);
                            //se viene cliccato il tasto per elimnare il film
                            $(`#eliminaFilm${film.ID}`).click(function(e) {
                                //conferma
                                if(conferma() == false){
                                    return;
                                }
                                //perndo il valore del film
                                e.preventDefault();
                                var id_film = $(this).val();
                                //fai una richiesta ajax per eliminare il film
                                $.ajax({
                                    type: 'POST',
                                    url: 'ajax/cancellaFilm.php',
                                    data: {
                                        "id_film": id_film
                                    },
                                    success: function(response) {
                                        if (response.status == 'success') {
                                            $(`#${film.ID}`).remove();
                                            alert('Film eliminato!');
                                        } else {
                                            alert('Film non eliminato!');
                                        }
                                    }
                                });
                            });
                            //senza i dati del genere visualizzo il film per genere
                        } else if (genere == "Tutti" || genere == film.genere) {
                            var filmCard = `
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${film.nome}</h5>
                                        <p class="card-text">
                                            Anno Produzione: ${film.anno_produzione}<br>
                                            Genere: ${film.genere}<br>
                                            Bio: ${film.bio}
                                        </p>
                                        <a href="dettaglioFilm.php?ID=${encodeURIComponent(film.ID)}" class="btn btn-primary">Dettagli</a>
                                    </div>
                                </div>
                            </div>`;
                            $('#filmContainer').append(filmCard);
                        }
                    });
                    //se l'utente ha i permessi admin visualizzo il pulsante per inserire un film
                    if (permessi_admin == true) {
                        var bottone = '<div class="col-md-12 mt-4"><a href="inserisci.php" class="btn btn-success btn-block">Inserisci film</a></div>';
                        $('#filmContainer').append(bottone);
                    }
                } else {
                    //se non ci sono film disponibili
                    $('#filmContainer').html('<p class="text-muted">Nessun film disponibile.</p>');
                }
            } else {
                //se l'utente non è autenticato
                $('#loginErrorMessage').text('Errore di autenticazione.');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Errore nella richiesta AJAX:', textStatus, errorThrown);
        }
    });
}

//inizializzo la variabile per i permessi admin di deafault a false
var permessi_admin = false;

//quando il documento è pronto
$(document).ready(function() {
    //fai una richiesta ajax per ottenere i generi 
    $.ajax({
        url: 'ajax/controllaGeneri.php',
        type: 'POST',
        dataType: 'json',
        //per ogni genere aggiungo un opzione al filtro
        success: function(response) {
            aggiungiOpzione("filtro", "Tutti", "Tutti");
            $.each(response.generi, function(index, genere) {
                aggiungiOpzione("filtro", genere, genere);
            });
        },
        //eventuale errore
        error: function(xhr, status, error) {
            alert('Si è verificato un errore durante il caricamento dei generi.');
            console.log(xhr.responseText);
        }
    });

    //fai una richiesta ajax per controllare i permessi
    $.ajax({
        type: 'POST',
        url: 'ajax/controllaPermessi.php',
        dataType: 'json',
        //controlla i permessi e nel caso richiama la funzione per caricare i film
        success: function(response) {
            if (response.status !== 'fail') {
                
                caricaFilm("Tutti", response.permessi_admin);

                if(response.permessi_admin == true){
                    permessi_admin = true;
                }else{
                    permessi_admin = false;
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Errore nella richiesta AJAX:', textStatus, errorThrown);
        }
        
        
    });

    //quando il filtro cambia modifica i film
    $("#filtro").change(function() {
        $("#filmContainer").empty();
        caricaFilm($("#filtro").val(), permessi_admin);
    });
    
});
