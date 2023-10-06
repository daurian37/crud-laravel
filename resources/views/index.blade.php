<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laravel</title>
  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css' />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>

<style>
    span.select2-selection{
        width: 212px;
    }

    .select2-container--open .select2-dropdown--below {
        z-index: 1000000 !important;
    }

</style>
<body>
  <div class="container">
    <div class="row my-5">
      <div class="col-lg-12">
        <h2 class="text-center">Liste de recettes</h2>
        <div class="card shadow mt-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="text-light">Les recettes</h3>
            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addRecipeModal"><i
                class="bi-plus-circle me-2"></i>Ajouter une nouvelle recette</button>
          </div>
          <div class="card-body" id="show_all_recipe">
            <h1 class="text-center text-secondary my-5">Loading...</h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- new recipe modal --}}
<div class="modal fade" id="addRecipeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ajouter une nouvelle recette</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <form method="POST" id="add_recipe_form" enctype="multipart/form-data">
            @csrf
            <div class="modal-body p-4 bg-light">
                <div class="row">
                    <div class="col-md-6">
                        <label for="titre">Titre</label>
                        <input id="titre" type="text" name="titre" class="form-control" placeholder="Un titre">
                    </div>
                    <div class="col-md-6">
                        <label for="description">Description</label>
                        <input id="description" type="text" name="description" class="form-control" placeholder="Une description">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="ingredient">Ingrédient</label>
                        <select id="ingredient" name="ingredient" class="form-control">

                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermé</button>
                <button type="submit" id="add_recipe_btn" class="btn btn-primary">Ajouter une recette</button>
            </div>
        </form>
    </div>
  </div>
</div>
 
{{-- edit recipe modal --}}
<div class="modal fade" id="editRecipeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="edit_recipe_form" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="emp_id" id="emp_id">
        <div class="modal-body p-4 bg-light">
          <div class="row">
            <div class="col-md-6">
              <label for="titreRecette">Titre</label>
              <input id="titreRecette" type="text" name="titre" class="form-control" placeholder="Un titre" required>
            </div>
            <div class="col-md-6">
              <label for="description">Description</label>
              <input id="description" type="text" name="description" class="form-control" placeholder="Une description" required>
            </div>
            <!--div class="col-lg mt-2">
              <label for="ingredientRecette">Ingrédient</label>
              <select name="ingredient" id="ingredientRecette" class="form-control">
              </select>
            </div-->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermé</button>
          <button type="submit" id="edit_recipe_btn" class="btn btn-success">Modifier la recette</button>
        </div>
      </form>
    </div>
  </div>
</div>
 
<script src='https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js'></script>
  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    $(function() {
        $(document).ready(function() {
            $("#ingredient").select2({
                // placeholder: 'Sélectionnez des ingrédients',
                allowClear: true,
                ajax: {
                    url: '{{ route('get-ingredient') }}',
                    method: 'get', // Change this to 'get' since you're using a GET request
                    dataType: 'json',
                    delay: 250,
                    data: function(params) 
                    {
                        return {
                            searchItem: params.term,
                            page: params.page
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data,
                            pagination: {
                                more: data.last_page != params.page
                            }
                        };
                    },
                    cache: true
                },
                templateResult: function(data) {
                    if (data.loading) {
                        return data.text;
                    }
                    return data.titre;
                },
                templateSelection: function(data) {
                    return data.titre;
                },
            });
        });

       // add new recipe ajax request
    $("#add_recipe_form").submit(function(e) {
      e.preventDefault();
      const titre = $("#titre").val(); // Récupérez la valeur du champ titre
      const description = $("#description").val(); // Récupérez la valeur du champ description
      const ingredient = $("#ingredient").val(); // Récupérez la valeur du champ ingredient

      if (!titre || !description || !ingredient) {
        // Vérifiez si les champs sont vides
        alert("Veuillez remplir tous les champs obligatoires.");
        return;
      }

      const fd = new FormData(this);
      $("#add_recipe_btn").text('Ajout en cours...');

      $.ajax({
        url: '{{ route('store') }}',
        method: 'post',
        data: fd,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
          if (response.status == 200) {
            Swal.fire(
              'Ajouté !',
              'Recette ajoutée avec succès !',
              'success'
            )
            fetchAllRecipes(); // Rechargez la liste des recettes après l'ajout
          }
          $("#add_recipe_btn").text('Ajouter une recette');
          $("#add_recipe_form")[0].reset(); // Réinitialisez le formulaire après l'ajout
          $("#addRecipeModal").modal('hide'); // Fermez la fenêtre modale d'ajout
        },
        error: function(xhr, status, error) {
          // Gérez les erreurs ici
          alert("Une erreur s'est produite lors de l'ajout de la recette : " + xhr.responseText);
        }
      });
    });
 
      // edit recipe ajax request
      $(document).on('click', '.editIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
          url: '{{ route('edit') }}',
          method: 'get',
          data: {
            id: id,
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            $("#titreRecette").val(response.titre);
            $("#description").val(response.description);
            $("#ingredientRecette").val(response.ingredient);
            $("#emp_id").val(response.id);
          }
        });
      });
 
      // update recipe ajax request
        $("#edit_recipe_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
            $("#edit_recipe_btn").text('Modifier...');
            $.ajax({
                url: '{{ route('update') }}',
                method: 'post',
                data: fd,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                if (response.status == 200) {
                    Swal.fire(
                    'Mis à jour !',
                    'Recette mis à jour avec succès !',
                    'success'
                    )
                    fetchAllRecipes();
                }
                $("#edit_recipe_btn").text('Mettre à jour de la recette');
                $("#edit_recipe_form")[0].reset();
                $("#editRecipeModal").modal('hide');
                }
            });
        });

 
      // delete recipe ajax request
      $(document).on('click', '.deleteIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        let csrf = '{{ csrf_token() }}';
        Swal.fire({
          title: 'êtes-vous sûr ?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Oui, supprimer'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '{{ route('delete') }}',
              method: 'delete',
              data: {
                id: id,
                _token: csrf
              },
              success: function(response) {
                console.log(response);
                Swal.fire(
                  'Deleted!',
                  'La recette a été supprimer',
                  'success'
                )
                fetchAllRecipes();
              }
            });
          }
        })
      });
 
      // fetch all recipe ajax request
      fetchAllRecipes();
 
      function fetchAllRecipes() {
        $.ajax({
          url: '{{ route('fetchAll') }}',
          method: 'get',
          success: function(response) {
            $("#show_all_recipe").html(response);
            $("table").DataTable({
              order: [0, 'desc']
            });
          }
        });
      }
    });
  </script>
</body>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</html>